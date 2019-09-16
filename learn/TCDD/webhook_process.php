<?php
if(!defined("THANKSTHRIVE"))die();
// define("LOGPROCESS", true);
require_once("admin/functions.php");
require_once("thrive.class.php");

// global $details, $email, $site, $ac, $api, $product_master, $contact;

$thrive = new THRIVE;
/* Get the order from DB */
$res = $thrive->get_order($order_id);
/* Lets check if data is processed already */
$is_processed = (@$res["processed"] == 0?false:true);
// $is_processed = false;

if($is_processed === false){
// file_put_contents("test-log.txt", print_r($res, true), FILE_APPEND | LOCK_EX);
	$site = $thrive->getSite_by_product($base_product);
	if(!$site) die();
	$site = json_decode($site["details"], true);

	$product_master = array();
	foreach($site["Products"] as $p){
		$product_master[$p["product_id"].strtoupper(@$p["is_bump"])] =  $p;
	}

	require_once("thanks_helper.php");
	// require_once("admin/class/memberapi.abstract.php");
	require_once("admin/class/wlm.memberapi.class.php");
	require_once("admin/class/wp.memberapi.class.php");

	if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
		require_once("admin/models/ac.class.php");
		$ac = new AC($site["APIs"]["ActiveCampaign"]["url"], $site["APIs"]["ActiveCampaign"]["key"]);
	}

	$wp_home_url = $site["APIs"]["Membership"]["url"];

	switch(@$site["APIs"]["Membership"]["plugin"]){
		case "wishlist":
			$api = new WishListMemberAPI($wp_home_url, $site["APIs"]["Membership"]["WishList_API_Key"]);
		break;
		default:
			$api = new WPMemberAPI($wp_home_url, $site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]);
		break;
	}

	$details = $tc = json_decode(stripslashes($res["details"]), true);
	if(!$details){
		$details = $tc = json_decode($res["details"], true);
	}


	$order = $details["order"];
	// print_r($details);
	$purchase_map = $details["purchase_map"];


	if(isset($details["customer"]["custom_fields"])){
		$thrive->updateThriveCartCustomFields($site["id"], (array)$details["customer"]["custom_fields"]);
	}

	/* get email */
	$url_email = strtolower($details["customer"]["email"]);
	if(@$site["AC"]["UseStoredEmail"]){
		$email = (isset($_COOKIE['stored-email']) && $_COOKIE['stored-email'] != "" ?$_COOKIE['stored-email']:$url_email);
	}else{
		$email = $url_email;
	}

	/* Generate password */
	$md5 = md5($email.rand(100,1000000));
	$user_pass = str_shuffle(substr($md5 , 0, 5). strtoupper(substr($md5 , 5, 4)));


	/* get GEO location */
	require_once('geoplugin.class.php');
	$geoplugin = new geoPlugin();
	$geoplugin->locate();

	/**** Lets Define User Information Here */
	require_once('settings.php');
	$user_info = @$details["customer"];
	foreach($system_data as $k => $f){
		$magic = false;
		eval('$magic = @$details'.$f['key'].";");
		if($magic)
		$user_info[$k] = $magic;
	}
	foreach((array)@$site["AC"]["ThriveCartCustomFields"] as $k => $f){
		$magic = false;
		$magic = @$details['customer']['custom_fields'][$k];
		if($magic)
		$user_info[$k] = $magic;
	}

	if(isset($details["referrer"])) $user_info["referrer"] = $details["referrer"];
	else $user_info["referrer"] = @$_GET["referrer"];

	if(isset($details["source"])) $user_info["source"] = $details["source"];
	else $user_info["source"] = @$_GET["source"];

	
	if(isset($user_info["first_name"]) && $user_info["first_name"] != "") $user_info["first_name"] = ucwords($user_info["first_name"]);
	if(isset($user_info["last_name"]) && $user_info["last_name"] != "") $user_info["last_name"] = ucwords($user_info["last_name"]);
	if(isset($user_info["customer_contactno"]) && $user_info["customer_contactno"] != "") $user_info["customer_contactno"] = "(".substr($user_info["customer_contactno"],0,3).") ".substr($user_info["customer_contactno"],3,3)."-".substr($user_info["customer_contactno"],6);

		/* Checkes GEO first */
	$user_info["geo_city"] = (isset($geoplugin->city) && $geoplugin->city != "" ?$geoplugin->city : @$user_info["customer_address_city"]);
	$user_info["geo_region"] = (isset($geoplugin->regionName) && $geoplugin->regionName != "" ?$geoplugin->regionName : @$user_info["customer_address_region"]);
	$user_info["geo_country"] = (isset($geoplugin->countryName) && $geoplugin->countryName != "" ?$geoplugin->countryName : @$user_info["customer_address_country"]);

	$extaddress = "";
	$extaddress .= (isset($user_info["customer_address_line1"]) && $user_info["customer_address_line1"] != "" && strtolower($user_info["customer_address_line1"]) != "not applicable" ?$user_info["customer_address_line1"]:"");
	$extaddress .= (isset($user_info["customer_address_city"]) && $user_info["customer_address_city"] != "" && strtolower($user_info["customer_address_city"]) != "not applicable" ?", ".$user_info["customer_address_city"]:"");
	$extaddress .= (isset($user_info["customer_address_state"]) && $user_info["customer_address_state"] != "" && strtolower($user_info["customer_address_state"]) != "not applicable" ?", ".$user_info["customer_address_state"]:"");
	$extaddress .= (isset($user_info["customer_address_province"]) && $user_info["customer_address_province"] != "" && strtolower($user_info["customer_address_province"]) != "not applicable" ?", ".$user_info["customer_address_province"]:"");
	$extaddress .= (isset($user_info["customer_address_zip"]) && $user_info["customer_address_zip"] != "" && strtolower($user_info["customer_address_zip"]) != "not applicable" ?" ".$user_info["customer_address_zip"]:"");
	$extaddress .= (isset($user_info["customer_address_country"]) && $user_info["customer_address_country"] != "" && strtolower($user_info["customer_address_country"]) != "not applicable" ?", ".$user_info["customer_address_country"]:"");


	/**** End of User Information */
		
	if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
		/* Deep Data ECommerce */
		$ordersv3 = array();
		if($site["APIs"]["ActiveCampaign"]["plan"] == "plus"){
			foreach($order["charges"] as $o){
				if($o["type"] != "recurring"){
					$ordersv3[] = array(
						"externalid" 	=> @$o["reference"],
						"name" 			=> htmlspecialchars_decode(htmlspecialchars_decode(@$o["name"])),
						"price" 		=> intval(@$o["amount"]),
						"quantity" 		=> (intval(@$o["quantity"])>0?intval(@$o["quantity"]):1),
					);
				}
			}
			
			$orderidv3 = $details["order_id"];
			
			$forlog["orders"] = $ordersv3;
			$forlog["order_id"] = $orderidv3;
			
			$resOrder = $ac->createOrder($orderidv3, $email, $ordersv3, $order["total"], $details["currency"], $details["customer_id"], 1, $details["order_date"], $details["fulfillment"]["url"]);
			$forlog["args"] = array($orderidv3, $email, $ordersv3, $order["total"], $details["currency"], $details["customer_id"], 1, $details["order_date"], $details["fulfillment"]["url"]);
			$forlog["response"] = $resOrder;
			if(isset($resOrder["errors"])){
				log_this("webhook-ac.logs.txt", array("Deep Data" => $forlog));
				if(strpos(strtolower($resOrder["errors"][0]["title"]), "already exists"))die("ERROR: ".$resOrder["errors"][0]["title"]); // This Order is in the system so lets kill the process.
			}
		}
		/* END Deep Data ECommerce */
		
		
		/* Lets Get Contact Info for its Lifetime Value */
		$contact = $ac->get_contact($email);
		$contact = json_decode($contact, true);
		$clifetime_value = 0;
		$ctotal_orders = 0;
		$ctotal_products = 0;
		$user_info["order_price"] = 0;
		foreach((array)@$site["AC"]["CF"] as $c){
			if($c["thrive"] == "order_lifetime_value"){
				$clifetime_value = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			}elseif($c["thrive"] == "total_orders"){
				$ctotal_orders = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			}elseif($c["thrive"] == "total_products"){
				$ctotal_products = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			}elseif($c["thrive"] == "order_price"){
				$user_info["order_price"] = floatval($order["total"])/100;
			}elseif($c["thrive"] == "order_product_count"){
				$user_info["order_product_count"] = count($details["purchases"]);
			}elseif($c["thrive"] == "order_currency"){
				$user_info["order_currency"] = $details["currency"];
			}elseif($c["thrive"] == "product_name"){
				$user_info["product_name"] = htmlspecialchars_decode(htmlspecialchars_decode($details["purchases"][0]));
			}elseif($c["thrive"] == "product_id"){
				$user_info["product_id"] = $details["base_product"];
			}elseif($c["thrive"] == "product_category"){
				// $user_info["product_category"] = $details["base_product"];
			}elseif($c["thrive"] == "order_date"){
				$user_info["order_date"] = date("Y-m-d", strtotime($details["order_date"]));
			}elseif($c["thrive"] == "order_time"){
				$user_info["order_time"] = date("h:i:sA", strtotime($details["order_date"]));
			}
		}
		
		$parameters = array();
		if(@$details['customer']['first_name']) $parameters["first_name"] = $details['customer']['first_name'];
		if(@$details['customer']['last_name']) $parameters["last_name"] = $details['customer']['last_name'];
		if(@$details['customer']['contactno']) $parameters["phone"] = $details['customer']['contactno'];
		if(@$details['customer']['business_name']) $user_info["orgname"] = $details['customer']['business_name'];
		
		/* Lets Create Dynamic Custom Fields */
			$missingFields = array();
			foreach($details["purchases"] as $i=>$purchase){
				$purchase = htmlspecialchars_decode(htmlspecialchars_decode($purchase));
				$forder_date = "Order Date - ".$purchase;
				$forder_time = "Order Time - ".$purchase;
				$forder_source = "Source - ".$purchase;
				$forder_access = "Access - ".$purchase;
				
				if($site["AC"]["DynamicFields"]["addOrderDate"] == "true"){
					$rdate = $ac->addCustomField($forder_date, 9);
					if(isset($rdate["fieldid"])){
						$parameters["field[".$rdate["fieldid"].",0]"] = date("Y-m-d", strtotime($details["order_date"]));
					}else{
						$missingFields[] = array("field" => $forder_date, "value" => date("Y-m-d", strtotime($details["order_date"])));
					}
				}
				
				if($site["AC"]["DynamicFields"]["addOrderTime"] == "true"){
					$rtime = $ac->addCustomField($forder_time);
					if(isset($rtime["fieldid"])){
						$parameters["field[".$rtime["fieldid"].",0]"] = date("h:i:sA", strtotime($details["order_date"]));
					}else{
						$missingFields[] = array("field" => $forder_time, "value" => date("h:i:sA", strtotime($details["order_date"])));
					}
				}
				
				if($site["AC"]["DynamicFields"]["addSrouce"] == "true" && @$_GET["ref"]){
					$rsource = $ac->addCustomField($forder_source);
					if(isset($rsource["fieldid"])){
						$parameters["field[".$rsource["fieldid"].",0]"] = @$_GET["ref"];
					}else{
						$missingFields[] = array("field" => $forder_source, "value" => @$_GET["ref"]);
					}
				}
				
				if($site["AC"]["DynamicFields"]["addAccess"] == "true"){
					$pmap = $details["purchase_map"][$i];
					$pm = explode("-", $pmap);
					$pid = @$pm[1];
					if(strtolower(@$pm[0]) == "bump") $pid .= "BUMP";
					$aurl = @$product_master[$pid]["dashboard_url"];
					if(!$pid || !@$aurl || @$aurl == "")continue;
					if(@$site["APIs"]["Membership"]["Enable_One_Click_Login"] === "true"){
						$aurl = $api->gen_dashboard_url($email, @$aurl, @$site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]);
					}
					$raccess = $ac->addCustomField($forder_access);
					if(isset($raccess["fieldid"])){
						$parameters["field[".$raccess["fieldid"].",0]"] = $aurl;
					}else{
						$missingFields[] = array("field" => $forder_access, "value" => $aurl);
					}
				}
			}
			
			foreach($details["order"]["charges"] as $charge){
				if($charge["type"] != "recurring") continue;
				$purchase = htmlspecialchars_decode(htmlspecialchars_decode($charge["name"]));
				$forder_subAmount = "Subscription Amount - ".$purchase;
				$forder_subFrequency = "Subscription Frequency - ".$purchase;
				$forder_subDue = "Subscription Due - ".$purchase;
				$forder_subValueDue = "Subscription Value Due - ".$purchase;
				
				if($site["AC"]["DynamicFields"]["addSubAmount"] == "true"){
					$rSubAmount = $ac->addCustomField($forder_subAmount);
					$val = number_format(floatval($charge["amount"])/100, 2);
					if(isset($rSubAmount["fieldid"])){
						$parameters["field[".$rSubAmount["fieldid"].",0]"] = $val;
					}else{
						$missingFields[] = array("field" => $forder_subAmount, "value" => $val);
					}
				}
				if($site["AC"]["DynamicFields"]["addSubFrequency"] == "true"){
					$rsubFrequency = $ac->addCustomField($forder_subFrequency);
					$val = ucwords($charge["frequency"]);
					if(isset($rsubFrequency["fieldid"])){
						$parameters["field[".$rsubFrequency["fieldid"].",0]"] = $val;
					}else{
						$missingFields[] = array("field" => $forder_subFrequency, "value" => $val);
					}
				}
				if($site["AC"]["DynamicFields"]["addSubDue"] == "true" && isset($details["order"]["future_charges"])){
					$rsubDue = $ac->addCustomField($forder_subDue);
					$val = $details["order"]["future_charges"]["due"];
					if(isset($rsubDue["fieldid"])){
						$parameters["field[".$rsubDue["fieldid"].",0]"] = $val;
					}else{
						$missingFields[] = array("field" => $forder_subDue, "value" => $val);
					}
				}
				if($site["AC"]["DynamicFields"]["addSubValueDue"] == "true" && isset($details["order"]["future_charges"])){
					$rSubValueDue = $ac->addCustomField($forder_subValueDue);
					$val = intval(@$details["order"]["future_charges"]["due"]) * floatval(@$details["order"]["future_charges"]["amount"]);
					if(isset($rSubValueDue["fieldid"])){
						$parameters["field[".$rSubValueDue["fieldid"].",0]"] = $val;
					}else{
						$missingFields[] = array("field" => $forder_subValueDue, "value" => $val);
					}
				}
			}
			
			if(count($missingFields)>0){
				if($contact["result_code"] == 0){
					$res = $ac->get_custom_fields();
					$cfields = json_decode($res, true);
				}else{
					$cfields = $contact["fields"];
				}
				foreach($cfields as $field){
					if(count($missingFields) <= 0)break;
					foreach($missingFields as $k => $missingField){
						if($missingField["field"] == $field["title"]){
							$parameters["field[".$field["id"].",0]"] = $missingField["value"];
							unset($missingFields[$k]);
						}
					}
				}
			}
		/* END Lets Create Dynamic Custom Fields */
		
	
		$product_tags = array(
			/* Edit this line to define custom tags for specific product. Create new line like this for each product. */
			"pid" => array("[ACCESS]", "[CUSTOMER]", "etc.."), // replace pid with actual product id. 1 Product can have multiple Tags.
		);
		
		/* Lets populate Tags here */
		$tags = array();
		foreach($order["charges"] as $i => $product){
			if($product["type"] == "recurring") continue;
			$id = $product["reference"]; // Product id
			$type = get_type($id, $i, $purchase_map); // product, bump, upsell
			$name = htmlspecialchars_decode(htmlspecialchars_decode($product["name"])); // Product name
			$price = floatval($product["amount"])/100; // Price
			
			array_push($tags, strtoupper("[".$type."] ").$name); // This produces something like [PRODUCT] Product Name. This is the default Tag for each product
			
			if (in_array($id, $product_tags)) {
				foreach($product_tags[$id] as $tag){
					/* $tag is the one you listed on $product_tags and $name is the product name. */
					/* you can also use $type, $id, $name, $price */

					$ptag = $tag." ".$name;
					array_push($tags, $ptag);
				}
			}
		}
		
		/* AC Tags */
		$parameters['tags'] = implode(',', $tags);
		
		/* Create ActiveCampaign Contact information */
		$lifetime_value = (floatval($order["total"])/100) + $clifetime_value;
		$user_info["order_lifetime_value"] = $lifetime_value;
		
		$user_info["total_orders"] =  $ctotal_orders + 1;
		
		$user_info["total_products"] =  count($details["purchases"]) + $ctotal_products;
		
		/* Subscribe to Lists */
		$lists = "";
		$lsep = "";
		foreach((array)@$site["AC"]["Lists"] as $List){
			$lists .=$lsep.$List["id"];
			$lsep = ", ";
			// $parameters["p[".$List["id"]."]"] = $List["id"];
		}

	
		$user_info["order_processor"] = ucwords($user_info["order_processor"]);
		$paypal_email_fid = 0;
		$payment_email_fid = 0;
		$username_fid = 0;
		$password_fid = 0;
		$license_fid = 0;
		$ref_fid = 0;

		foreach((array)@$site["AC"]["CF"] as $CF){
			if($CF["thrive"] == "stripe_id"){
				if(strtolower($order["processor"]) == "stripe"){
					$parameters["field[".$CF["field"]["id"].",0]"] = @$user_info[$CF["thrive"]];
				}
			}elseif($CF["thrive"] == "paypal_email"){
				$paypal_email_fid = $CF["field"]["id"];
			}elseif($CF["thrive"] == "payment_email"){
				$payment_email_fid = $CF["field"]["id"];
			}elseif($CF["thrive"] == "user_name"){
				$username_fid = $CF["field"]["id"];
			}elseif($CF["thrive"] == "password"){
				$password_fid = $CF["field"]["id"];
			}elseif($CF["thrive"] == "ctsp_license"){
				$license_fid = $CF["field"]["id"];
				$parameters["field[".$license_fid.",0]"] = @$_GET["license_key"];
			}elseif($CF["thrive"] == "ref"){
				$ref_fid = $CF["field"]["id"];
				$parameters["field[".$ref_fid.",0]"] = @$_GET["ref"];
			}else{
				$parameters["field[".$CF["field"]["id"].",0]"] = @$user_info[$CF["thrive"]];
			}
		}
		
		
		if(strtolower($order["processor"]) == "paypal" && $paypal_email_fid > 0){
			$parameters["field[".$paypal_email_fid.",0]"] = $url_email; // Lets try to set this field and use Email from Thrive if processor is paypal
		}elseif($url_email != $email && strtolower($order["processor"]) != "paypal" && $payment_email_fid > 0){
			$parameters["field[".$payment_email_fid.",0]"] = $url_email; // Lets set this field if processor is not paypal and Thrive email is not the same with cookied email.
		}
		
		
		if(@$site["AC"]["Note"] == "true"){
			/* Note -------------- */
			$note = "Order ID : ".$user_info["order_id"]."\n";
			$note .= "Lifetime Value : $lifetime_value\n";
			$note .= "Payment Processor : ".ucfirst($user_info["order_processor"])."\n\r\n\r";
			$note .= "Customer :\n";
			if(@$user_info["first_name"] || @$user_info["last_name"])$note .= $user_info["first_name"]." ".$user_info["last_name"]."\n";
			$note .= "$email\n";
			if(@$user_info["customer_contactno"])$note .= $user_info["customer_contactno"]."\n";
			if(@$extaddress)$note .= "$extaddress\n";
			$note .= "\r\n\rOrder :\n\r";
			foreach($order["charges"] as $i => $on){
				$op = number_format(floatval($on["amount"])/100, 2)." ".$details["currency"];
				if($on["type"] == "recurring"){
					$type = "Recurring";
					$freq = "/".ucwords($on["frequency"]);
				}else{
					$type = get_type($on["reference"], $i, $purchase_map);
					$freq = "";
				}
				$oname =  htmlspecialchars_decode(htmlspecialchars_decode($on["name"]));
				$note .= $oname." | ".$type." | ".$op.$freq."\n";
			}
			$note .= 'Total Order : '.number_format(floatval($order["total"])/100,2)." ".$details["currency"]."\n";
			if(@$user_info["coupon_name"])$note .= 'Coupon : '.$user_info["coupon_name"]."\n\r";
			if(@$affiliate_id)$note .= 'Affiliate Referral ID : '.$affiliate_id."\n";
			if(@$user_info["source"])$note .= 'Source : '.@$user_info["source"]."\n";
			if(@$user_info["referrer"])$note .= 'Referrer : '.@$user_info["referrer"]."\n\r";
			$note_parameters = array(
				'listid'		=> 0,
				'note'			=> print_r($note, true),
			);
			/* !Note */
		}
	}
	
	
	/* WishList -------------- */
	$levels = array();
	// if(@$site["APIs"]["Membership"]["useWihList"] == "true"){
	if(@$site["APIs"]["Membership"]["plugin"] == "wishlist"){
		foreach($order["charges"] as $i=>$prod){
			$pmap = $details["purchase_map"][$i];
			$pm = explode("-", $pmap);
			$pid = @$pm[1];
			if(strtolower(@$pm[0]) == "bump") $pid .= "BUMP";
			if($product_master[$pid]["wishlist_id"])array_push($levels, $product_master[$pid]["wishlist_id"]);
		}
		$levels = array_unique($levels);
	}

	/* !WishList */
	
	if(!empty($levels) || @$site["APIs"]["Membership"]["plugin"] != "wishlist"){
		$data = array(
			"first_name"	=> @$user_info["first_name"],
			"last_name"		=> @$user_info["last_name"],
			"display_name"	=> @$user_info["first_name"]." ".@$user_info["last_name"],
			"nickname"		=> @$user_info["first_name"],
			"user_login"	=> $email,
			"user_pass"		=> $user_pass,
			"user_email"	=> $email,
			"Levels" 		=> $levels,
			"contact_id"	=> $contact["id"]
		);
		
		$response = $api->create_user($data);

		if(!is_array($response)) $res = json_decode($response, true);
		$wpmember = array();
		
		if(@$res["success"] == 0){ // Fail to create new member because email already exists.
			$wpmember = $api->get_user($email);
			
			if(@$wpmember["success"] == 1){
				/* Add to Levels */
				$api->add_to_levels($levels, $wpmember["id"]);
				
				// Lests update username to ActiveCampaign.
				if(intval($username_fid) >0) $parameters['field['.$username_fid.',0]']	= $wpmember["user_login"];
			}
		}elseif(@$res["success"] == 1){
			/* Lests save password to ActiveCampaign. */
			if(intval($password_fid) >0) $parameters['field['.$password_fid.',0]']	= $user_pass;
			if(intval($username_fid) >0) $parameters['field['.$username_fid.',0]']	= $email;
		}
	}
	
	if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
		$res = $ac->update_contact($parameters, $email);
		// echo $res;
		$contact = json_decode($res, true);
		if(@$site["AC"]["Note"] == "true"){
			$note_res = $ac->add_note($note_parameters, $contact["subscriber_id"]);
		}
		log_this("webhook-ac.logs.txt", array("update" => @$parameters, "note" => @$note_parameters, "contact_id"=> @$contact["subscriber_id"], "DeepData" => @$forlog, "hasnote" => @$note_res, "missignFields" => @$themiss, "rdate" => @$rdate, "rtime" => @$rtime, "rsource" => @$rsource, "raccess" => @$raccess, "wlm_req" => @$data, "wlm_res" => @$response, "products" => @$product_master));
	}
	$thrive->markprocessed($user_info["order_id"]);
}
?>