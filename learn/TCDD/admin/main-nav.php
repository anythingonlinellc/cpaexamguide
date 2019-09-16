<?php if(!defined("THANKSTHRIVE"))die(); ?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
if(typeof jQuery == "undefined"){
	var tag = document.createElement('script');
	tag.src = "//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	tag.onload = function() {
		var $ = jQuery;
		clipboard_textarea($);
		$( document ).tooltip();
	};
}else{
	jQuery(document).ready(function($){
		clipboard_textarea($);
		$( document ).tooltip();
		$("body").on("click", "#feature-request", function(){			
			jQuery("#featureRequestForm .panel#frform").show();
			jQuery("#featureRequestForm .panel#frNotif").hide();
			jQuery("#featureRequestForm").show();
			return false;
		});
		$("body").on("click", "#weebhookurl", function(){
			jQuery("#webhookmodal").show();
			return false;
		});
		$("body").on("click", ".modalclose", function(){
			jQuery(this).closest(".tmodal").hide();
			jQuery("html").removeClass("withModal");
		});
		
		$("body").on("click", "#vidtutorialpopup .modalclose", function(){
			jQuery(".tmodal#vidtutorialpopup iframe").replaceWith($("<iframe/>"));
		});
		$("body").on("click", ".vidtutorial", function(){
			var vid = $(this).attr("data-vid");
			var title = $(this).attr("data-title");
			var desc = $(this).attr("data-desc");
			jQuery(".tmodal#vidtutorialpopup h2").text(title);
			jQuery(".tmodal#vidtutorialpopup p.desc").text(desc);
			var nvid = $("<iframe/>");
			$(nvid).attr("src", vid);
			$(nvid).attr("width", "480");
			$(nvid).attr("height", "270");
			$(nvid).attr("frameborder", "0");
			$(nvid).attr("allowfullscreen", "1");
			jQuery(".tmodal#vidtutorialpopup iframe").replaceWith(nvid);
			jQuery(".tmodal#vidtutorialpopup").show();
			jQuery("html").addClass("withModal");
		});

        $("body").on("click", "#submitfeaturerequest", function(){
            var feature = $("#featureRequestForm textarea[name='feature']").val();
            if(feature == "") return false;
            $.post("?action=feature-request",{feature:feature}, function(r){
                try{
                    var res = JSON.parse(r);
					console.log(res);
                    if(res.result == true){
                        console.log("SUCCESS");
						$("#featureRequestForm textarea[name='feature']").val("");
						jQuery("#featureRequestForm .panel#frNotif div#msg").html("<p>Feature Request Successfully Sent.</p>");
                    }else{
						jQuery("#featureRequestForm .panel#frNotif div#msg").html("<p>Failed to submit your Feature Request!</p>");
					}
					jQuery("#featureRequestForm .panel#frform").hide();
					jQuery("#featureRequestForm .panel#frNotif").show();
                }catch(e){console.log(e);}
            });
        });
		<?php if(@$_GET["action"] != "updates"){ ?>
		$.post("?action=latest-updates",{},function(r){
			var lu = false;
			try{
				lu = JSON.parse(r);
			}
			catch(err){}
			
			if(!lu) return false;
			var latestUpdate = JSON.parse(localStorage.getItem("latestUpdate"));
			if(!latestUpdate || latestUpdate.id != lu.id){
				$("#newUpdates #subject a").html(lu.subject+" <span>[READ MORE]<span>");
				$("#newUpdates #date").html(lu.date);
				$("#newUpdates").show();
				localStorage.setItem("shownUpdate", JSON.stringify(lu));
			}
		});
		$("body").on("click", "#newUpdates #hideUpdate", function(){
			localStorage.setItem("latestUpdate",localStorage.getItem("shownUpdate"));
			$("#newUpdates").fadeOut();
		});
		<?php } ?>
	});
}

function clipboard_textarea($){
	jQuery(".clipboard_textarea").each(function(i,v){
		var text = jQuery(this).attr("text");
		var position = jQuery(this).attr("position");
		var width = (jQuery(this).attr("width")?jQuery(this).attr("width"):"");
		var height = (jQuery(this).attr("height")?jQuery(this).attr("height"):"");
		var button = jQuery(this).attr("button");
		jQuery(this).attr("id","clipboard_textarea_"+i);
		var txa = jQuery("<textarea>");
		jQuery(this).html(txa);
		if(position == "left") jQuery(this).css({float:"left"});
		if(position == "right") jQuery(this).css({float:"right"});
		if(position == "center") jQuery(this).css({display:"block", margin :"0 auto"});
		console.log(width);
		jQuery(this).css({"width":width, "height":height});
		jQuery("textarea",this).css({"width":width, "height":height});
		jQuery("textarea",this).text(text.replace(/{break}/g,"\n\r"));
		jQuery("textarea",this).attr("readonly","Readonly");
		var button = '<input type="button" class="btn_clipboard_textarea" value="'+button+'" style="margin-top:5px;"/>';
		jQuery(this).append(button);
	});
	
	jQuery("body").on("click", ".btn_clipboard_textarea", function(){
		var tid = jQuery(this).closest(".clipboard_textarea").attr("id");
		activepages_copy_text(tid);
	})
}

