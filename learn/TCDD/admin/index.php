<?php
error_reporting(0);
define("THANKSTHRIVE", true);
if(!file_exists("../config.php")){
	header("Location: ../install.php");
}

if(!isset($_COOKIE["keep"])){
	if(session_id() == '') {
		session_start();
	}
}
require_once("../config.php");
require_once("../settings.php");
if(file_exists("loggedkey.php")){
	require_once("loggedkey.php");
}
require_once("../thrive.class.php");
require_once("functions.php");
require_once("models/sites.class.php");
if( (isset($_COOKIE["keep"]) && isset($_COOKIE["loggedin"]) && $_COOKIE["loggedin"] == @LOGGED_KEY) || (!isset($_COOKIE["keep"]) && @$_SESSION["loggedin"] == "true") ){
	$remote_contents = array();
	if(!file_exists("lang/content.json") || filemtime("lang/content.json") < strtotime("30 minutes ago")){
		try{
			// $res = filex_get_contents("http://customthrivecartpages.com/rabbitremote/custom-success/index.php");
			$res = curl_get_contents("http://customthrivecartpages.com/rabbitremote/custom-success/index.php");
			if($res) file_put_contents("lang/content.json", $res);
			$remote_contents = json_decode($res, true);
		}catch(Exception $e){}
	}else{
		$remote_contents = json_decode(file_get_contents("lang/content.json"), true);
	}
	
	$thrive = new THRIVE;
	
	if(!file_exists("../download/custom_thrive_success_pages.zip")){	
		$thrive->createThanksZip();
	}
	
	$tooltips = @$remote_contents["tooltips"];
	$video_tutorials = @$remote_contents["video_tutorials"];
	$pages = @$remote_contents["pages"];
	
	switch (@$_GET["action"]){
		case "show-config":
			$sm = $thrive->sqlmode();
			print_r($sm);
			die();
		break;
		case "upgrade-installer":
			require_once("upgrader.php");
			die();
		break;
		case "show-orders":
			$records = $thrive->getTableRecords($_GET["table"]);
			print_r($records);
			die();
		break;
		case "show-table":
			$thrive->showTable($_GET["table"]);
			die();
		break;
		case "alter-table":
			$thrive->alterTables();
			die();
		break;
		case "logout":
			setcookie("loggedin", "", time() - 3600);
			setcookie("keep", "", time() - 3600);
			unset($_SESSION["loggedin"]);
			$uri = explode("?", $_SERVER["REQUEST_URI"]);
			header("Location: //".$_SERVER["HTTP_HOST"].$uri[0]);
			die();
		break;
		case "edit-site":
			require_once("tabs.php");
			die();
		break;
		case "site-duplicate":
			$sitename = $_POST["sitename"];
			$siteid = $_POST["siteid"];
			$res = $sites->duplicateSite($siteid, $sitename);
			echo $res;
			die();
		break;
		case "site-add":
			$sitename = $_POST["sitename"];
			$res = $sites->addSite($sitename);
			echo $res;
			die();
		break;
		case "site-multiple":
			$res = $sites->enableMultiple($_POST["enable"]);
			echo $res;
			die();
		break;
		case "site-orders":
			$res = $sites->getSiteOrders($_GET["siteid"]);
			echo json_encode(array("data"=>$res));
			die();
		break;
		case "site-delete":
			$res = $sites->deleteSite($_POST["siteid"]);
			echo $res;
			die();
		break;
		case "site-addCustomerCustomFields":
			$res = $thrive->updateThriveCartCustomFields($_POST["siteid"], array($_POST["cf"] => $_POST["label"]));

			if($res){
				echo json_encode(array("slug" => $_POST["cf"], "label" => $res[$_POST["cf"]]));
			}
			die();
		break;
		case "site-update":
			$res = $sites->updateSite($_POST["site"]);
			echo $res;
			die();
		break;
		case "load-acdata":
			$url = $_POST["url"];
			$key = $_POST["key"];
			$res = $sites->loadACData($url, $key);
			echo json_encode($res);
			die();
		break;
		case "latest-updates":
			$res = $lupdates = $sites->getLatestUpdates();
			if(isset($res["date"])) $res["date"] = date("m/d/Y", strtotime($res["date"]));
			echo json_encode($res);
			die();
			break;
		case "updates":
			require_once("updates.php");
			die();
			break;
		case "support":
			require_once("support.php");
			die();
			break;
		case "user-guide":
			require_once("userguide.php");
			die();
			break;
		case "sites-list":
			echo $sites->getSites($_POST, "json");
			die();
			break;
		case "feature-request":
			echo $thrive->featureRequest($_POST["feature"]);
			die();
			break;
		case "new-issue":
			echo $sites->newIssue($_POST["issue"], $_POST["subject"], $_POST["name"], $_POST["email"]);
			die();
			break;
		case "conversation-issue":
			$res = $sites->issueConversation($_POST["issueid"]);
			echo json_encode($res);
			die();
			break;
		case "reply-issue":
			$res = $sites->issueReply($_POST["issueid"], $_POST["reply"]);
			echo json_encode($res);
			die();
			break;
		case "plugin-keys":
			$res = $sites->get_plugins_keys($_POST["wp"], $_POST["wpmea_key"]);
			echo json_encode($res);
			die();
			break;
		case "wlm-levels":
			require_once("class/wlm.memberapi.class.php");
			$wlm = new WishListMemberAPI($_POST["url"], $_POST["key"]);
			// echo $_POST["url"];
			// echo $_POST["key"];
			// $wlm = new memberapi($_GET["url"], $_GET["key"]);
			// $wlm = new memberapi("http://emailresponsewarrior.com/members/","82ba2db0917ce23082ba54f7ad726a94");
			$levels = $wlm->getLevels();
			echo json_encode($levels);
			die();
			break;
		default:
			require_once("sites.php");
			die();
			break;
	}
}else{
	$message = false;
	if(isset($_POST["login"])){
		if(ADMIN_UNAME == $_POST["username"] && ADMIN_PASS == $_POST["userpass"]){
			if(isset($_POST["keep"])){
				$loggedkey = md5(Date("U"));
				file_put_contents("loggedkey.php", '<?php if(!defined("THANKSTHRIVE"))die(); define("LOGGED_KEY","'.$loggedkey.'"); ?>');
				setcookie("loggedin", $loggedkey, time() + (86400 * 365));
				setcookie("keep", "true", time() + (86400 * 365));
			}else{
				if(session_id() == '') {
					session_start();
				}
				$_SESSION["loggedin"] = "true";
				setcookie("loggedin", "", time() - 3600);
				setcookie("keep", "", time() - 3600);
			}
			if(!file_exists("../download/custom_thrive_success_pages.zip")){
				$thrive = new THRIVE;
				$thrive->createThanksZip();
			}
			die("1");
		}else{
			die("Login Fail!.");
		}
	}else{
		if(@$_GET["action"] == "send-recovery"){
			$thrive = new THRIVE;
			$res = $thrive->sendPasswordRecovery();
			print_r($res);
			die();
		}elseif(@$_GET["action"] == "recover_password"){
			if(file_exists("recovery_key.php"))	require_once("recovery_key.php");
			if(@RECOVERY_KEY && RECOVERY_KEY == $_GET["recovery_key"] && date("U", filemtime("recovery_key.php")) > date("U",strtotime("24 hours ago"))){
				file_put_contents("recovery_key.php", '<?php if(!defined("THANKSTHRIVE"))die(); define("RECOVERY_KEY",false); ?>');
				$thrive = new THRIVE;
				$res = $thrive->sendAdminAccount();
				header("Location: ".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?a=1");
				die();
			}else{
				header("Location: ".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."?a=3");
				die();
			}
		}
	}
	?>
	<!DOCTYPE html>
	<html>
		<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
			jQuery(document).ready(function($){
				$("body").on("click", "#recover", function(){
					$.post("?action=send-recovery", {}, function(r){
						window.location.href = "?a=2";
					});
				});
				$("body").on("click", "#forgot", function(){
					$("#main").hide();
					$("#main.recoverycont").show();
				});
				$("body").on("click", "button#login", function(){
					$("#notif").html("");
					var haserror = false;
					var data = {
						username:$("input[name='username']").val(),
						userpass:$("input[name='userpass']").val(),
						login: "login",
					};
					if($("input[name='keep']").is(":checked")) data.keep = "true";
					if(data.username == ""){
						haserror = true;
						$("#notif").append("<p><span style='color:red;'>Please input your Usename!</span></p>");
					}
					if(data.userpass == ""){
						haserror = true;
						$("#notif").append("<p><span style='color:red;'>Please input your Password!</span></p>");
					}
					
					var url = window.location.origin+window.location.pathname;
					console.log(data);
					if(haserror == true) return;
					$.post(url, data, function(r){
						if(r != 1){
							$("#notif").html("<span style='color:red;'>"+r+"</span>");
						}else{
							window.location.href = url;
						}
					})
				});
			});
		</script>
	<style>
	html{
		font-family: Verdana, Arial, Helvetica, sans-serif;
		min-height:100%;
		background-color:#eaeaea;
	}
	.recoverycont{
		display:none;
	}
	#main {
		width: 400px;
		padding: 20px;
		margin: 0 auto;
		background-color: #ffffff;
		border-radius: 5px;
		margin-top: 30px;
	}
	.inputcont {
		overflow: hidden;
		padding: 5px 20px;
	}

	.inputcont label {
		display: block;
		width: 100px;
		float: left;
		padding: 5px;
	}
	.inputcont input {
		border: 1px solid #a0a0a0;
		padding: 5px;
		width: calc(100% - 125px);
	}
	.grouphead {
		background-color: #47494a;
		overflow: hidden;
		padding: 15px 20px;
		margin: 10px 0;
		color: #ffffff;
		border-radius: 3px;
	}
	#login{
		padding: 5px 20px;
	}
	h1{
		text-align: center;
		color: #1c9cce;
		margin-top: 5px;
	}
	#forgot{
		font-size: 13px;
		margin-left: 133px;
		color:blue;
		cursor:pointer;
	}	
	#msg{
		color:green;
		text-align:center;
	}
	</style>
		</head>
		<body>
			<div id="main">
				<h1>ThriveCart Deep Data Integration For ActiveCampaign</h1>
				<?php if(@$_GET["a"] == 1){ ?>
				<p id="msg">Your Password has been sent to your email.</p>
				<?php }elseif(@$_GET["a"] == 2){ ?>
				<p id="msg">The Password Recovery Link is sent to your email.</p>
				<?php }elseif(@$_GET["a"] == 3){ ?>
				<p id="msg" style="color:red;">Password Recovery Link is expired.</p>
				<?php } ?>
				<div class="grouphead">Please Login</div>
				<p id="notif"></p>
				<div class="inputcont">
					<label>Username</label>
					<input type="text" id="username" name="username" />
				</div>
				<div class="inputcont">
					<label>Password</label>
					<input type="password" id="userpass" name="userpass" />
				</div>
				<div style="margin: 10px 0;">
					<label style="display: inline-block; width: 130px; float: left;">&nbsp;</label>
					<div>
						<input type="checkbox" id="keep" name="keep" value="true"/>
						<label for="keep" style="font-size:12px;">Keep me loggedin</label>
						<p><a id="forgot">Forgot your password?</a></p>
					</div>
				</div>
				<div class="inputcont">
					<label>&nbsp;</label>
					<button id="login" name="login">Login</button>
				</div>
			</div>
			<div id="main" class="recoverycont">
				<h1>Password Recovery</h1>
				<p style="text-align:center;"><button id="recover">Send Recovery Link</button></p>
			</div>
		</body>
	</html>
<?php } ?>