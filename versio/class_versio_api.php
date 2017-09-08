<?php
class Versio_API {
	
	function setApi_login($username, $password)
	{
		$this->loginusername = $username;
		$this->loginpassword = $password;
	}
	
	function setApi_debug()
	{
		$this->debug = true;
	}

	function setApi_testmodus($testmodus)
	{
		if ($testmodus == 'true') {
			$this->endpoint = 'https://www.versio.nl/testapi/v1';
		}else{
			$this->endpoint = 'https://www.versio.nl/api/v1';
		} 
	}
	
		function setApi_output($outputresult)
	{
			$this->output = $outputresult;
	}

	function request($requesttype, $request, $data=array())
	{
		$url = $this->endpoint.$request;
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_USERPWD, $this->loginusername . ":" . $this->loginpassword);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requesttype);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		$debugdata = array('requesttype' => $requesttype, 'url' => $url, 'postdata' => $data, 'result' => $result, 'httpcode' => $httpcode);
		
		logModuleCall('versio', 'API CALL', 'Request', $debugdata);
		
		if($this->debug) {
			var_dump($debugdata);
		}
		
		$codes = array('200', '201', '202', '400', '401', '404');
		
		if($this->output == 'json') {
			$result = json_decode($result, 1);
			$result['httpcode'] = $httpcode;
			
			if (in_array($httpcode, $codes)) {
				return json_encode($result);
			}else{
				$error = array();
				$error['error']['message']  = 'Request failed';
				return json_encode($error);
			}
			
		} else {
			
			$result = json_decode($result, 1);
			$result['httpcode'] = $httpcode;
			
			if (in_array($httpcode, $codes)) {
				return $result;
			}else{
				$error = array();
				$error['error']['message']  = 'Request failed';
				return $error;
			}
		}
	}
}