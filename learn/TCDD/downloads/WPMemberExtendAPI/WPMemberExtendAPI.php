<?php
/*
Plugin Name: WP Member Extend API
Plugin URI: http://www.acpowertools.com
Description: Registers Users and provides One Click Login functionality
Version: 1.0
Author: Big Jason Henderson
Author URI: http://www.acpowertools.com  						(
C) 2015 Big Jason Henderson. All Rights Reserved.
For personal use only. May not be shared with anyone online or offline.
If you have developer rights, you may only implement for your clients.
Get ActiveCampaign Coaching and Power Tools at:
http://www.AC_Video_Tracking.com
*/

add_action( 'wp_ajax_nopriv_ocl_pk', 'ocl_pluginKeys');
add_action( 'wp_ajax_ocl_pk', 'ocl_pluginKeys');

add_action( 'wp_ajax_nopriv_wpmea_dashboard', 'wpmea_product_dashboard');
add_action( 'wp_ajax_wpmea_dashboard', 'wpmea_product_dashboard');

add_action('wp_ajax_nopriv_wpmea_insert_user','wpmea_insert_user');
add_action('wp_ajax_wpmea_insert_user','wpmea_insert_user');

add_action('wp_ajax_nopriv_wpmea_get_user','wpmea_get_the_user');
add_action('wp_ajax_wpmea_get_user','wpmea_get_the_user');

add_action('wp_ajax_nopriv_wpmea_update_user_pass','wpmea_update_user_pass');
add_action('wp_ajax_wpmea_update_user_pass','wpmea_update_user_pass');

add_action('wp_ajax_nopriv_wpmea_getPosts','wpmea_getPosts');
add_action('wp_ajax_wpmea_getPosts','wpmea_getPosts');

add_action('wp_ajax_nopriv_wpmea_getPosts2','wpmea_getPosts2');
add_action('wp_ajax_wpmea_getPosts2','wpmea_getPosts2');

function ocl_pluginKeys(){
	global $wpdb;
	$res = check_site_request($_POST["key"]);
	if($res == false) die(json_encode(array("success" => 0)));
	
	$OptionsTable = $wpdb->prefix . 'wlm_' . 'options';
	$row = $wpdb->get_row($wpdb->prepare("SELECT `option_value` FROM `{$OptionsTable}` WHERE `option_name`='%s'", "WLMAPIKey"));
	
	$WLMAPIKey = @$row->option_value;
	$memberium = get_option("memberium");
	$keys = array(
		"wishlist" => $WLMAPIKey,
		"acm360" => ACM360_hack(),
		"memberium" => @$memberium["autologin_authkeys"],
	);
	echo json_encode($keys);
	die();
}

function ACM360_hack(){
	global $wpdb;
	$qry = "SELECT * FROM `".$wpdb->prefix."mbrOptions` WHERE `option_name` = 'site_options'";
	$res = $wpdb->get_results($qry, ARRAY_A);
	if(count($res) > 0){
		$option = unserialize($res[0]["option_value"]);
		$security = unserialize($option["security_codes"]);
		if(count($security) > 0) return $security[0];
		else return "";
	}else return "";
}

function wpmea_getPosts2(){
	header('Access-Control-Allow-Origin: *');
	 $posts = query_posts( array(
        'post_type'  => array('post', 'page'),
        'posts_per_page' => -1
    ) );
	
	foreach($posts as $i => $post){
		$post->url = get_permalink($post->ID);
		$posts[$i] = $post;
	}
	
	echo json_encode($posts);
	die();
}
function wpmea_getPosts(){
	header('Access-Control-Allow-Origin: *');
	global $wpdb;
	$table = $wpdb->prefix."posts";
	$sql = "SELECT `ID`, `post_title`, `post_type`, `guid` FROM `".$table."` WHERE (`post_type`='post' OR `post_type`='page') AND `post_status`='publish' AND `post_parent`=0";
	$posts = $wpdb->get_results($sql, ARRAY_A );
	foreach($posts as $i => $post){
		$post["guid"] = get_permalink($post["ID"]);
		$posts[$i] = $post;
	}
	echo json_encode($posts);
	die();
}

