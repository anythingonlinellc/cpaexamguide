<?php
error_reporting(0);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
if(file_exists("config.php")){
	header("Location: admin/");
	exit();
}
define("THANKSTHRIVE", true);
require_once("admin/functions.php");
	$remote_contents = array();
	if(!file_exists("admin/lang/content.json") || filemtime("admin/lang/content.json") < strtotime("30 minutes ago")){
		// $res = filex_get_contents("http://customthrivecartpages.com/rabbitremote/custom-success/index.php");
		$res = curl_get_contents("http://customthrivecartpages.com/rabbitremote/custom-success/index.php");
		if($res) file_put_contents("admin/lang/content.json", $res);
		$remote_contents = json_decode($res, true);
	}else{
		$remote_contents = json_decode(file_get_contents("admin/lang/content.json"), true);
	}
	
	$tooltips = @$remote_contents["tooltips"];
	$video_tutorials = @$remote_contents["video_tutorials"];
	$pages = @$remote_contents["pages"];

if(isset($_POST["install"])){
	require_once("thrive.class.php");
	$THRIVE = new THRIVE(true);
	$r = $THRIVE->validateLicense($_POST["email"], $_POST["key"], $_POST["thrivecart_store_id"]);

	$valid = json_decode($r, true);
	if($valid["result"] == 1) {
		$res = $THRIVE->install( $_POST["dbhost"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"], $_POST["dbOrderPrefix"], $_POST["username"], $_POST["userpass"], $_POST["email"], $_POST["key"], $_POST["thrivecart_store_id"] );
		// var_dump($res);
	}else{
	    $res = $valid["message"];
    }
	die($res);
}

?>
<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="admin/common.css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		jQuery(document).ready(function($){
			$("body").on("click", "input#install", function(){
				var data = {};
				var has_error = false;
				$(".installForm input").each(function(u, i){
					if($(this).val() == ""){
						$("#notif").html("<span style='color:red;'>All fields are required!</span>");
                        has_error = true;
                        return false;
					}else{
						data[$(this).attr("name")] = $(this).val();
					}
				});
				console.log("href", window.location.href);
				console.log(data);
				if(has_error == true) return false;
				$.post(window.location.href, data, function(r){
					console.log("reponse",r);
					if(r != 1){
						$("#notif").html("<span style='color:red;'>*"+r+"</span>");
					}else{
						window.location.replace("admin");
					}
				});
			});
		});
	</script>
	<style>
	#main {
		width: 430px;
		padding: 30px 50px;
		margin: 0 auto;
		margin-top: 15px;
	}
	.inputcont {
		overflow: hidden;
		margin-top:5px;
	}
	.inputcont label {
		display: block;
		width: 150px;
		float: left;
		padding: 5px;
	}
	.inputcont input {
		border: 1px solid #a0a0a0;
		padding: 5px;
	}
	.grouphead {
		background-color: #e2e2e2;
		overflow: hidden;
		padding: 5px;
		margin: 10px 0;
	}
	#mainheader{
		display:none;
	}
	
	#install{
		font-size: 16px;
		margin-top: 10px;
		padding: 5px 67px;
		background-color: #0c7ac2;
		color: #FFFFFF;
		border: 0;
		cursor:pointer;
	}
	#install:hover{
		opacity:.8;
	}
	</style>
	</head>
	<body>
		<?php require_once("admin/main-nav.php"); ?>
		<div id="main" class="installForm">
			<h1 style="margin:0;text-align:center;">ThriveCart Deep Data Integration &amp; Custom Success Pages</h1>
			<p id="notif">Please fill-up the form with correct information</p>
			
			<div class="grouphead">ThriveCart Account <?php tooltip(@$tooltips["ThriveCart Account"], "gray"); ?> <?php vidtutorial($video_tutorials["ThriveCart Account"], $tooltips["video icon"]); ?></div>
            <div class="inputcont">
                <label>Store ID <?php tooltip(@$tooltips["ThriveCart Store ID"], "gray"); ?></label>
                <input type="text" id="thrivecart_store_id" name="thrivecart_store_id" />
            </div>
			<div class="grouphead">License <?php tooltip(@$tooltips["License"], "gray"); ?> <?php vidtutorial($video_tutorials["License"], $tooltips["video icon"]); ?></div>
            <div class="inputcont">
                <label>Email <?php tooltip(@$tooltips["License Email"], "gray"); ?></label>
                <input type="text" id="email" name="email" />
            </div>
            <div class="inputcont">
                <label>Key <?php tooltip(@$tooltips["License Key"], "gray"); ?></label>
                <input type="text" id="key" name="key" />
            </div>

			<div class="grouphead">Database Information <?php tooltip(@$tooltips["Database Information"], "gray"); ?> <?php vidtutorial($video_tutorials["Database Information"], $tooltips["video icon"]); ?></div>
			<div class="inputcont">
				<label>Host <?php tooltip(@$tooltips["Database Host"], "gray"); ?></label>
				<input type="text" id="dbhost" name="dbhost" value="localhost"/>
			</div>
			<div class="inputcont">
				<label>DB Name <?php tooltip(@$tooltips["Database Name"], "gray"); ?></label>
				<input type="text" id="dbname" name="dbname" />
			</div>
			<div class="inputcont">
				<label>Username <?php tooltip(@$tooltips["Database Username"], "gray"); ?></label>
				<input type="text" id="dbuser" name="dbuser" />
			</div>
			<div class="inputcont">
				<label>Password <?php tooltip(@$tooltips["Database Password"], "gray"); ?></label>
				<input type="text" id="dbpass" name="dbpass" />
			</div>
			<div class="grouphead">Create MySQL Table <?php tooltip(@$tooltips["MySQL Table"], "gray"); ?></div>
			<div class="inputcont">
				<label>Table Prefix <?php tooltip(@$tooltips["Table Prefix"], "gray"); ?></label>
				<span><input type="text" id="dbOrderPrefix" name="dbOrderPrefix" value="ctcp_" /></span>
			</div>
			<div class="grouphead">Admin Account <?php tooltip(@$tooltips["Admin Account"], "gray"); ?></div>
			<div class="inputcont">
				<label>Username <?php tooltip(@$tooltips["Admin Username"], "gray"); ?></label>
				<input type="text" id="username" name="username" />
			</div>
			<div class="inputcont">
				<label>Password <?php tooltip(@$tooltips["Admin Password"], "gray"); ?></label>
				<input type="text" id="userpass" name="userpass" />
			</div>
			<div class="inputcont">
				<label>&nbsp;</label>
				<input style="font-size:16px;margin-top:10px;" type="submit" id="install" name="install" value="Install" />
			</div>
		</div>
	</body>
</html>