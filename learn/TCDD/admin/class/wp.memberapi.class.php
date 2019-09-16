<?php
class WPMemberAPI{
	private $api_url = "";
	private $endpoint = "";
	private $key = "";
	private $donothing = false;
	function __construct($wpurl = "", $key = ""){
		if($wpurl == "") $this->donothing = true;
		if($key == "") $this->donothing = true;
		$this->api_url = trim($wpurl, "/")."/wp-admin/admin-ajax.php";
		$this->key =  $key;
	}
	
	function create_user($user_data){
		$this->endpoint = $this->api_url."?action=wpmea_insert_user";
		$res = $this->send($user_data);
		if($res === 2) return $this->skip();
		return $res;
	}
	
	function get_user($email){
		$this->endpoint = $this->api_url."?action=wpmea_get_user";
		$res = $this->send(array("user_email" =>$email));
		if($res === 2) return $this->skip();
		$user = json_decode($res, true);
		if(@$user["success"] == 1){
			$uid = $user["data"]["ID"];
			$user["data"]["id"] = $uid;
			$user["data"]["success"] = 1;;
			return $user["data"];
		}else{
			return false;
		}
	}
	
	function skip(){
		return array("success" => 2);
	}
	
	function add_to_levels($levels){}
	
	function update_pass($uid, $email, $new_pass){
		$this->endpoint = $this->api_url."?action=wpmea_update_user_pass";
		$res = $this->send(array("ID" => $uid, "user_email" => $email, "user_pass" => $new_pass));
		if($res === 2) return $this->skip();
		return $res;
	}
	
	function udpate_user(){}
	
	function gen_dashboard_url($email, $rurl, $wpmeakey){
		return $this->api_url."?action=wpmea_dashboard&oclh=".get_user_hash($email, $wpmeakey )."&e=".$email."&r=".urlencode($rurl);
	}
	
	function send($parameters){
		if($this->donothing == true) return 2;
		$parameters["oclh"] = get_user_hash($parameters["user_email"], $this->key );
		// $context	= stream_context_create(
			// array('http' =>
				// array(
					// 'method'	=> 'POST',
					// 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					// 'content' => http_build_query($parameters)
				// )
			// )
		// );
		// $res = filex_get_contents($this->endpoint, false, $context);
		$res = curl_get_contents($this->endpoint, "POST", $parameters);
		return $res;
	}
}
?>