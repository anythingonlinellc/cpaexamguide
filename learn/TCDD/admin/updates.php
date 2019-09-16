<?php
	if(!defined("THANKSTHRIVE"))die();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="common.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<?php include("main-nav.php"); ?>
		<div id="main">
			<h1 style="color:#3fa9f5;">Updates</h1>
			<div id="UpdatesList" style="height:600px;">
				<iframe src="//www.customthrivecartpages.com/license/iframe-updates.php" style="width: 100%; height: 100%; background-color: #ffffff; border: 0; border-radius: 10px;"/>
			</div>
		</div>
		<?php 
			$footer = str_replace("[YEAR]", Date("Y"), base64_decode($pages["footer"])); 
			echo $footer;
		?>
	</body>
</html>