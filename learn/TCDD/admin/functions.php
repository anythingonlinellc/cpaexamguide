<?php 
if(!defined("THANKSTHRIVE"))die(); 
function tooltip($tooltip, $color = "", $echo = true){
	$t = "<span class=\"tooltip $color\" title=\"$tooltip\">i</span>";
	if($echo === true){
		echo $t;
	}else return $t;
}

function vidtutorial($video, $tooltip, $echo = true){
	$v = "<div class=\"vidtutorial\" data-vid=\"".$video["vidurl"]."\" data-title=\"".$video["title"]."\" data-desc=\"".$video["description"]."\" title=\"".$tooltip."\"><span class=\"box\"></span><span class=\"lense\" ></span></div>";
	if($echo === true){
		echo $v;
	}else return $v;
}

function oneClick_memberium($wpurl, $id, $email, $key, $rurl){
	$url = trim($wpurl,"/");
	return $url."/?memb_autologin=yes&auth_key=$key&id=$id&email=$email&redir=".urlencode($rurl);
}

function oneClick_ACM360($wpurl, $email, $key, $rurl){
	return trim($wpurl,"/")."/?mbr_autologin=$key&email=$email&redir=".urlencode($rurl);
}

function getPerstag($title, $replace=array(), $delimiter='-') {
	setlocale(LC_ALL, 'en_US.UTF8');
	if( !empty($replace) ) {
		$title = str_replace((array)$replace, ' ', $title);
	}

	$clean = preg_replace(array('/Ä/', '/Ö/', '/Ü/', '/ä/', '/ö/', '/ü/'), array('Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue'), $title);
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

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

function get_global_settings(){
	$globalsettings = array();
	if(file_exists(__DIR__ ."/globals.json")) $globalsettings = json_decode(file_get_contents(__DIR__ ."/globals.json"), true);
	return $globalsettings;
}

function log_this($file, $data){
	if(defined("LOGPROCESS"))
	file_put_contents($file, "\n\r=======\n\r".print_r($data, true)."\n\r======", FILE_APPEND | LOCK_EX);
}

function getVersion(){
	$settings = file_get_contents("../settings.php");
	$m = preg_match("/#\bversion\b.*/", $settings, $match);
	if($m) $v = explode(":", @$match[0]); 
	$version = trim(@$v[1]);
	return $version;
}
?>