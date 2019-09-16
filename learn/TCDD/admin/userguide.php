<?php
	if(!defined("THANKSTHRIVE"))die();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="common.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
			jQuery(document).ready(function($){
				$("body").on("click", "#mnave li", function(){
					var id = $(this).attr("data-id");
					$("#contents > div").removeClass("active");
					$("#contents #"+id).addClass("active");
					$("#mnave li").removeClass("active");
					$(this).addClass("active");
				});
			});
		</script>
		<style>
			html{
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 14px;
			}
			h1#mh{color:#337ab7;}
			#mnave{
				float: left;
				margin: 0;
				padding: 20px 0;
				background-color: #F0F0F0;
				width: 225px;
				margin-right: 15px;
			}
			#mnave li {
				display: block;
				width: 180px;
				padding: 10px;
				margin: 0 auto;
				color: #337ab7;
				cursor:pointer;
				border-radius : 5px;
			}
			
			#mnave li.active, #mnave li:hover {
				background-color: #337ab7;
				color:#ffffff;
			}
			#contents{
				float:left;
				width: calc(100% - 300px);
				padding: 20px;
				background-color: #F0F0F0;
			}
			#contents > div{
				display:none;
			}
			#contents > div.active {
				display:block;
			}
			code{
				color:red;
			}
			
			@media only screen and (max-width : 800px) {
				#mnave, #contents{
					float: none;
					clear:both;
				}
				#contents{
					width:100%;
				}
			}
		</style>
	</head>
	<body>
		<?php include("main-nav.php"); ?>
		<?php echo base64_decode($pages["userguide"]); ?>
		<?php 
			$footer = str_replace("[YEAR]", Date("Y"), base64_decode($pages["footer"])); 
			echo $footer;
		?>
	</body>
</html>




















