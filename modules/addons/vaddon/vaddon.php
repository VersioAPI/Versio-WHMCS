<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

use WHMCS\Module\Addon\Versio\Admin\AdminDispatcher;
use WHMCS\Module\Addon\Versio\Client\ClientDispatcher;

require('../modules/registrars/versio/class_versio_api.php');
/**
 * @return array
 */
function vaddon_config()
{
    return array(
        'name' => 'Versio Product addon',
        'description' => 'Product addon for Versio',
        'version' => '1.1',
        'author' => 'Versio B.V.',
		'language' => 'dutch', // Default language
        'fields' => array(

        'Username' => array(
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your username',
        ),

        'Password' => array(
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Enter your password',
        ),

        'Site-version' => array(
          'Type' => 'dropdown',
          'Options' => array(
              '.nl' => 'NL',
              '.eu' => 'EU',
              '.uk' => 'UK',
          ),
          'Description' => 'Choose which Versio website you want to connect to',
        ),

        'TestMode' => array(
            'Type' => 'dropdown',
            'Options' => array(
                'true' => 'On',
                'false' => 'Off'),
            'Description' => 'Enable or disable TestMode',
 ),
        )
    );
}

/**
 * @param array $vars
 */
function vaddon_output($vars)
{

    if (!empty($_REQUEST['action'])) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }

    $view = [
        'global' => [
            'mod_url' => '?module=vaddon',
            'module' => 'vaddon',
        ],
    ];

    $view['lang'] = $vars['_lang'];

    if ($action === 'list') {

		$view['products'] = Capsule::table('versio_products')->get();
    } else {

        if ($action === 'update') {

	  $username = $vars['Username'];
    $password = $vars['Password'];
    $endpoint = $vars['Site-version'];
    $testmode = $vars['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

    $response = $versio->request('GET', '/sslproducts');

	if($response['error'])
		{
			$view['errorMessage'] = $response['error']['message'];
		}else{
			$view['products'] = $response['sslproductsList'];
                Capsule::table('versio_products')->truncate();

                foreach ($response['sslproductsList'] as $product) {
                     Capsule::table('versio_products')->insert([
                         'id' => null,
                         'product_id' => $product['id'],
                         'type' => $product['type'],
                         'brand_name' => $product['supplier'],
                         'price' => $product['prices']['1_year'],
                         'max_period' => $product['max_years'],
                         'number_of_domains' => ($product['support_san_names'] == 'true') ? '99' : '0',
                         'currency' => '€',
                         'changed_at' => date('Y-m-d H:i:s', time()),
                     ]);
                 }
		}
        } else {
            $action = 'default';
        }
    }

    $view['global']['mod_action_url'] = $view['global']['mod_url'] . '&action=' . $action;
    $view['global']['action'] = $action;

    require __DIR__ . '/templates/' . $action . '.php';
}

/**
 *
 */
function vaddon_activate()
{
    //todo: try via Exception classes
    try {
        Capsule::schema()->create(
            'versio_products',
            function ($table) {
                /** @var \Illuminate\Database\Schema\Blueprint $table */
                $table->increments('id');
                $table->integer('product_id');
                $table->string('type');
                $table->string('brand_name');
                $table->float('price');
                $table->string('currency');
                $table->integer('max_period');
                $table->integer('number_of_domains');
                $table->string('changed_at', 19);
                $table->primary(['id']);
            }
        );
    } catch (\Exception $e) {
        echo "Unable to create versio_products: {$e->getMessage()}";
    }

    try {
        Capsule::schema()->create(
            'versio_orders',
            function ($table) {
                /** @var \Illuminate\Database\Schema\Blueprint $table */
                $table->increments('id');
                $table->integer('product_id');
                $table->integer('order_id');
                $table->string('status', 32);
                $table->string('creation_date', 19);
                $table->string('activation_date', 19);
                $table->string('expiration_date', 19);
                $table->string('changed_at', 19);
                $table->integer('service_id');
                $table->primary(['id']);
            }
        );
    } catch (\Exception $e) {
        echo "Unable to create versio_orders: {$e->getMessage()}";
    }
}

/**
 * @return array
 */
function vaddon_deactivate()
{
    return ['status' => 'success', 'description' => ''];
}

function vaddon_upgrade($vars)
{
    $version = $vars['version'];
}
