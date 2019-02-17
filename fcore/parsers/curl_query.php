<?php

include_once( dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'config.php');



function curl_query($url, $referer = 'https://google.com', $type="GET", $cookie_file=NULL, $outputFile=NULL, $postFields = [] ){
	$ch = curl_init();
	//$headers   = array();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	
	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		//"Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0" );
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	curl_setopt($ch, CURLOPT_TIMEOUT, CURL_MAX_TIMEOUT);
	
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);	
	
	if ($type == "POST"){
		$data_string = "";
		
		foreach($postFields as $key=>$value){ /// YOU HAVE TO DO THIS
			$data_string .= '&' . $key.'='.urlencode($value);  /// AND THIS
		}
		
		//$post_data = json_encode($postFields);
		//$post_data = $postFields;
		$fields = mb_substr($data_string, 1, strlen($data_string ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		
		
		//return;
		
				
		$headers = [
			'Host: b2b.ad.ua',
			//'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0',
			'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3',
			'Accept-Encoding: gzip, deflate',
			'Content-Type: application/x-www-form-urlencoded',
			//'Accept-Encoding: gzip, deflate',
			'Content-Length: ' . strlen( $fields),
			'X-Compress:	1',
			//'X-Requested-With: XMLHttpRequest',
			'Connection: keep-alive',
			'Upgrade-Insecure-Requests:	1'
			//'Cookie: ' . $cookie
		];

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

//return;			
		
	}
	
		
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);	

	
	
	
	$data = curl_exec($ch);
	
	
	$err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
	
	// save file if specified
	if ($outputFile){
		file_put_contents($outputFile, $data);
	}
	
	curl_close($ch);
	return $data;
	
	
}







function curl_query2($url, $referer = 'https://google.com', $type="GET", $cookie_file=NULL, $outputFile=NULL, $postFields = []){
	$ch = curl_init();
	$headers   = array();
	
	
	//$cookie  = '_ga=GA1.2.1181835893.1499933245;_gid=GA1.2.801499777.1499933245;sid=3860340|bra2kmswc;notifies=;_gat=1';

	//$headers[] = 'Cookie: ' . $cookie;
	
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	
	curl_setopt($ch, CURLOPT_TIMEOUT, CURL_MAX_TIMEOUT);
	
	


$post = [	
'act' => 'backjobmainautentication',
'psw' =>	'antonec1',
'lgn' =>	'123456789'
	];		
	
	
	if ($type == "POST"){
	
		$data_string = "";

		foreach($postFields as $key=>$value){ /// YOU HAVE TO DO THIS
			$data_string .= $key.'='.urlencode($value).'&';  /// AND THIS
		}
		
		
		
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		
		
		$headers = [
		//	'Host: b2b.ad.ua',
			//'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0',
		//	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		//	'Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3',
			//'Accept-Encoding: gzip, deflate',
		//	'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			//'Accept-Encoding: gzip, deflate',
		//	'Content-Length: ' . (strlen( $data_string ) ),
			//'X-Compress:	1',
			//'X-Requested-With: XMLHttpRequest',
		//	'Connection: keep-alive',
			//'Upgrade-Insecure-Requests:	1'
			//'Cookie: ' . $cookie
			
			"Host: b2b.ad.ua",
			//"User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0",	
			//"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			//"Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3",
			//"Accept-Encoding: gzip, deflate",
			//"Content-Type: application/x-www-form-urlencoded",
			"Content-Length: 193",
			//"Referer: http://b2b.ad.ua/Account/Login?ReturnUrl=%2F",
			//"Connection: keep-alive",
			//"Upgrade-Insecure-Requests:	1"			
				
			
		];

		
		
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

	}
	
	$data = curl_exec($ch);
	
	$err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );

	
	

	curl_close($ch);
	return $data;
}






