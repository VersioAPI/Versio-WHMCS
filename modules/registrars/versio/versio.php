<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Domains\DomainLookup\ResultsList;
use WHMCS\Domains\DomainLookup\SearchResult;

require('class_versio_api.php');

function versio_MetaData()
{
    return array(
        'DisplayName' => 'Versio REST API Module for WHMCS',
        'APIVersion' => '1.1',
    );
}

function versio_getConfigArray()
{
    return array(
        'Username' => array(
            'Type' => 'text',
            'Size' => '25',
            'Default' => '1024',
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
                'false' => 'Off',
            ),
            'Description' => 'Choose one',
        )
    );
}


function versio_RegisterDomain($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

	$versio = new Versio_API();
	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

    // registrant information
	$contactDetails = array();
	$contactDetails['company'] = $params["companyname"];
  $contactDetails['firstname'] = $params["firstname"];
  $contactDetails['surname'] = $params["lastname"];
  $contactDetails['email'] = $params["email"];
	$contactDetails['phone'] = $params["phonenumber"];
  $contactDetails['street'] = preg_replace('/\d+/u', '', $params["address1"]);
	$contactDetails['number'] = preg_replace('/[^0-9,.]/', '', $params["address1"]);
	$contactDetails['zipcode'] = $params["postcode"];
	$contactDetails['city'] = $params["city"];
	$contactDetails['country'] = $params["countrycode"];

    $response = $versio->request('POST', '/contacts', $contactDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

    $contactid = $response['contact_id'];

	//domain

	$sld = $params['sld'];
  $tld = $params['tld'];

	$domainDetails = array();
	$domainDetails['years'] = $params['regperiod'];
	$domainDetails['contact_id'] = $contactid;
	$domainDetails['auto_renew'] = true;

	if(!$params['ns1'] == null)
	{
	$ns[] = array('ns' => $params['ns1'], 'nsip' => '');
	}

	if(!$params['ns2'] == null)
	{
	$ns[] = array('ns' => $params['ns2'], 'nsip' => '');
	}

	if(!$params['ns3'] == null)
	{
	$ns[] = array('ns' => $params['ns3'], 'nsip' => '');
	}

	if(!$params['ns4'] == null)
	{
	$ns[] = array('ns' => $params['ns4'], 'nsip' => '');
	}

	if(!$params['ns5'] == null)
	{
	$ns[] = array('ns' => $params['ns5'], 'nsip' => '');
	}

	$domainDetails['ns'] = $ns;


	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld, $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}


function versio_TransferDomain($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

    // registrant information
	$contactDetails = array();
	$contactDetails['company'] = $params["companyname"];
  $contactDetails['firstname'] = $params["firstname"];
  $contactDetails['surname'] = $params["lastname"];
  $contactDetails['email'] = $params["email"];
	$contactDetails['phone'] = $params["phonenumber"];
  $contactDetails['street'] = preg_replace('/\d+/u', '', $params["address1"]);
	$contactDetails['number'] = preg_replace('/[^0-9,.]/', '', $params["address1"]);
	$contactDetails['zipcode'] = $params["postcode"];
	$contactDetails['city'] = $params["city"];
	$contactDetails['country'] = $params["countrycode"];

    $response = $versio->request('POST', '/contacts', $contactDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

    $contactid = $response['contact_id'];

	//domain

	$sld = $params['sld'];
    $tld = $params['tld'];

	$domainDetails = array();
	$domainDetails['years'] = $params['regperiod'];
	$domainDetails['contact_id'] = $contactid;
	$domainDetails['auto_renew'] = true;
	$domainDetails['auth_code'] = $params['eppcode'];

	if(!$params['ns1'] == null)
	{
	$ns[] = array('ns' => $params['ns1'], 'nsip' => '');
	}

	if(!$params['ns2'] == null)
	{
	$ns[] = array('ns' => $params['ns2'], 'nsip' => '');
	}

	if(!$params['ns3'] == null)
	{
	$ns[] = array('ns' => $params['ns3'], 'nsip' => '');
	}

	if(!$params['ns4'] == null)
	{
	$ns[] = array('ns' => $params['ns4'], 'nsip' => '');
	}

	if(!$params['ns5'] == null)
	{
	$ns[] = array('ns' => $params['ns5'], 'nsip' => '');
	}

	$domainDetails['ns'] = $ns;


	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/transfer', $domainDetails);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}

function versio_RenewDomain($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

    	$versio = new Versio_API();
    	$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$domainDetails = array();
	$domainDetails['years'] = $params['regperiod'];


	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/renew', $domainDetails);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}


function versio_GetNameservers($params)
{
// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];


	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array(
			'dnsmanagement' => $response['domainInfo']['dns_management'],
			'success' => true,
			'ns1' => $response['domainInfo'][ns][0][ns],
			'ns2' => $response['domainInfo'][ns][1][ns],
			'ns3' => $response['domainInfo'][ns][2][ns],
			'ns4' => $response['domainInfo'][ns][3][ns],
			'ns5' => $response['domainInfo'][ns][4][ns]);
		}
}

function versio_SaveNameservers($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
  $tld = $params['tld'];

	$domainDetails = array();
	$ns = array();

	if(!$params['ns1'] == null)
	{
	$ns[] = array('ns' => $params['ns1'], 'nsip' => '');
	}

	if(!$params['ns2'] == null)
	{
	$ns[] = array('ns' => $params['ns2'], 'nsip' => '');
	}

	if(!$params['ns3'] == null)
	{
	$ns[] = array('ns' => $params['ns3'], 'nsip' => '');
	}

	if(!$params['ns4'] == null)
	{
	$ns[] = array('ns' => $params['ns4'], 'nsip' => '');
	}

	if(!$params['ns5'] == null)
	{
	$ns[] = array('ns' => $params['ns5'], 'nsip' => '');
	}

	if(($params['ns1'] == 'nszero1.axc.nl' || $params['ns2'] == 'nszero2.axc.nl') || ($params['ns1'] == 'nszero1.axc.eu' || $params['ns2'] == 'nszero2.axc.eu'))
		{
			$domainDetails['dns_management'] = true;
		}else{
			//$domainDetails['dns_management'] = false;
			$domainDetails['ns'] = $ns;
		}

	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/update', $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}

function versio_GetContactDetails($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
  $tld = $params['tld'];

	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

	$registrant_id = $response['domainInfo']['registrant_id'];

			if(!$registrant_id)
		{
			return array('error' => 'No contact information available');
			die();
		}


	$response = $versio->request('GET', '/contacts/'.$registrant_id);


		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{

		return array(
            'Registrant' => array(
                'First Name' => $response['contactInfo']['firstname'],
                'Last Name' => $response['contactInfo']['surname'],
                'Company Name' => $response['contactInfo']['company'],
                'Email Address' => $response['contactInfo']['email'],
                'Address 1' => $response['contactInfo']['street'].' '.$response['contactInfo']['number'],
                'City' => $response['contactInfo']['city'],
                'Postcode' => $response['contactInfo']['zipcode'],
                'Country' => $response['contactInfo']['country'],
                'Phone Number' => $response['contactInfo']['phone'])   );
		}
}

function versio_SaveContactDetails($params)
{
	// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	// registrant information

	$contactDetails = array();
	$contactDetails['company'] = $params['contactdetails']['Registrant']['Company Name'];
  $contactDetails['firstname'] = $params['contactdetails']['Registrant']['First Name'];
  $contactDetails['surname'] = $params['contactdetails']['Registrant']['Last Name'];
  $contactDetails['email'] = $params['contactdetails']['Registrant']['Email Address'];
	$contactDetails['phone'] = $params['contactdetails']['Registrant']['Phone Number'];
  $contactDetails['street'] = preg_replace('/\d+/u', '', $params['contactdetails']['Registrant']['Address 1']);
	$contactDetails['number'] = preg_replace('/[^0-9,.]/', '', $params['contactdetails']['Registrant']['Address 1']);
	$contactDetails['zipcode'] = $params['contactdetails']['Registrant']['Postcode'];
	$contactDetails['city'] = $params['contactdetails']['Registrant']['City'];
	$contactDetails['country'] = $params['contactdetails']['Registrant']['Country'];

    $response = $versio->request('POST', '/contacts', $contactDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

	$domainDetails = array();
	$domainDetails['registrant_id'] = $response['contact_id'];

	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/update', $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}

function versio_GetRegistrarLock($params)
{
	// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

		return ($response['domainInfo']['lock'] == 'true') ? 'locked' : 'unlocked';
}

function versio_SaveRegistrarLock($params)
{
	// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$domainDetails = array();
	$domainDetails['lock'] = ($params['lockenabled'] == 'locked') ? true : false;

	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/update', $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}
}

function versio_GetDNS($params)
{
// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];


	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld.'?show_dns_records=true');

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{

        $hostRecords = array();

		$records = count($response['domainInfo']['dns_records']);

		$i = 0;

       do {


            $hostRecords[] = array("hostname" => $response['domainInfo']['dns_records'][$i]['name'], // eg. www
                "type" => $response['domainInfo']['dns_records'][$i]['type'], // eg. A
                "address" => $response['domainInfo']['dns_records'][$i]['value'], // eg. 10.0.0.1
                "priority" => $response['domainInfo']['dns_records'][$i]['prio'], // eg. 10 (N/A for non-MX records)
			);

		$i++;

        } while ($i < $records);

		//redirections

				$redirections = count($response['domainInfo']['dns_redirections']);

		$i = 0;

       do {

			if(!$redirections == 0)
			{
            $hostRecords[] = array("hostname" => $response['domainInfo']['dns_redirections'][$i]['from'], // eg. domain.nl
                "type" => 'URL', // eg. A
                "address" => $response['domainInfo']['dns_redirections'][$i]['destination'], // eg. http://www.google.nl
			);
			}

		$i++;

        } while ($i < $redirections);


        return $hostRecords;

		}
}

function versio_SaveDNS($params)
{
    // user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$domainDetails = array();
	$dnsRecords = array();
	$redirect = array();

	foreach($params['dnsrecords'] as $dnsrecord)
	{
		if($dnsrecord['type'] == 'MXE')
		{
		  return array('error' => 'Record type MXE is not supported');
		  die();
		} elseif($dnsrecord['type'] == 'FRAME') {
		  return array('error' => 'Record type FRAME is not supported');
		  die();
		}else{

			if(!$dnsrecord['hostname'] == NULL)
			{

			   if($dnsrecord['priority'] = 'N\/A')
			   {
				  $dnsrecord['priority'] = '0';
			   }

			   if($dnsrecord['type'] == 'URL') {
				$redirect[] = array("from" => $dnsrecord['hostname'], "destination" => $dnsrecord['address']);
			   }else{
				$dnsRecords[] = array('type' => $dnsrecord['type'], 'name' => $dnsrecord['hostname'], 'value' => $dnsrecord['address'], 'prio' => $dnsrecord['priority'], 'ttl' => '3600');
			   }
			}
	}
	}

	$domainDetails['dns_records'] = $dnsRecords;
    $domainDetails['dns_redirections'] = $redirect;

	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/update', $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			return array('success' => true);
		}

}

function versio_IDProtectToggle($params)
{
	return 'Not implemented';
}

function versio_GetEPPCode($params)
{
	// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
  $tld = $params['tld'];

	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld.'?show_epp_code=true');

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
			die();
		}

		if($tld == 'be')
					{
								return array(
                'eppcode' => 'EPP code will be sent by email',
            );

					}else{
							return array(
                'eppcode' => $response['domainInfo']['epp_code'],
            );
					}

}

function versio_ReleaseDomain($params)
{
	$username = $params['Username'];
  $password = $params['Password'];
  $endpoint = $params['Site-version'];
  $testmode = $params['TestMode'];

$versio = new Versio_API();
$versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
  $tld = $params['tld'];

	$domainDetails = array();
	$domainDetails['tag'] = $params['transfertag'];

	$response = $versio->request('POST', '/domains/'.$sld.'.'.$tld.'/update/nominettag', $domainDetails);

	if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
			$status = $response['domainInfo']['status'];

				switch ($status) {
				case "TRANSFERRED_OUT":
					return array('success' => 'success');
					break;
				case "WAITING_FOR_HANDSHAKE":
					return array('success' => 'success');
					break;
				default:
					return array('error' => 'failed');
				}
		}

}

function versio_RequestDelete($params)
{
// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$response = $versio->request('DELETE', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{
	    return array(
            'success' => 'success');
		}

}

