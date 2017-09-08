<?php

/**
 * Place this file into a whmcs root dir
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once 'modules/servers/vproduct/functions.php';

load_api('client');
use Illuminate\Database\Capsule\Manager as Capsule;

define("CLIENTAREA", true);

require_once 'init.php';

$ca = new WHMCS_ClientArea();

$ca->setPageTitle('Reissue SSL Certificate');

$ca->addToBreadCrumb('index.php', Lang::trans('globalsystemname'));
$ca->addToBreadCrumb('reissuessl.php', 'Reissue SSL Certificate');

$ca->initPage();

$ca->requireLogin();


$ca->assign('url', 'reissuessl.php');

if ($ca->isLoggedIn()) {

    $serviceId = $_GET['serviceId'];
	$step = $_GET['step'];
	$domainname = $_POST['domain'];

    $hosting = Capsule::table('tblhosting')->where('id', $serviceId)->first();

    if ((int)$ca->getUserID() !== (int)$hosting->userid) {

 header('HTTP/1.0 403 Forbidden');

        echo 'You are forbidden!';

        exit;
    }

	$ca->assign('serviceid', $serviceId);
	$ca->assign('step', $step);

	if(empty($step))
	{
	$order = Capsule::table('versio_orders')->where('service_id', $serviceId)->first();

    if (!$order) {
		$ca->assign('errorMessage', 'No certificate requested');
	}

	}elseif($step == 2)
	{
		$order = Capsule::table('versio_orders')->where('service_id', $serviceId)->first();

    if (!$order) {
		$ca->assign('errorMessage', 'No certificate requested');
	}

	$login = getlogin();

	// user defined configuration values
  $username = $login[0]->value;
	$password = $login[1]->value;
  $endpoint = $login[2]->value;
	$testmode = $login[3]->value;

	if(empty($login))
	{
		$ca->assign('errorMessage', 'Error: Contact the hosting company.');
	}

	$versio = new Versio_API();
	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

    $response = $versio->request('GET', '/sslapprovers/'.$domainname);

	if(empty($response['approverList']))
	{
		$ca->assign('errorMessage', 'No domainname set');
	}else{
	$ca->assign('sslapprovers', $response['approverList']);
	}
	}elseif($step == 3)
	{

    $product = Capsule::table('tblproducts')->where('id', $hosting->packageid)->first();

    $order = Capsule::table('versio_orders')->where('service_id', $serviceId)->first();

    if (!$order) {
		$ca->assign('errorMessage', 'No certificate requested');
	}else{

	$login = getlogin();

	// user defined configuration values
  $username = $login[0]->value;
	$password = $login[1]->value;
  $endpoint = $login[2]->value;
	$testmode = $login[3]->value;

	if(empty($login))
	{
		$ca->assign('errorMessage', 'Error: Contact the hosting company.');
	}

	$versio = new Versio_API();
	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	$details = $_POST;
	$details['product_id'] = $product->configoption1;
	$details['years'] = 1;
	$details['auto_renew'] = 0;

    $response = $versio->request('POST', '/sslcertificates/'.$order->order_id.'/reissue', $details);

	if($response['error'])
		{
			$ca->assign('errorMessage', $response['error']['message']);
		}else{

			if($response['id'])
		{

			$ca->assign('message', 'Certificate reissued');

	if($response['error'])
		{
			$ca->assign('errorMessage', $response['error']['message']);
		}

	}
		}
		}
	}else{
		$ca->assign('errorMessage', 'Contact the hosting company');
	}
}
$ca->setTemplate('vproduct');
$ca->output();
