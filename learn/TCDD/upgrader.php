<?php
define("THANKSTHRIVE", true);
require_once("thrive.class.php");
class Upgrader{
	private $upgradeURL = "";
	private $thrive;
	function __construct(){
		$this->thrive = new THRIVE;
		$this->upgradeURL = $this->thrive->composeRequest("upgradeInstaller");
		// die($this->upgradeURL);
		$this->getUpgrade();
	}
	
	private function getUpgrade(){
		$settings = file_get_contents("settings.php");
		$m = preg_match("/#\bversion\b.*/", $settings, $match);
		if($m) $v = explode(":", @$match[0]);
		$version = trim(@$v[1]);
		$context	= stream_context_create(
			array('http' =>
				      array(
					      'method'	=> 'POST',
					      'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      'content' => http_build_query(array("version" => $version))
				      )
			)
		);
		// $ret = filex_get_contents($this->upgradeURL, false, $context);
		$ret = curl_get_contents($this->upgradeURL, "POST", array("version" => $version));
		$s = file_put_contents("customthrivecart.zip", $ret, LOCK_EX);
		if($s === false) die("Couldn't download Upgrade.");
		$zip = new ZipArchive;
		$res = $zip->open('customthrivecart.zip');
		if ($res === TRUE) {
		  $res = $zip->extractTo('./');
		  $zip->close();
		  echo "Upgrade Successful...";
		  if(file_exists("downloads/custom_thrive_success_pages.zip"))unlink("downloads/custom_thrive_success_pages.zip");
		} else {
			die("Fail to Upgrade.");
		}
	}
}

$upgader = new Upgrader;


function curl_get_contents($url, $method = "GET", $fields = array()){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	if($method == "POST"){
		curl_setopt($ch, CURLOPT_URL, $url);
		$fields_string = http_build_query($fields);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	}else{
		$url = append_query_arg($url, $fields);
		curl_setopt($ch, CURLOPT_URL, $url);
	}

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function append_query_arg($url, $args = array()){
	if(!$args || count($args) == 0) return $url;
	if(strpos("?",$url)){
		$url .= "&".http_build_query($args); 
	}else{
		$url .= "?".http_build_query($args); 
	}
	return $url;
}

?>















