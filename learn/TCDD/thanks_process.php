<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
define("THANKSTHRIVE", true);
require_once("settings.php");
require_once("admin/functions.php");
require_once("thrive.class.php");

$thrive = new THRIVE;
if($thrive->checkHash() == false) die("Denied!");

$base_product = (isset($_GET["thrivecart"]["product_id"])?$_GET["thrivecart"]["product_id"]:$_GET["thrivecart"]["base_product"]);
$order_id = @$_GET["thrivecart"]["order_id"];
$affiliate_id = @$_GET["affiliate_id"];

$site = $thrive->getSite_by_product($base_product);
if(!$site) die();
$site = json_decode($site["details"], true);

$res = $thrive->get_order($order_id);

$details = $tc = json_decode(stripslashes($res["details"]), true);
if(!$details){
	$details = $tc = json_decode($res["details"], true);
}

/* Lets check if data is processed already */
$is_processed = ($res["processed"] == 0?false:true);
// $is_processed = false;
if($is_processed === false){
	die("wait");
	// require_once("webhook_process.php");
}

$missingFields = array();
$password_fid = false;
foreach((array)@$site["AC"]["CF"] as $CF){
	if($CF["thrive"] == "password"){
		$password_fid = $CF["field"]["id"];
	}elseif($CF["thrive"] == "affiliate_id"){
		$missingFields[] = array("id" => $CF["field"]["id"], "value" => @$_GET["affiliate_id"]);
	}elseif($CF["thrive"] == "referrer" || $CF["thrive"] == "ref"){
		$missingFields[] = array("id" => $CF["field"]["id"], "value" => @$_GET["ref"]);
	}elseif($CF["thrive"] == "product_category"){
		$missingFields[] = array("id" => $CF["field"]["id"], "value" => @$_GET["cat_id"]);
	}
}

$product_master = array();
foreach($site["Products"] as $p){
	$product_master[$p["product_id"].strtoupper(@$p["is_bump"])] =  $p;
}

$doresetpass = (isset($_GET["resetpass"]) && $_GET["resetpass"] == 1 && isset($_GET["mid"]) && $_GET["mid"] != "");

require_once("thanks_helper.php");
// require_once("admin/class/memberapi.abstract.php");
require_once("admin/class/wlm.memberapi.class.php");
require_once("admin/class/wp.memberapi.class.php");

$thrivecart = $_GET['thrivecart'];

if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
	require_once("admin/models/ac.class.php");
	$ac = new AC($site["APIs"]["ActiveCampaign"]["url"], $site["APIs"]["ActiveCampaign"]["key"]);
}

$wp_home_url = $site["APIs"]["Membership"]["url"];

if(@$site["APIs"]["Membership"]["plugin"] == "wishlist"){
	$api = new WishListMemberAPI($wp_home_url, $site["APIs"]["Membership"]["WishList_API_Key"]);
}else{
	$api = new WPMemberAPI($wp_home_url, $site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]);
}

$affiliate_id = @$_GET["thrivecart"]["affiliate_id"];
$order = $details["order"];
$purchase_map = $details["purchase_map"];

/* get email */
$url_email = strtolower($details["customer"]["email"]);
if(@$site["AC"]["UseStoredEmail"]){
	$email = (isset($_COOKIE['stored-email']) && $_COOKIE['stored-email'] != "" ?$_COOKIE['stored-email']:$url_email);
}else{
	$email = $url_email;
}

if($doresetpass){
	/* Generate password */
	$md5 = md5($email.rand(100,1000000));
	$user_pass = str_shuffle(substr($md5 , 0, 5). strtoupper(substr($md5 , 5, 4)));
	$response = $api->update_pass($_GET["mid"], $email, $user_pass); /* Update pass in WP */
	if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true" && $password_fid !=  false){
		$ac->update_pass($email, $user_pass, $password_fid); /* Updates custom field for password in AC */
	}
	echo $user_pass;
	die();
}

$c = $ac->get_contact($email);
$contact = json_decode($c, true);

$wpmember = $api->get_user($email);
// if($wpmember == false){
	// die("{}");
