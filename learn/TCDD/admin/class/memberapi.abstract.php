<?php
abstract class abstractMemberAPI{
	abstract private $api_url = "";
	abstract private $endpoint = "";
	abstract private $key = "";
	abstract private $donothing = false;
	function __construct($wpurl = "", $key = ""){}
	
	abstract function create_user($user_data){}
	
	abstract function get_user($email){}
	
	abstract function skip(){}
	
	abstract function add_to_levels($levels){}
	
	abstract function update_pass($uid, $email, $new_pass){}
	
	abstract function udpate_user(){}
	
	abstract function gen_dashboard_url($email, $rurl){}
	
	abstract function send($parameters){	}
}
?>