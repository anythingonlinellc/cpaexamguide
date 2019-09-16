<?php
$site = $thrive->getSite_by_product($base_product);
if(!$site) die();
$site = json_decode($site["details"], true);

if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
	require_once("admin/models/ac.class.php");
	$ac = new AC($site["APIs"]["ActiveCampaign"]["url"], $site["APIs"]["ActiveCampaign"]["key"]);
	$cancel = $_REQUEST;
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
		
	if(@$site["AC"]["Note"] == "true"){
		$note = "CANCELLED \n\r\n\r";
		$note .= "Order ID : ".$order_id."\n";
		$note .= "Lifetime Value : ".number_format($lifetime_val, 2)."\n";
		$note .= "Payment Processor : ".ucfirst(@$cancel["order"]["processor"])."\n\r\n\r";
		$note .= "Subscription ID : ".$cancel["subscription"]["id"]."\n";
		$note .= "Subscription Processor : ".ucfirst(@$cancel["subscription"]["processor"])."\n\r\n\r";
		$note .= "Customer :\n";
		if(@$cancel['customer']['first_name'] || @$cancel["customer"]["last_name"])$note .= $cancel["customer"]["first_name"]." ".$cancel["customer"]["last_name"]."\n";
		$note .= "$email\n";
		if(@$cancel['customer']['contactno'])$note .= $cancel['customer']['contactno']."\n";
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
		$note .= 'Total Order : '.number_format(floatval($cancel["order"]["total"])/100, 2)." ".@$cancel["currency"]."\n";
		if(@$cancel["coupon_name"])$note .= 'Coupon : '.$cancel["coupon_name"]."\n\r";
		$note_parameters = array(
			'listid'		=> 0,
			'note'			=> $note,
		);
		
		$note_res = $ac->add_note($note_parameters, $contact["id"]);
	}
	
	log_this("webhook-cancel-logs.txt", array("update" => $parameters, "note" => $note_parameters, "contact_id"=>$contact["id"]));
}
?>