<?php
require_once('wlmapiclass.php');
class WishListMemberAPI extends wlmapiclass{
	var $wpurl = "";
	var $key = "";
	function __construct($wpurl = "", $key = ""){
		if($wpurl == "") die("Please provide WP URL");
		if($key == "") die("Please provide Wishlist Key");
		$this->wpurl = trim($wpurl, "/");
		parent::__construct($this->wpurl, $key);
		$this->return_format = 'json';
		$this->key = $key;
	}
	
	function create_user($user_data){
		$response = $this->post("/members" , $user_data);
		return $response;
	}
	
	function get_user($email){
		$r = $this->get("/members");
		$members = json_decode($r, true);
		foreach($members["members"]["member"] as $member){
			if(strtolower($member["user_email"]) == $email){
				$wpmember = $member;
				$wpmember["success"] = 1;
				break;
			}
		}
		if($wpmember["success"] == 1){
			return $wpmember;
		}else{
			$wpmember["success"] = 0;
			return $wpmember;
		}
	}
	
	function add_to_levels($levels, $mid){
		foreach($levels as $level){ // Add member to each product level
			$resource = "/levels/{$level}/members";
			$this->post($resource, array("Users" => $mid));
		}
	}
	
	function update_pass($uid, $email, $new_pass){
		$data = array("user_pass" => $new_pass);
		$response = $this->put("/members/".$uid , $data);
	}
	
	function udpate_user(){}
	
	function gen_dashboard_url($email, $rurl, $wpmeakey){
		$api_url = trim($this->wpurl, "/")."/wp-admin/admin-ajax.php";
		return $api_url."?action=wpmea_dashboard&oclh=".get_user_hash($email, $wpmeakey )."&e=".$email."&r=".urlencode($rurl);
	}
	
	function udpate_user_pass(){}
	
	function getLevels(){
		$response = $this->get("/levels");
		if($response === false) return array("success" => 0, "error" => $this->auth_error);
		$levels = json_decode($response, true);
		if(@$levels["success"] == 1) return $levels["levels"]["level"];
		else return array();
	}
}
?>