function activepages_copy_text(id){
	var copyTextarea =  document.querySelector("#"+id+" textarea");
	copyTextarea.select();

	try {
		var successful = document.execCommand("copy");
		if(successful){
			alert("Copied to Clipboard");
			jQuery("#webhookmodal").hide();
		}
	} catch (err) {
		console.log("Oops, unable to copy");
	}
}
</script>
<div id="mainheader">
	<div id="logocont"><span><i class="logo glyphicon glyphicon-shopping-cart"></i> ThriveCart Deep Data Integration For ActiveCampaign<span style="font-size: 11px;color: #ffa500;margin-left: 5px;">v<?php echo getVersion(); ?></span></span>
		<div id="logoMenu"><a href="?action=updates">Updates</a><a href="?action=support">Support</a><a href="https://www.facebook.com/groups/thrivecartactivecampaign/" target="_balank">Facebook Group</a><a href="?action=logout">Logout</a></div>
	</div>
	<div id="menucont">
		<div id="mMenu">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
		<ul id="mainnav">
			<?php if(isset($_GET["action"])){ ?>
			<li class="item"><a href="?">Home</a></li>
			<?php }else{ ?> 
			<li class="item active"><a href="?">Home</a></li>
			<?php } if(@$_GET["action"] != "user-guide"){ ?>
			<li class="item"><a href="?action=user-guide">User Guide</a></li>
			<?php }else{ ?>
			<li class="item active"><a href="?action=user-guide">User Guide</a></li>
			<?php } ?>
			<li class="item"><a id="weebhookurl" href="#">Webhook URL</a></li>
			<li class="item"><a href="../downloads/custom_thrive_success_pages.zip" target="_blank">Download 'Thank You' files</a></li>
			<li class="item"><a href="../downloads/WPMemberExtendAPI.zip" target="_blank">Download 'WP Member Extend API' Plugin</a></li>
			<li class="item"><a id="feature-request" href="#">Feature Request</a></li>
		</ul>
	</div>
</div>
<div id="spacer">&nbsp;</div>

<div id="newUpdates">
	<p id="title">Latest Update</p>
	<p id="subject"><a href="?action=updates"></a></p>
	<p id="date"></p>
	<span id="hideUpdate">[HIDE]</span>
</div>	


<div class="tmodal" id="webhookmodal">
	<div class="panel">
		<h2>ThriveCart Webhook URL <?php tooltip($tooltips["webhook"], "blue"); ?> <?php vidtutorial($video_tutorials["webhook"], $tooltips["video icon"]); ?></h2>
		<div class="clipboard_textarea" text="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].str_replace("/admin/index.php","",$_SERVER["SCRIPT_NAME"])."/thanks-webhook.php"; ?>" position="center" width="400" height="80" button="Copy to clipboard"></div>
		<p class="note">Copy this Webhook URL and use this into your ThriveCart as the WebHook URL.</p>
		<button class="modalclose">Got it</button>
	</div>
</div>
<div class="tmodal" id="featureRequestForm">
	<div class="panel" id="frform">
		<h2>Feature Request <?php tooltip($tooltips["Feature Request"], "blue"); ?> <?php vidtutorial($video_tutorials["Feature Request"], $tooltips["video icon"]); ?></h2>
		<p class="desc"></p>
		<textarea style="width:100%;max-width:calc(100% - 20px);padding:10px;" rows="6" name="feature" placeholder="Describe The Feature You Want to Request"></textarea>
		<div>
			<button class="modalclose">Close</button>
			<button class="submit" id="submitfeaturerequest">Submit</button>
		</div>
	</div>
	<div class="panel" id="frNotif">
		<div id="msg"><p>Feature Request Successfully Sent.</p></div>
		<div>
			<button class="modalclose">Close</button>
		</div>
	</div>
</div>
<div class="tmodal" id="CreateThriveCartCustomFieldForm">
	<div class="panel">
		<h2>Create ThriveCart Custom Field <?php tooltip(@$tooltips["Create ThriveCart Custom Field"], "blue"); ?> <?php vidtutorial(@$video_tutorials["Create ThriveCart Custom Field"], $tooltips["video icon"]); ?></h2>
		<div class="field">
			<label>Custom Field <?php tooltip(@$tooltips["ThriveCart Custom Field"], "blue"); ?></label>
			<input type="text" id="ThriveCartCustomField" name="ThriveCartCustomField" placeholder="ThriveCart Custom Field" />
		</div>
		<div>
			<button class="modalclose">Close</button>
			<button class="submit" id="CreateThriveCartCustomField">Create</button>
		</div>
	</div>
</div>
<div class="tmodal" id="viewOrderDetials">
	<div class="panel" style="max-width:1100px;">
		<h3>Order Details</h3>
		<div id="details">
			<div class="row">
				<div class="section"><div class="heading">Order Info</div>
					<div id="accountinfo"></div>
				</div>
				<div class="section"><div class="heading">Customer</div>
					<div id="customerinfo"></div>
				</div>
			</div>
			<div class="row">
				<div class="section wide"><div class="heading">Orders</div>
					<table id="ordersinfo">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Type</th>
								<th>Price</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="section wide"><div class="heading">Subscription History</div>
					<table id="historyinfo">
						<thead>
							<tr>
								<th>Type</th>
								<th>Amount</th>
								<th>Frequency</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<div>
			<button class="modalclose">Got It</button>
		</div>
	</div>
</div>
<div class="tmodal" id="vidtutorialpopup">
	<div class="panel">
		<h2>Tutorial</h2>
		<p class="desc"></p>
		<iframe width="480" src="" frameborder="0" allowfullscreen></iframe>
		<span class="modalclose">Got it</span>
	</div>
</div>
