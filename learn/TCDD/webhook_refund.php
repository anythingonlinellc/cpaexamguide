<?php
$site = $thrive->getSite_by_product($base_product);
if(!$site) die();
$site = json_decode($site["details"], true);

if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
	require_once("admin/models/ac.class.php");
	$ac = new AC($site["APIs"]["ActiveCampaign"]["url"], $site["APIs"]["ActiveCampaign"]["key"]);
	$refund = $_REQUEST;
	$order = $refund["order"];
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
	$ctotal_orders = 0;
	$ctotal_products = 0;
	$refund_amount = floatval($refund["refund"]["amount"])/100;

	$lv_id = 0;
	$to_id = 0;
	$tp_id = 0;
	foreach((array)@$site["AC"]["CF"] as $c){
		if($c["thrive"] == "order_lifetime_value"){
			$clifetime_value = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			$lv_id = $c["field"]["id"];
		}elseif($c["thrive"] == "total_orders"){
			$ctotal_orders = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			$to_id = $c["field"]["id"];
		}elseif($c["thrive"] == "total_products"){
			$ctotal_products = floatval(@$contact["fields"][$c["field"]["id"]]["val"]);
			$tp_id = $c["field"]["id"];
		}
	}

	$lifetime_val = $clifetime_value - $refund_amount;
	$total_orders = $ctotal_orders - 1;
	$total_products = $ctotal_products - count($order["purchases"]);

	$parameters = array();
	if($lv_id){
		$parameters['field['.$lv_id.',0]'] = $lifetime_val;
	}
	if($to_id){
		$parameters['field['.$to_id.',0]'] = $total_orders;
	}
	if($tp_id){
		$parameters['field['.$tp_id.',0]'] = $total_products;
	}

	if($parameters)	$res = $ac->update_contact($parameters, $email);
	
	/* Deep Data ECommerce */
	$ordersv3 = array();
	if(@$site["APIs"]["ActiveCampaign"]["plan"] == "plus"){
		foreach($details["order"]["charges"] as $o){
			if($o["type"] != "recurring"){
				if(@$o["reference"] == $refund["refund"]["id"]) $o["name"] = "Refund - ".$o["name"];
				$ordersv3[] = array(
					"externalid" 	=> @$o["reference"],
					"name" 			=> htmlspecialchars_decode(htmlspecialchars_decode(@$o["name"])),
					"price" 		=> 0,
					"quantity" 		=> (intval(@$o["quantity"])>0?intval(@$o["quantity"]):1),
				);
			}
		}
		$orderidv3 = $refund["order_id"];
		$atotal = floatval($details["order"]["total"]) - floatVal($refund["refund"]["amount"]);
		$resOrder = $ac->refund($orderidv3, $email, $ordersv3, $atotal);
		
		if(isset($resOrder["errors"])){
			if(strpos(strtolower($resOrder["errors"][0]["title"]), "already exists"))die("ERROR: ".$resOrder["errors"][0]["title"]); // This Order is in the system so lets kill the process.
		}
	}
	/* END Deep Data ECommerce */
	
	
	if(@$site["AC"]["Note"] == "true"){
		$note = "REFUND\n\r\n\r";
		$note .= "Order ID : ".$order_id."\n";
		$note .= "Lifetime Value : ".number_format($lifetime_val, 2)."\n";
		$note .= "Payment Processor : ".ucfirst($refund["order"]["processor"])."\n\r\n\r";
		$note .= "Refund ID : ".$refund["refund"]["id"]."\n";
		$note .= "Refund Processor : ".ucfirst($refund["refund"]["processor"])."\n\r";
		$note .= "Refund Amount : ".number_format(@$refund_amount, 2)."\n\r\n\r";
		$note .= "Customer :\n";
		if(@$refund['customer']['first_name'] || @$refund["customer"]["last_name"])$note .= $refund["customer"]["first_name"]." ".$refund["customer"]["last_name"]."\n";
		$note .= "$email\n";
		if(@$refund['customer']['contactno'])$note .= $refund['customer']['contactno']."\n";
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
		$note .= 'Total Order : '.number_format(floatval($refund["order"]["total"])/100, 2)." ".@$refund["currency"]."\n";
		$note .= 'Refund Amount : '.number_format(@$refund_amount,2)." ".@$refund["currency"]."\n";
		if(@$refund["coupon_name"])$note .= 'Coupon : '.$refund["coupon_name"]."\n\r";
		$note_parameters = array(
			'listid'		=> 0,
			'note'			=> $note,
		);
		
		$note_res = $ac->add_note($note_parameters, $contact["id"]);
	}
	
	log_this("webhook-refund-logs.txt", array("update" => $parameters, "note" => $note_parameters, "contact_id"=>$contact["id"]));
}
?>