function versio_Sync($params)
{
	// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
  $tld = $params['tld'];

	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{

		$status = $response['domainInfo']['status'];
		$dns_management = $response['domainInfo']['dns_management'];

		switch ($status) {
    case "OK":
		return array(
    'dnsmanagement' => $dns_management,
    'expirydate' => $response['domainInfo']['expire-date'],
    'active' => true);
        break;
    case "PENDING":
        return array('active' => false);
        break;
    case "INACTIVE":
        return array('expired' => true);
        break;
	case "PENDING_TRANSFER":
        return array('active' => false);
        break;
    default:
        return array(
         'dnsmanagement' => $dns_management,
         'expirydate' => $response['domainInfo']['expire-date'],
         'active' => true);
}

		}
}

function versio_TransferSync($params)
{
// user defined configuration values
    $username = $params['Username'];
    $password = $params['Password'];
    $endpoint = $params['Site-version'];
    $testmode = $params['TestMode'];

  $versio = new Versio_API();
  $versio->setApi_login($username, $password, $endpoint);

	$versio->setApi_testmodus($testmode);

	//domain
	$sld = $params['sld'];
    $tld = $params['tld'];

	$response = $versio->request('GET', '/domains/'.$sld.'.'.$tld);

		if($response['error'])
		{
			return array('error' => $response['error']['message']);
		}else{

				$status = $response['domainInfo']['status'];

		switch ($status) {
		case "OK":
			return array('completed' => true, 'expirydate' => $response['domainInfo']['expire-date']);
			break;
		case "PENDING":
			return array();
			break;
		case "INACTIVE":
			return array();
			break;
		case "PENDING_TRANSFER":
			return array();
			break;
		default:
			return array();
		}
	}
}
