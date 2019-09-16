<?php
if(!defined("THANKSTHRIVE"))die();
require_once("thrive.class.php");
class Upgrader{
	private $upgradeURL = "";
	private $thrive;
	function __construct(){
		$this->thrive = new THRIVE;
		$this->upgradeURL = $this->thrive->composeRequest("upgradeInstaller");
		$this->getUpgrade();
	}
	
	private function getUpgrade(){
		$settings = file_get_contents("../settings.php");
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
		$s = file_put_contents("../customthrivecart.zip", $ret, LOCK_EX);
		if($s === false) die("Couldn't download Upgrade.");
		$zip = new ZipArchive;
		$res = $zip->open('../customthrivecart.zip');
		if ($res === TRUE) {
		  $res = $zip->extractTo('../');
		  $zip->close();
		} else {
			die("Fail to Upgrade.");
		}
	}
}

$upgader = new Upgrader;
?>