// }else{
	if(@$res["hash"] == ""){
		
	/* Lets Fill-in Missing Dynamic Custom Fields */
		foreach($details["purchases"] as $purchase){
			$forder_source = "Source - ".htmlspecialchars_decode(htmlspecialchars_decode($purchase));
			$missingFields[] = array("field" => $forder_source, "value" => @$_GET["ref"]);
		}
		
		if(count($missingFields)>0){
			if($contact["result_code"] != 0){
				foreach($contact["fields"] as $field){
					if(count($missingFields) <= 0)break;
					foreach($missingFields as $k => $missingField){
						if(isset($missingField["id"])){
							$parameters["field[".$missingField["id"].",0]"] = $missingField["value"];
							unset($missingFields[$k]);
							break;
						}elseif($missingField["field"] == $field["title"]){
							$parameters["field[".$field["id"].",0]"] = $missingField["value"];
							unset($missingFields[$k]);
						}
					}
				}
				$ac->update_contact($parameters, @$contact["id"]);
			}
		}
	/* END Lets Create Dynamic Custom Fields */
		$thrive->fillMissing($details["order_id"], $_GET);
	}
	
	unset($wpmember["user_pass"]);
	$details["member"] = $wpmember;
	if($password_fid != false && @$contact["fields"][$password_fid]["val"] != "null" && @$contact["fields"][$password_fid]["val"] != "")$details["member"]["user_pwd"] = @$contact["fields"][$password_fid]["val"];
		
	$details["order"]["total"] = floatval($details["order"]["total"])/100;
	$custom_labels = array();
	if(@$site["Product_Type_Labels"]){
		$custom_labels = $site["Product_Type_Labels"]["labels"];
		$details["show_colum_type"] = $site["Product_Type_Labels"]["show"];
	}else{
		$details["show_colum_type"] = true;
	}
	foreach($details["order"]["charges"] as $i => $o){
		$details["order"]["charges"][$i]["stype"] = get_type_label($o["reference"], $i, $purchase_map, $custom_labels);
		
		// $pid = $o["reference"];
		$aurl = "";
		
		$pmap = $details["purchase_map"][$i];
		$pm = explode("-", $pmap);
		$pid = @$pm[1];
		if(strtolower(@$pm[0]) == "bump") $pid .= "BUMP";
		// print_r($site);
		if(@$product_master[$pid]["dashboard_url"]){
			if($site["APIs"]["Membership"]["plugin"] == "memberium"){
				$aurl = oneClick_memberium(@$site["APIs"]["Membership"]["url"], @$contact["id"], $email, @$site["APIs"]["Membership"]["Memberium_API_Key"], @$product_master[$pid]["dashboard_url"]);
			}elseif($site["APIs"]["Membership"]["plugin"] == "activemember360"){
				$aurl = oneClick_ACM360(@$site["APIs"]["Membership"]["url"], $email, @$site["APIs"]["Membership"]["ActiveMember360_API_Key"], @$product_master[$pid]["dashboard_url"]);
			}else{
				if(@$site["APIs"]["Membership"]["Enable_One_Click_Login"] === "true"){
					$aurl = $api->gen_dashboard_url($email, @$product_master[$pid]["dashboard_url"], @$site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]);
				}else{
					$aurl = @$product_master[$pid]["dashboard_url"];
				}
			}			
		}
		if(!@$details["member"]["loginurl"]){
			if(@$site["APIs"]["Membership"]["Enable_One_Click_Login"] === "true"){
				$olurl = $api->gen_dashboard_url($email, @$site["APIs"]["Membership"]["loginurl"], @$site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]);
			}else{
				$olurl = @$site["APIs"]["Membership"]["loginurl"];
			}
			$details["member"]["loginurl"] = (!@$site["APIs"]["Membership"]["loginurl"] ?$aurl:$olurl);
		}
		$details["order"]["charges"][$i]["url"] = $aurl;
		$details["order"]["charges"][$i]["amount"] = floatval($details["order"]["charges"][$i]["amount"])/100;
		$details["order"]["charges"][$i]["name"] = htmlspecialchars_decode ($details["order"]["charges"][$i]["name"]);
	}
	
	if(!@$details["customer"]["address"]) @$details["customer"]["address"] = array("line1"=>"","state"=>"","zip"=>"","city"=>"","country"=>"");
	elseif(!@$details["customer"]["address"]["line1"]) $details["customer"]["address"]["line1"] = "";
	elseif(!@$details["customer"]["address"]["state"]) $details["customer"]["address"]["state"] = "";
	elseif(!@$details["customer"]["address"]["zip"]) $details["customer"]["address"]["zip"] = "";
	elseif(!@$details["customer"]["address"]["city"]) $details["customer"]["address"]["city"] = "";
	elseif(!@$details["customer"]["address"]["country"]) $details["customer"]["address"]["country"] = "";
	
	unset($details["thrivecart_secret"]);
	unset($details["thrivecart_account"]);
	$details["Trackers"] = $site["Trackers"];
	
	echo json_encode($details);
// }
?>