<?php
	define("THANKSTHRIVE", true);
	$ref = @$_REQUEST['ref'];

	/* Verify the hash matches the data provided */
	$thrivecart = $_GET['thrivecart'];

	extract($thrivecart);
	extract($customer);
	
	$product_ids = array();
	foreach($order as $o){
		$product_ids[] = $o["id"];
	}
	

	/* get email */
	$url_email = strtolower($email);
	$license_email = $email = (isset($_COOKIE['stored-email']) && $_COOKIE['stored-email'] != "" ?$_COOKIE['stored-email']:$url_email);
	$currency = $_GET["thrivecart"]["order_currency"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Thanks for Your Order</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script>
		var remote = "//www.cpaexamguide.com/learn/TCDD/";
		var currency = "<?php echo $currency; ?>";
		var gfirstname = "<?php echo $firstname; ?>";
		var gemail = "<?php echo $email; ?>";
		var gref = "<?php echo $ref; ?>";
		var gorder_total = "<?php echo floatval((isset($order_total)?$order_total:$order["total"]))/100; ?>";
		var hideLoginUrl = false;
	</script>
	<script src="//www.cpaexamguide.com/learn/TCDD/thanks.js"></script>
	<style>
		/* 
			******* TO easily generate colors, go to this URL ******
			https://www.google.com.ph/search?q=colorpicker&oq=colorpicker&aqs=chrome..69i57j0l5.2648j0j7&sourceid=chrome&ie=UTF-8 
		*/
		
		html,body{width: 100%;margin:0;padding:0;}
		body{
			
			/* --- Main BackGround Color --- */
			 background-color: #ffffff;
		}

		#main_container{
			width: calc(100% - 40px);
			max-width: 835px;
			margin: 0 auto;
			padding: 0 20px 20px 20px;
			
			
			/* --- General Font Family --- */
			font-family: "Lato", Helvetica, sans-serif;
			
			
			/* --- Page BackGround Color --- */
			background-color: #ffffff; 
			
			
			/* --- Page Top Border --- */
			border-top-color : #ffffff;
			border-top-width: 5px;
			border-top-style: solid;
				
				
			/* --- Page Bottom Border --- */
			border-bottom-color : #ffffff;
			border-bottom-width: 5px;
			border-bottom-style: solid;
			
			
			/* --- Page Left Border --- */
			border-left-color : #00000;
			border-left-width: 0px;
			border-left-style: solid;
			
			
			/* --- Page Right Border --- */
			border-right-color : #00000;
			border-right-width: 0px;
			border-right-style: solid;
		}

		h1{
			/* --- Page Heading Text Color --- */
			color: #183c63;
			
			
			text-align: center;
		}
		
		
		div#ordercont{
			padding: 20px;
			
			/* --- Receipt Background  Color --- */
			background-color: #ffffff;
			
			
			/* --- Receipt Border Style. Posibble values : dashed, dotted, solid --- */
			border-style: dashed; 
			
			
			/* --- Receipt Border Color --- */
			border-color: #000000;
			
			
			/* --- Receipt Font Family --- */
			font-family: "Lato", Helvetica, sans-serif;
			
			
			/* --- Receipt Font size --- */
			font-size: 16px;
			
			/* --- Receipt Border Thickness --- */
			border-width: 2px;
			
			/* --- Receipt Border Round Corners --- */
			border-radius: 8px;
			
			
			margin-top: 50px;
		}

		div#customer {			
			/* --- Customer Info Text Color --- */
			color: #000000;
			
			margin-bottom: 20px;
		}

		div#customer span {
			display: block;
			text-align: right;
			line-height: 25px;
		}
		
		#products_table{
			width: 100%;
		}

		#products_table th {
			/* --- Receipt Table Heading Background Color --- */
			background-color: #ffffff;
			
			
			/* --- Receipt Table Heading Text Color --- */
			color: #000000;
			
			
			/* ---  Receipt Table Heading Font Size --- */
			font-size: 16px;
			
			
			padding: 5px 10px;
		}

		#products_table .aleft{
			text-align:left;
		}

		#products_table .aright{
			text-align:right;
			white-space: nowrap;
		}

		#products_table td {
			border-bottom: 1px dotted rgba(24, 43, 74, 0.28);
			padding: 5px 10px;
		}
		
		#products_table td , #products_table td a{
			
			/* --- Receipt Table Row Text Color --- */
			color: #000000;
			
			
			/* ---  Receipt Table Row Font Size --- */
			font-size: 16px;
		}

		#footer{
			/* --- Footer Text Color --- */
			color: #000000;
			
			
			/* ---  Footer Font Size --- */
			font-size: 16px;
			
			
			text-align:center;
			margin-top: 30px;
		}
		
		p#thelicense span#l span {
			background-color: #d1e5d1;
			display: block;
			padding: 5px;
			margin: 5px;
		}
		
		#print{
			margin-top: -50px;
			display: block;
			margin-bottom: 25px;
			margin-left: -15px;
		}
		
		.hideColumnType th:nth-child(2), .hideColumnType tr td:nth-child(2){
			display:none;
		}
		
		@media print {
			#print{
				display:none;
			}
			#main_container{
				border:0;
			}
			body > *, #main_container > *, #main_container #orderdetails > *{
				display:none;
			}
			
			#main_container, #main_container #orderdetails, #main_container #orderdetails #ordercont{
				display:block!important;
			}
		}
	</style>
	
	
	<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '446167432203578');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=446167432203578&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

	
	
