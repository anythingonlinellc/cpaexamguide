<?php
define("THANKSTHRIVE", true);
require_once("thanks_helper.php");
require_once("admin/functions.php");
require_once("thrive.class.php");

log_this("thanks-webhook-log.txt", $_REQUEST);

$thrive = new THRIVE;
if(!isset($_REQUEST["thrivecart_secret"]) || $_REQUEST["thrivecart_secret"] == "" || $thrive->checkThriveSecret($_REQUEST["thrivecart_secret"]) == false) die("You have no business here!");



$base_product = @$_REQUEST["base_product"];

$cs = $thrive->getSite_by_product($base_product);

if(!isset($cs["id"])) die("You have no business here!");

$thrive->recordOrder($_REQUEST);

$order_id = @$_REQUEST["order_id"];
$affiliate_id = @$_REQUEST["affiliate_id"];
$base_product = @$_REQUEST["base_product"];

switch($_REQUEST["event"]){
	case "order.refund": // refund
		require_once("webhook_refund.php");
	break;
	case "order.subscription_payment": // reacurring
		require_once("webhook_recurring.php");
	break;
	case "order.subscription_cancelled": // Cancel
		require_once("webhook_cancel.php");
	break;
	case "order.success": // new purcahse
		require_once("webhook_process.php");
	break;
}
?>