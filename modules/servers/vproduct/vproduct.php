<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use libphonenumber\PhoneNumberUtil;


if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

require_once __DIR__ . '/functions.php'; 

/**
 * @return array
 */
function vproduct_MetaData()
{
    return [
        'DisplayName' => 'Versio product addons',
        'APIVersion' => '1.0', // Use API Version 1.0
        'RequiresServer' => false,
    ];
}

/**
 * @return array
 */
function vproduct_ConfigOptions()
{
	$productlist = array();

	foreach (Capsule::table('versio_products')->get() as $product) {
		$productlist[$product->product_id] = 'ID:'.$product->product_id.' - '.$product->brand_name;
		}

    return [
        'SSL Product' => [
            'Type' => 'dropdown',
            'Options' => $productlist,
        ],
    ];
}

/**
 * @param $params
 *
 * @return string
 */
function vproduct_Cancel($params)
{
	$order = Capsule::table('versio_orders')->where('service_id', $params['serviceid'])->first();
    return cancel('admin', $params, $order->order_id);
}

/**
 * @param array $params
 *
 * @return array|string
 */
function vproduct_ClientArea($params)
{
    $fullMessage = null;
    $order = null;
    $updatedData = [];
	$_lang = $params['_lang']; // an array of the currently loaded language variables

        $order = Capsule::table('versio_orders')->where('service_id', $params['serviceid'])->first();

		if($order->order_id > 0)
		{
        $updatedData = updateorder('client', $params, $order->order_id);

		if($updatedData['status'] == 'PENDING_VALIDATION')
		{

			    return [
        'templatefile' => 'templates/clientarea.tpl',
        'templateVariables' => [
            'linkValue' => '/changeapproverssl.php?serviceId=' . $params['serviceid'],
			'linkName' => 'Change approver',
            'errorMessage' => $updatedData['errormessage'],
            'status' => $updatedData['status'],
            'creationDate' => $order->creation_date,
            'activationDate' => $updatedData['activation_date'],
            'expirationDate' => $updatedData['expiration_date'],
			'enabled' => true,
        ],
    ];

		}else{

		    return [
        'templatefile' => 'templates/clientarea.tpl',
        'templateVariables' => [
            'linkValue' => '/reissuessl.php?serviceId=' . $params['serviceid'],
			'linkName' => 'Reissue SSL',
			'linkValue2' => '/downloadssl.php?serviceId=' . $params['serviceid'],
			'linkName2' => 'Download certificate',
            'errorMessage' => $updatedData['errormessage'],
            'status' => $updatedData['status'],
            'creationDate' => $order->creation_date,
            'activationDate' => $updatedData['activation_date'],
            'expirationDate' => $updatedData['expiration_date'],
			'enabled' => true,
        ],
    ];

		}




		}else{

			    return [
        'templatefile' => 'templates/clientarea.tpl',
        'templateVariables' => [
            'linkValue' => '/requestssl.php?serviceId=' . $params['serviceid'],
            'linkName' => 'Request SSL',
			'status' => '',
			'enabled' => false,
        ],
    ];

		}
}

/**
 * @return array
 */
function vproduct_AdminCustomButtonArray()
{
    return [
        'Cancel Certificate' => 'Cancel'
    ];
}

function vproduct_AdminServicesTabFields($params)
{
$order = Capsule::table('versio_orders')->where('service_id', $params['serviceid'])->first();
	$order->order_id;

		$updatedData = updateorder('admin', $params, $order->order_id);

		if($updatedData['errormessage'])
		{
		$fieldsarray = ['Error' => $updatedData['errormessage']];
		}else{
		$fieldsarray = ['Status' => $updatedData['status'],'Creation Date' => $order->creation_date,'Activation Date' => $updatedData['activation_date'],'Expiration Date' => $updatedData['expiration_date']];
		}

    return $fieldsarray;
}
