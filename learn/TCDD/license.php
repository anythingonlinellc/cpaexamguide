<?php
	if(isset($_GET["request"])){
		// $context	= stream_context_create(
			// array('http' =>
				// array(
					// 'method'	=> 'GET',
					// 'header'	=> 'Content-type: application/x-www-form-urlencoded',
				// )
			// )
		// );
		$url = "http://www.customthrivecartpages.com/license/?".http_build_query($_GET);
		// var_dump(filex_get_contents($url, false, $context));
		var_dump(curl_get_contents($url));
	}else{
		print_r($_SERVER);
	}
?>