function wpmea_product_dashboard(){
	if(wpmea_check_request($_GET["e"], $_GET["oclh"]) == false) die(json_encode(array("success" => 0)));

	$user = get_user_by('email', $_GET["e"] );

	// Redirect URL //
	if ( !is_wp_error( $user ) ){
		// wp_logout();
		wp_clear_auth_cookie();
		wp_set_current_user ( $user->data->ID );
		wp_set_auth_cookie  ( $user->data->ID );

		$redirect_to = $_GET["r"];
		if(!$redirect_to || $redirect_to == "") $redirect_to = get_home_url();
		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function wpmea_get_user_hash($email){
	$secret = get_option("ocl_secret_key");
	return md5(base64_encode(substr($email,0,1).trim($email).substr($email,0,2)).$secret);
}

function wpmea_update_user_pass(){
	extract($_POST);
	if(wpmea_check_request() == false) die(json_encode(array("success" => 0)));
	$user = get_user_by("email",$user_email);
	$ID = intval($ID);
	if($ID > 0 && $user->ID == $ID){
		$user_id = wp_update_user(array("user_pass"=>$user_pass, "ID" => $ID));
		if ( ! is_wp_error( $user_id ) ) {
			$res = array("success" => 1);
		}else{
			$res = array("success" => 0);
		}
	}else{
		$res = array("success" => 0);
	}
	echo json_encode($res);
	die();
}

function wpmea_insert_user(){
	extract($_POST);
	if(wpmea_check_request() == false) die(json_encode(array("success" => 0)));
	$userdata = array(
		'user_login'  =>  @$user_login,
		'user_email'  =>  @$user_email,
		'first_name'  =>  @$first_name,
		'last_name'   =>  @$last_name,
		'display_name'=>  @$first_name." ".@$last_name,
		'nickname'	  =>  @$first_name,
		'user_pass'   =>  @$user_pass,
	);
	
	if (email_exists($user_email) == false ) {
		$user_id = wp_insert_user( $userdata ) ;
		//On success
		if ( ! is_wp_error( $user_id ) ) {
			$u = json_encode(wpmea_get_user($user_email));
			$res = json_decode($u, true);
			$res["success"] = 1;
		}else{
			$res = array("success" => 0);
		}
	}else{
		$res = array("success" => 0);
	}
	echo json_encode($res);
	die();
}

function wpmea_get_the_user(){
	if(wpmea_check_request() == false) die(json_encode(array("success" => 0)));
	$user = wpmea_get_user($_POST["user_email"]);
	if($user){
		$u = json_encode($user);
		$u = json_decode($u, true);
		$u["success"] = 1;
		echo json_encode($u);
	}else{
		echo json_encode(array("success" => 0));
	}
	die();
}

function wpmea_get_user($email){
	$user = get_user_by_email($email);
	return $user;
}

function wpmea_check_request($email="",$oclh=""){
	if($email == "") $email = $_POST["user_email"];
	if($oclh == "") $oclh = $_POST["oclh"];
	$uh = wpmea_get_user_hash($email);
	if($oclh != "" && $oclh == $uh) return true;
	else return false;
}
function check_site_request($rkey){
	if(!$rkey) return false;
	$key = get_option("ocl_secret_key");
	if($key == $rkey) return true;
	else return false;
}

register_activation_hook(__FILE__, 'ocl_activation_hook');
function ocl_activation_hook(){
	$key = get_option("ocl_secret_key");
	if(!$key){
		update_option("ocl_secret_key", md5(rand(100,1000000)*rand(10,100)));
	}
}

add_action('admin_menu', 'ocl_admin_menu');
function ocl_admin_menu(){
	add_menu_page('WP Member Extend', 'WP Member Extend', 'manage_options', "wpmea", 'wpmea_dashboard');
}

function wpmea_dashboard(){
	$key = get_option("ocl_secret_key");
	echo '<h1>WP Member Extend API</h1>';
	echo '<p><i>Registers Users and provides One Click Login functionality.</i></p>';
	echo "<p>Secret Key : <input type=\"text\" id=\"wpmea_secret_key\" value=\"".$key."\" style=\"width:100%;max-width:250px;background-color: #ffffff;\"readOnly/><button id=\"wpmea_copy_key\" class=\"btn btn-default btn-xs\">Copy to Clipboard</button></p>";
	echo "<p><button id=\"wpmea_new_key\" class=\"btn btn-success\">Generate New Key</button></p>";
}

add_action('admin_enqueue_scripts', 'wpmea_enqueue_scripts');
function wpmea_enqueue_scripts(){
	if($_GET["page"] == "wpmea"){
		wp_enqueue_script('wpmea_admin_script', admin_url( 'admin-ajax.php' )."?action=wpmea_js", array('jquery'));
	}
}
add_action('wp_ajax_wpmea_js', 'wpmea_js');
function wpmea_js(){
	header('Content-Type: application/javascript'); 
?>
jQuery(document).ready(function($){
	$("body").on("click", "#wpmea_new_key", function(){
		if(confirm("All apps using this WP Member Extend API will no longer work if you generate a new Key.\n\rBe sure to update all your apps to the new Key after generating a new one.\n\rOnly genrate a new Key if you are sure of what you are doing!\n\rContinue to generate new Key?")){
			$("#wpmea_secret_key").val("...");
			$.post(ajaxurl,{action:"wpmea_new_key"},function(r){
				var res = JSON.parse(r);
				if(res.hasOwnProperty("key")){
					$("#wpmea_secret_key").val(res.key);
					var input =  document.querySelector("#wpmea_secret_key");
					input.select();
				}
			});
		}
	});
	$("body").on("click", "#wpmea_copy_key", function(){
		wpmea_copy_text("wpmea_secret_key");
	});
	function wpmea_copy_text(id){
		var copyTextarea =  document.querySelector("#"+id);
		copyTextarea.select();

		try {
			var successful = document.execCommand("copy");
			if(successful){
				alert("Copied to Clipboard");
			}
		} catch (err) {
			console.log("Oops, unable to copy");
		}
	}
});
<?php
}

add_action('wp_ajax_wpmea_new_key', 'wpmea_new_key');
function wpmea_new_key(){
	$key = md5(rand(100,1000000)*rand(10,100));
	update_option("ocl_secret_key", $key);
	$res = array("key" => $key);
	die(json_encode($res));
}
?>