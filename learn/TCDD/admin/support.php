<?php
	if(!defined("THANKSTHRIVE"))die();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="common.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<style>
			button.submit{
				background-color: #24c9dc;
				border: none;
				border-radius: 3px;
				padding: 5px 10px;
				color: #ffffff;
				cursor:pointer;
			}
			button.submit:hover{
				opacity : .8;
			}
			
			.issue .subject{
				color:blue;
				font-weight:bold;
				text-decoration: underline;
				cursor:pointer;
			}
			.issue .subject span:first-child{
				color:#909090;
				font-size : 11px;
				font-weight: normal;
				text-decoration: none;
			}
			
			.issue .subject span:last-child{
				font-size : 10px;
			}
			
			.issue .subject span:last-child i{
				font-size: 17px;
				position: relative;
				color: #fb964b;
			}
			
			.issue .subject span:last-child i span{				
				position: absolute;
				display: block;
				left: 0;
				top: 0;
				width: 100%;
				text-align: center;
				color: #FFFFFF;
				font-size: 9px!important;
				font-weight: bold;
			}
			
			.conversation{			
				max-width: 900px;
				margin: 0 auto;
				background-color: #f7f7f7;
				padding: 20px;
				display: block;
				overflow: hidden;
			}
			
			.conversation .mine, .conversation .yours{
				max-width: calc(100% - 70px);
				border-radius: 5px;
				padding: 10px;
				position:relative;
				margin-top: 35px;
				margin-bottom: 20px;
				min-width: 145px;
				clear:both;
			}
			
			.conversation .mine{
				float: right;
				background-color: #d9f8fb;
				text-align: right;
			}
			
			.conversation .yours{
				float: left;
				background-color: #d9e7fb;
				text-align: left;
			}
			
			.conversation .who, .conversation .when{
				position: absolute;
				top: -32px;
				display: block;
				width: calc(100% - 20px);
			}
			
			.conversation .mine .who, .conversation .mine .when{
				text-align: right;
			}
			
			.conversation .yours .who, .conversation .yours .when{
				text-align: left;
			}
			
			.conversation .yours .when, .conversation .mine .when{
				top: -15px;
				color: #999898;
				font-size: 11px;
			}
			
			.conversation #description .when, .conversation #description .who{
				display:none;
			}
			.conversation #description{
				margin-top: 0;
				float: none;
				text-align: left;
				background-color: #fefefe;
			}
			
			.replyform{
				max-width: 500px;
				width:100%;
				margin:0 auto;
				margin-top:30px;
				clear:both;
			}
			.replyform textarea{
				width:100%;
				padding:10px;
			}
		</style>
		<script>
			jQuery(document).ready(function($){
				$("body").on("click", ".issue .submitreply", function(){
					var obj = $(this).closest(".issue");
					var details = $("textarea.details",obj).val();
					var issue = JSON.parse(details);
					var reply = $("textarea[name='reply']",obj).val();
					var data = {
						issueid : issue.id,
						reply : reply
					};
					$.post("?action=reply-issue", data, function(r){
						console.log(r);
						var res = false;
						try{
							res = JSON.parse(r);
						}catch(e){
							console.log(e);
						}
						if(res.result == 1){
							notify("Reply Successfully Submitted.");
						}else{
							notify("Failed to Submit Your Reply. Please try again!");
						}
						viewIssue(res.id);
					});
				});
				
				$("body").on("click", ".issue .subject", function(){
					var obj = $(this).parent();
					if($(".conversation",obj).is("div")){
						$(".conversation",obj).remove();
					}else{
						var details = $("textarea.details",obj).val();
						console.log(details);
						var issue = JSON.parse(details);			
						viewIssue(issue.id);
					}
				});
				
				function viewIssue(id){
					var obj = $(".issue[data-id='"+id+"']");
					var details = $("textarea.details",obj).val();
					console.log(details);
					var issue = JSON.parse(details);
					$(".conversation", obj).remove();
					$(obj).append("<div class=\"conversation\"><div class=\"mine\" id=\"description\"><span class=\"who\">"+issue.support_name+"</span><span class=\"when\">"+issue.date+"</span>"+issue.issue+"</div></div>");
					load_conversation(issue.id);
				}
				
				function load_conversation(issueid){
					var data = {
						issueid : issueid
					};
					console.log(data);
					$.post("?action=conversation-issue", data, function(r){
						var res = false;
						try{
							res = JSON.parse(r);
						}catch(e){
							console.log(e);
						}
						
						var licenseid = $(".issue[data-id='"+res.id+"']").attr("data-licenseid");
						if(res){
							for(var c in res.conversation){
								var con = res.conversation[c];
								var ic = "yours";
								if(licenseid == con.license_id)ic = "mine";
								$(".issue[data-id='"+res.id+"'] .conversation").append("<div class=\""+ic+"\"><span class=\"who\">"+con.support_name+"</span><span class=\"when\">"+con.date+"</span>"+con.issue+"</div>");
							}
							$(".issue[data-id='"+res.id+"'] .conversation").append("<div class=\"replyform\"><textarea name=\"reply\" placeholder=\"Your Reply Here\" rows=\"5\"></textarea><button class=\"submit submitreply\">Submit Reply</button></div>");
						}
					});
				}
				
				$("body").on("click", "#newissue", function(){
					var data = {
						name 	: $("input[name='name']").val(),
						email 	: $("input[name='email']").val(),
						subject : $("input[name='subject']").val(),
						issue	: $("textarea[name='description']").val()
					};
					$.post("?action=new-issue", data, function(r){
						console.log(r);
						var res = false;
						try{
							var r = JSON.parse(r);
							if(r.result==1)res = true;
						}catch(e){
							console.log(e);
						}
						
						if(res === true){
							$("select[name='type'] option").removeAttr("selected");
							$("select[name='type'] option[value='']").attr("selected", "Selected");
							$("textarea[name='description']").val("");
							notify("Issue Successfully Submitted.");
						}else{
							notify("Failed to sumbit you Issue. Please Try again!.");
						}
						location.href=location.href;
					});
				});
			});
			
			function notify(msg){
				jQuery("#saveProgressNotif").html(msg).css("right", "-150px").show().stop().animate({right:0},200);
				setTimeout(function(){
					jQuery("#saveProgressNotif").fadeOut(200,function(){$(this).hide().css({opacity:1})});
				}, 3000);
			}
		</script>
	</head>
	<body>
		<?php include("main-nav.php"); ?>
		<?php
		$issues = $sites->listIssues();
		?>
		<div id="main">
			<h1 class="bheading">Support</h1>
			<div id="supportList" style="min-height:400px;">
				<p><b>Enter You Support Issue Here:</b></p>
				<p><input type="text" name="name" placeholder="Enter Your Name" value="<?php echo @$issues[0]["support_name"]; ?>" style="width:250px;" /></p>
				<p><input type="text" name="email" placeholder="Enter Your Best Email" value="<?php echo (@$issues[0]["support_email"]?$issues[0]["support_email"]:EMAIL); ?>" style="width:250px;" /></p>
				<p><input type="text" name="subject" style="width:100%; max-width:550px;" placeholder="Enter The Subject of Your Support Issue" /></p>
				<p><textarea name="description" placeholder="Describe youre support issue here." rows="5" style="width:100%; max-width:550px;"></textarea></p>
				<p><button class="submit" id="newissue">Submit Your Support Issue</button></p>
				<p><h2>Current Issue</h2></p>
				<?php if(isset($issues[0])): ?>
				<div class="issue" data-id="<?php echo $issues[0]["id"]; ?>" data-licenseid="<?php echo $issues[0]["license_id"]; ?>">
					<textarea class="details" style="display:none;"><?php echo json_encode($issues[0]); ?></textarea>
					<p class="subject"><span><?php echo date("m/d/Y", strtotime($issues[0]["date"])); ?></span> <?php echo $issues[0]["subject"]; ?> <span>[VIEW ISSUE] <?php if(intval($issues[0]["replies"])>0): ?><i class="glyphicon glyphicon-comment"><span><?php echo $issues[0]["replies"]; ?></span></i><?php endif; ?></span></p>
				</div>
				<?php endif; ?>
				<p><h2>Past Issues</h2></p>
				<?php 
				unset($issues[0]);
				foreach((array)$issues as $issue){ ?>
				<div class="issue" data-id="<?php echo $issue["id"]; ?>" data-licenseid="<?php echo $issue["license_id"]; ?>">
					<textarea class="details" style="display:none;"><?php echo json_encode($issue); ?></textarea>
					<p class="subject"><span><?php echo date("m/d/Y", strtotime($issue["date"])); ?></span> <?php echo $issue["subject"]; ?> <span>[VIEW ISSUE] <?php if(intval($issue["replies"])>0): ?><i class="glyphicon glyphicon-comment"><span><?php echo $issue["replies"]; ?></span></i><?php endif; ?></span></p>
				</div>	
				<?php }?>
			</div>
		</div>
		<?php 
			$footer = str_replace("[YEAR]", Date("Y"), base64_decode($pages["footer"])); 
			echo $footer;
		?>
		<div id="saveProgressNotif">Issue Successfully Submitted.</div>
	</body>
</html>