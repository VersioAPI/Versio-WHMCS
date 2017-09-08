<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use libphonenumber\PhoneNumberUtil;

function load_api($type)
{
	if($type == 'client')
	{
		include('modules/registrars/versio/class_versio_api.php');
	}else{
		include('../modules/registrars/versio/class_versio_api.php'); 
	}

}

/**
 * @param $params
 *
 * @return string
 * @throws Exception
 */
function getlogin()
{
	$logincredentials = Capsule::table('tbladdonmodules')->select(array('setting', 'value'))->whereIn('setting',array('Username', 'Password', 'TestMode'))->where('module', 'vaddon')->get();
return $logincredentials;
}

function create($params)
{
    $order = Capsule::table('versio_orders')->where('service_id', $params['serviceid'])->first();

    if ($order) {
        return 'success';
    }else{

        //$product = Capsule::table('versio_products')->where('brand_name', $params['configoption1'])->first();
        //$productId = $product->product_id;

        //$hosting = Capsule::table('tblhosting')->where('id', $params['serviceid'])->first();
        //$billingCycle = $hosting->billingcycle;


        Capsule::table('versio_orders')->insert([
            'id' => null,
            'product_id' => $params['configoption1'],
            'order_id' => 0,
            'status' => '',
            'creation_date' => date('Y-m-d H:i:s', time()),
            'activation_date' => '1970-01-01 00:00:00',
            'expiration_date' => '1970-01-01 00:00:00',
            'changed_at' => date('Y-m-d H:i:s', time()),
            'service_id' => $params['serviceid'],
        ]);

    return 'success';
	}
}

/**
 * @param $params
 *
 * @return string
 * @throws Exception
 */
function cancel($type, $params, $order)
{
	load_api($type);

	$login = getlogin();

	// user defined configuration values
	$username = $login[0]->value;
	$password = $login[1]->value;
	$endpoint = $login[2]->value;
	$testmode = $login[3]->value;

	if(empty($login))
	{
		$errormessage = 'Error: Contact the hosting company.';
		return $errormessage;
		die();
	}


	$versio = new Versio_API();
	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	$response = $versio->request('POST', '/sslcertificates/'.$order.'/cancel');

	if($response['error'])
		{
			$errormessage = $response['error']['message'];
			return $errormessage;
		}else{
			return 'success';

						    //save update status into a DB
    Capsule::table('versio_orders')->lockForUpdate();
    Capsule::table('versio_orders')
        ->where('order_id', $order)
        ->update([
			'order_id' => 0,
            'status' => '',
            'activation_date' => '',
            'expiration_date' => '',
        ]);

		}
}

/**
 * @param $params
 *
 * @return array
 */
function updateorder($type, $params, $order)
{

	load_api($type); //type for api location

	$login = getlogin();

	// user defined configuration values
	$username = $login[0]->value;
	$password = $login[1]->value;
	$endpoint = $login[2]->value;
	$testmode = $login[3]->value;

	if(empty($login))
	{
		$errormessage = 'Error: Contact the hosting company.';
	}

	$versio = new Versio_API();
	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

			$response = $versio->request('GET', '/sslcertificates/'.$order);

	if($response['error'])
		{
			$errormessage = $response['error']['message'];
		}else{

			    //save update status into a DB
    Capsule::table('versio_orders')->lockForUpdate();
    Capsule::table('versio_orders')
        ->where('service_id', $params['serviceid'])
        ->update([
            'status' => $response['SSLcertificateInfo']['status'],
            'activation_date' => $response['SSLcertificateInfo']['issue_date'],
            'expiration_date' => $response['SSLcertificateInfo']['expire_date'],
        ]);


		}
		    return [
		'errormessage' => $errormessage,
        'status' => $response['SSLcertificateInfo']['status'],
        'activation_date' => $response['SSLcertificateInfo']['issue_date'],
        'expiration_date' => $response['SSLcertificateInfo']['expire_date'],
    ];
}
