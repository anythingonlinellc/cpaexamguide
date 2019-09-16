<?php
$site = $thrive->getSite_by_product($base_product);
if(!$site) die();
$site = json_decode($site["details"], true);

if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
	require_once("admin/models/ac.class.php");
	$ac = new AC($site["APIs"]["ActiveCampaign"]["url"], $site["APIs"]["ActiveCampaign"]["key"]);
	$recurring = $_REQUEST;
	$res = $thrive->get_order($order_id);
	$details = $tc = json_decode(stripslashes($res["details"]), true);
	if(!$details){
		$details = $tc = json_decode($res["details"], true);
	}
	
	$email = strtolower($details["customer"]["email"]);
		
	$contact = $ac->get_contact($email);
	$contact = json_decode($contact, true);


	/* Lets Get Contact Info for its Lifetime Value */
	$contact = $ac->get_contact($email);
	$contact = json_decode($contact, true);
	$clifetime_value = 0;
	$payment_total = floatval($recurring["subscription"]["amount"])/100;

	$lv_id = 0;
	foreach((array)@$site["AC"]["CF"] as $c){
		if($c["thrive"] == "order_lifetime_value"){
			$clifetime_value = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			$lv_id = $c["field"]["id"];
			break;
		}
	}

	$lifetime_val = $clifetime_value + $payment_total;

	$parameters = array();
	if($lv_id){
		$parameters['field['.$lv_id.',0]'] = $lifetime_val;
	}

	if($parameters)	$res = $ac->update_contact($parameters, $email);
	
	/* Deep Data ECommerce */
	$ordersv3 = array();
	if(@$site["APIs"]["ActiveCampaign"]["plan"] == "plus"){
		$subscription_amount = intval(@$recurring["subscription"]["amount"]);
		$ordersv3[] = array(
			"externalid" 	=> @$recurring["subscription"]["id"],
			"name" 			=> htmlspecialchars_decode(htmlspecialchars_decode(@$recurring["subscription"]["name"])),
			"price" 		=> $subscription_amount,
			"quantity" 		=> (intval(@$recurring["subscription"]["quantity"])>0?intval(@$recurring["subscription"]["quantity"]):1),
		);
		$pcount = 1;
		foreach($details["update"] as $ru){
			if($ru["subscription"]["id"] == $recurring["subscription"]["id"])$pcount++;
		}
		// $orderidv3 = $recurring["order_id"]."-".$recurring["subscription"]["id"]."-payment".$pcount;
		$orderidv3 = $recurring["order_id"].$recurring["subscription"]["id"].$pcount;
		$resOrder = $ac->createOrder($orderidv3, $email, $ordersv3, $subscription_amount, $recurring["currency"], $recurring["customer_id"], 1, Date("Y-m-d H:i:s"), $details["fulfillment"]["url"]);
		
		if(isset($resOrder["errors"])){
			if(strpos(strtolower($resOrder["errors"][0]["title"]), "already exists"))die("ERROR: ".$resOrder["errors"][0]["title"]); // This Order is in the system so lets kill the process.
		}
	}
	/* END Deep Data ECommerce */
	
	if(@$site["AC"]["Note"] == "true"){
		$note = "RECURRING \n\r\n\r";
		$note .= "Order ID : ".$orderidv3."\n";
		$note .= "Lifetime Value : ".number_format($lifetime_val, 2)."\n";
		$note .= "Payment Processor : ".ucfirst(@$recurring["order"]["processor"])."\n\r\n\r";
		$note .= "Subscription ID : ".$recurring["subscription"]["id"]."\n";
		$note .= "Subscription Processor : ".ucfirst(@$recurring["subscription"]["processor"])."\n";
		$note .= "Subscription Amount : ".number_format(@$payment_total, 2)."\n\r\n\r";
		$note .= "Customer :\n";
		if(@$recurring['customer']['first_name'] || @$recurring["customer"]["last_name"])$note .= $recurring["customer"]["first_name"]." ".$recurring["customer"]["last_name"]."\n";
		$note .= "$email\n";
		if(@$recurring['customer']['contactno'])$note .= $recurring['customer']['contactno']."\n";
		$note .= "\r\n\rOrder :\n\r";
		foreach($details["order"]["charges"] as $i => $on){
			$op = '$'. number_format(floatval($on["amount"])/100, 2);
			if($on["type"] == "recurring"){
				$type = "Recurring";
				$freq = "/".ucwords($on["frequency"]);
			}else{
				$type = get_type($on["reference"], $i, $details["purchase_map"]);
				$freq = "";
			}
			$oname =  htmlspecialchars_decode(htmlspecialchars_decode($on["name"]));
			$note .= $oname." | ".$type." | ".$op.$freq."\n";
		}
		$note .= 'Total Order : '.number_format(floatval($recurring["order"]["total"])/100, 2)." ".@$recurring["currency"]."\n";
		$note .= 'Subscription Amount : '.number_format($payment_total, 2)." ".@$recurring["currency"]."\n";
		if(@$recurring["coupon_name"])$note .= 'Coupon : '.$recurring["coupon_name"]."\n\r";
		$note_parameters = array(
			'listid'		=> 0,
			'note'			=> $note,
		);
		
		$note_res = $ac->add_note($note_parameters, $contact["id"]);
	}
	
	log_this("webhook-recurring-logs.txt", array("update" => $parameters, "note" => $note_parameters, "contact_id"=>$contact["id"], "DEEP" => array("res"=>$resOrder, "o" => $ordersv3)));
}
?>