function curl_query_ad($url, $referer = 'https://google.com', $type="GET", $cookie_file=NULL, $outputFile=NULL, $postFields = [] ){
	$ch = curl_init();
	$headers   = array();
	
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);	
	
	curl_setopt($ch, CURLOPT_TIMEOUT, CURL_MAX_TIMEOUT);
	
	
	
	if ($type == "POST"){
		// format post fields
		$data_string = "";
		$pfL = count( $postFields );
		$counter = 0;
		foreach($postFields as $key=>$value){ 
			$counter += 1;
			if ($counter < $pfL){
				$data_string .= $key.'='.urlencode($value).'&'; 
			}
			else{
				$data_string .= $key.'='.urlencode($value); 
			}
		}
		
		// set postfields
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				
		// set headers
		$headers = [
			"Host: b2b.ad.ua",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3",
			"Content-Type: application/x-www-form-urlencoded",
			"Connection: keep-alive"		
		];

		
		
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

	}
	
	$data = curl_exec($ch);
	

	curl_close($ch);
	return $data;
}






function curl_query_zp($url, $referer = 'https://google.com', $type="GET", $cookie_file=NULL, $outputFile=NULL, $postFields = [] ){
	$ch = curl_init();
	$headers   = array();
	
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);	
	
	curl_setopt($ch, CURLOPT_TIMEOUT, CURL_MAX_TIMEOUT);
	
	
	
	if ($type == "POST"){
		// format post fields
		$data_string = "";
		$pfL = count( $postFields );
		$counter = 0;
		foreach($postFields as $key=>$value){ 
			$counter += 1;
			if ($counter < $pfL){
				$data_string .= $key.'='.urlencode($value).'&'; 
			}
			else{
				$data_string .= $key.'='.urlencode($value); 
			}
		}
		
		// set postfields
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			

	}
	
	$data = curl_exec($ch);
	

	curl_close($ch);
	return $data;
}




































function curl_query3($url, $referer = 'https://google.com', $type="GET", $cookie_file=NULL, $outputFile=NULL, $postFields = []){
	$ch = curl_init();
	$headers   = array();
	
	
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERAGENT,
		"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);	
	
	
	
	//if ($type == "POST"){
	
		$data_string = "";
		$pfL = count( $postFields );
		$counter = 0;
		foreach($postFields as $key=>$value){ /// YOU HAVE TO DO THIS
			$counter += 1;
			if ($counter < $pfL){
				$data_string .= $key.'='.urlencode($value).'&';  /// AND THIS
			}
			else{
				$data_string .= $key.'='.urlencode($value); 
			}
		}
		
		
		
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		
		
		//return;
		
		
		$headers = [
		//	'Host: b2b.ad.ua',
			//'User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0',
		//	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		//	'Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3',
			//'Accept-Encoding: gzip, deflate',
		//	'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			//'Accept-Encoding: gzip, deflate',
		//	'Content-Length: ' . (strlen( $data_string ) ),
			//'X-Compress:	1',
			//'X-Requested-With: XMLHttpRequest',
		//	'Connection: keep-alive',
			//'Upgrade-Insecure-Requests:	1'
			//'Cookie: ' . $cookie
			
			"Host: b2b.ad.ua",
			//"User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:55.0) Gecko/20100101 Firefox/55.0",	
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Language: uk,ru;q=0.8,en-US;q=0.5,en;q=0.3",
			//"Accept-Encoding: gzip, deflate",
			"Content-Type: application/x-www-form-urlencoded",
			//'Content-Length: ' . (strlen( $data_string ) ),
			//"Referer: http://b2b.ad.ua/Account/Login?ReturnUrl=%2F",
			//"Connection: keep-alive",
			//"Upgrade-Insecure-Requests:	1"			
				
			
		];

		
		
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	

	//}
	
	$data = curl_exec($ch);
	
	//$err     = curl_errno( $ch );
    //$errmsg  = curl_error( $ch );
	
	

	curl_close($ch);
	return $data;
}


















?>