</head>
<body>
<script>
  fbq('track', 'Purchase', {
    value: 697,
    currency: 'USD'
  });
</script>

	

	
<div id="main_container">
<!----------------------------------->
<!--- You can customize this text --->
<!----------------------------------->
<h1>You Are In <?php echo ucfirst("$firstname"); ?>! Your Training Starts Below:</h1>
	
	<?php if($affiliate_id == "yaeger"): // If affiliate id is bigrabbit ?>
<!-- <p>say this</p> -->
<?php elseif($affiliate_id == "bigpig"): // If affiliate id is bigpig ?>
<!-- <p>say this</p> -->
<?php else: // If affiliate id is not bigrabbit or bigpig ?>
<!-- <p>say this</p> -->
<?php endif; ?>
	
	<script src="https://fast.wistia.com/embed/medias/hqherboyuk.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_hqherboyuk videoFoam=true" style="height:100%;position:relative;width:100%"><div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;"><img src="https://fast.wistia.com/embed/medias/hqherboyuk/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" onload="this.parentNode.style.opacity=1;" /></div></div></div></div>
	
		<!----------------------------------->
		<!----------------------------------->
		<!-- DO Not Edit Within This Block -->
		<!----------------------------------->
		<!----------------------------------->
			<div id="loadingdetails">Loading...</div>
			<div id="orderdetails">
				<p id="theloginurl"></p>
				<p id="theunam"><b>Username :</b></p>
				<p id="thepass"><b>Password :</b> <span id="password">"Your Original Password"</span> <span id="resetpass" style="color:blue;text-decoration:underline;cursor:pointer;">If you can't remember or find it, click here to generate a new one</span></p>
				<p id="thelicense"></p>
				<div id="ordercont">
					<button id="print" onClick="window.print();">Print</button>
					<div id="customer"></div>
					<table id="products_table">
						<thead>
							<th class="aleft">Product Name</th>
							<th class="aleft" style="width:100px;">Type</th>
							<th class="aright" style="min-width:100px;">Price</th>
						</thead>
						<tr id="thetotal">
							<td class="aright">&nbsp;</td>
							<td class="aright">&nbsp;</td>
							<td class="aright"><b>Total</b>&nbsp;&nbsp;<?php echo number_format(floatval($order_total)/100, 2)." ".$currency; ?></td>
						</tr>
					</table>
				</div>
			</div>
		<!----------------------------------->
		<!----------------------------------->
		<!---------- END of Block ----------->
		<!----------------------------------->
		<!----------------------------------->
		
		
	<div id="footer">
		<b>
			<!----------------------------------->
			<!----------------------------------->
			<!--- You can customize texts here -->
			<!----------------------------------->
			<!----------------------------------->
			&copy; Copyright 2019+. Anything Online LLC. All Rights Reserved.
			
		</b>
	</div>
</div>
	

<!--PROOF PIXEL--><script src="https://cdn.useproof.com/proof.js?acc=zI2OsuoAtKeMD2hFFhsmAcEBkKi1" async></script><!--END PROOF PIXEL-->	
<!--Mouseflow-->	
	<script type="text/javascript">
    window._mfq = window._mfq || [];
    (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript"; mf.async = true;
        mf.src = "//cdn.mouseflow.com/projects/5c63bca4-b6b3-4a76-a35e-44f12d92cbca.js";
        document.getElementsByTagName("head")[0].appendChild(mf);
    })();
</script>

	
</body>
</html>