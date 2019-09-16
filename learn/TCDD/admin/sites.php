<?php if(!defined("THANKSTHRIVE"))die(); ?>
<?php 
	$globalsettings = get_global_settings(); 
	if(!isset($globalsettings["multipleDeepDataIntegrationSettings"])) $globalsettings["multipleDeepDataIntegrationSettings"] = 1;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="common.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.15/r-2.1.1/rr-1.2.0/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.15/r-2.1.1/rr-1.2.0/datatables.min.js"></script>
		<script>
			jQuery(document).ready(function($){
				$.fn.dataTable.render.myControls = function(){
					return function(){
						return '<span class="control delete glyphicon glyphicon-trash" title="Delete"></span><span class="glyphicon glyphicon-duplicate control duplicate" title="Duplicate"></span><span class="control edit glyphicon glyphicon-cog" title="Settings"></span>';
					}
				};
				window.sites_table = $("#sites-list").DataTable({
					ajax: "?action=sites-list",
					"oLanguage": {
						"sEmptyTable": "Click “Add New” above to create your first custom success page."
					},
					rowId: 'id',
					columns: [
						{ data: "name" },
						{ data: "membership", render: function(param){if(param==0)return "";else return param;} },
						{ data: "controls", render: $.fn.dataTable.render.myControls() }
					],
					columnDefs: [
						{"className": "dt-right", 
						 "targets": 2} ],
					"order": [[0, "asc"]], "pageLength": 50
				});
				
				$("body").on("click", ".control.edit", function(){
					var id = $(this).closest("tr").attr("id");
					window.location.href = "?action=edit-site&id="+id;
				});
				
				$("body").on("click", ".control.duplicate", function(){
					var id = $(this).closest("tr").attr("id");
					$(".tmodal#duplicate #siteid").val(id);
					$(".tmodal#duplicate").show();
				});
				
				$("body").on("click", ".control.delete", function(){
					var id = $(this).closest("tr").attr("id");
					if(confirm("Are You Sure Want to Delete This Deep Data Integration Settings?")){
						// console.log("clicked Yes");
						$.post("?action=site-delete", {siteid:id}, function(r){
							 window.sites_table.ajax.reload();
						});
					}else{
						// console.log("clicked No");
					}
				});
				
				$("body").on("click", ".tmodal#duplicate #submitAddSite", function(){
					var sid = parseInt($(".tmodal#duplicate #siteid").val());
					var sitename = $(".tmodal#duplicate #siteDomain").val();
					if(sitename == "" || sid <=0 ) return false;
					
					$(".tmodal#duplicate .panel").hide();
					$.post("?action=site-duplicate", {siteid:sid, sitename:sitename}, function(r){
						var sid = parseInt(r);
						// console.log(r);
						window.location.href = "?action=edit-site&id="+sid;
					});
				});
				
				$("body").on("click", "#addSite", function(){
					$(".modal#createnew").show();
				});
				
				$("body").on("click", ".modal#createnew #submitAddSite", function(){
					$(".modal#createnew .panel").hide();
					var sitename = $(".modal#createnew #siteDomain").val();
					$.post("?action=site-add", {sitename:sitename}, function(r){
						// console.log(r);
						window.location.href = "?action=edit-site&id="+r;
					});
				});
				
				$("body").on("click", ".modal#createnew", function(e){
					if(e.target == this)$(this).hide();
				});
				
				$("body").on("change", "#enableMultipleSettings", function(){
					if($(this).is(":checked")){
						// Save Global Settings. Enable
						$.post("?action=site-multiple", {enable:1}, function(r){
							// show add new button
							$("#addSite, .control.duplicate").show();
							console.log("enable", r);
						});
					}else{
						if(window.sites_table.data().count() <= 1){
							// Save Global Settings. Disable
							$.post("?action=site-multiple", {enable:0}, function(r){
								console.log("disable", r);
							});
							if(!window.sites_table.data().count()){
								// Show add new button
								$("#addSite, .control.duplicate").show();
							}else{
								// Hide add new button
								$("#addSite, .control.duplicate").hide();
							}
						}else{
							$(this).attr("checked");
							// Show add new button
							$("#addSite, .control.duplicate").show();
						}
					}
				});
			});
		</script>
		<style>
		html{
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 14px;
		}
		button.addnew{
			background-color: #57ab57;
			border: 0;
			padding: 5px 10px;
			margin-left: 15px;
			color: #ffffff;
			border-radius: 3px;
			cursor: pointer;
		}
		
		button.addnew:hover{
			background-color: #5cb85c;
		}
		
		.modal {
			position: absolute;
			z-index: 1000;
			top: 0;
			left: 0;
			background: rgba(0,0,0,.5) url(tiny-loader.gif) no-repeat top;
			width: 100%;
			height: 100%;
			display: none;
			background-position-y: 30px;
		}
		
		.modal .panel{
			background-color : #ffffff;
			margin:0 auto;
			margin-top:	20px;
			width: 400px;
			padding:20px;
		}
		
		.field label {
			width: 78px;
			display: inline-block;
		}
		.field {
			padding: 5px;
		}
		
		#sites-list tbody tr:nth-child(even){
			background-color:#f5f5f5;
		}
		
		#sites-list tbody tr:hover{
			background-color:#ececec;
		}
		
		#sites-list thead tr{
			background-color: #f5f5f5;
			text-align: left;
			height: 50px;
		}
		
		#sites-list .control{
			float: right;
			width: 25px;
			height: 25px;
			display: inline-block;
			overflow: hidden;
			border: 1px solid #d2d2d2;
			border-radius: 20px;
			line-height: 25px;
			text-align: center;
			margin: 1px 2px;
			cursor: pointer;
			background-color: #f3f3f3;
			font-size: 13px;
			-webkit-transition: background-color .2s; /* Safari */
			transition: background-color .2s;
		}
		#sites-list .control:hover{
			background-color:#b8dfff;
		}
		#sites-list .control.edit{
			color:orange;
		}
		#sites-list .control.duplicate{
			color:#5e95bd;
		}
		#sites-list .control.delete{
			color:red;
		}
		#sites-list_length.dataTables_length{
			margin-bottom:10px;
		}
		#tablecont{
			padding: 10px;
			border-radius: 5px;
			overflow-x: auto;
		}
		h1 {
			color: #3fa9f5;
		}
		<?php if($globalsettings["multipleDeepDataIntegrationSettings"] == 0){ ?>
		.control.duplicate{display:none!important;}
		<?php }?>
		</style>
	</head>
	<body>
		<?php include("main-nav.php"); ?>
		<div id="main">
			<h1>Deep Data Integrations<button class="addnew" id="addSite" style="<?php echo (@$globalsettings["multipleDeepDataIntegrationSettings"] == 1?"":"display:none;") ; ?>">Add New</button> <span class="tooltip blue" title="<?php echo $tooltips["Add New Success Pages"]; ?>">i</span> <div class="vidtutorial" data-vid="<?php echo $video_tutorials["Add New Success Pages"]["vidurl"]; ?>" data-title="<?php echo $video_tutorials["Add New Success Pages"]["title"]; ?>" data-desc="<?php echo $video_tutorials["Add New Success Pages"]["description"]; ?>" title="<?php echo $tooltips["video icon"]; ?>"><span class="box"></span><span class="lense" ></span></div></h1>
			
			<p><label for="enableMultipleSettings"><input type="checkbox" name="enableMultipleSettings" id="enableMultipleSettings" value="true" <?php echo (@$globalsettings["multipleDeepDataIntegrationSettings"] == 1?"checked":"") ; ?>/>Enable Multiple Deep Data Integration Settings <?php tooltip(@$tooltips["Enable Multiple Deep Data Integration Settings"], "blue"); ?></label></p>
			<div id="tablecont">
				<table class="table" id="sites-list">
					<thead>
						<th>Name</th>
						<!-- th>Wordpress</th -->
						<th>Membership</th>
						<th>Controls</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
		<?php 
			$footer = str_replace("[YEAR]", Date("Y"), base64_decode($pages["footer"])); 
			echo $footer;
		?>
		<div class="modal" id="createnew">
			<div class="panel">
				<h2>Add New Deep Data Integration <?php tooltip($tooltips["Add ThriveCart"], "blue"); ?> <?php vidtutorial($video_tutorials["Add New Success Pages"], $tooltips["video icon"]); ?></h2>
				<div class="content">
					<div class="field">
						<label style="width:150px;">Name <span class="tooltip blue" title="<?php echo @$tooltips["Success Page Name"]; ?>">i</span></label>
						<input type="text" style="width:300px;" name="doamin" id="siteDomain" value="" />
					</div>
					<div class="field">
						<label>&nbsp;</label>
						<button class="addnew" id="submitAddSite" style="float:right;margin-right:4px;">Add</button>
					</div>
				</div>
			</div>
		</div>
		<div class="tmodal" id="duplicate">
			<div class="panel">
				<h2>Duplicate Settings to Create New Deep Data Integration. <?php tooltip(@$tooltips["Duplicate ThriveCart"], "blue"); ?> <?php vidtutorial($video_tutorials["Duplicate ThriveCart"], $tooltips["video icon"]); ?></h2>
				<div class="content">
					<div class="field">
						<label style="width:150px;">Name <span class="tooltip blue" title="<?php echo @$tooltips["Success Page Name"]; ?>">i</span></label>
						<input type="text" style="width:calc(100% - 75px);" name="doamin" id="siteDomain" value="" />
						<input type="hidden" name="siteid" id="siteid" value="" />
					</div>
					<div class="field">
						<label>&nbsp;</label>
						<button class="modalclose">Cancel</button>
						<button class="addnew" id="submitAddSite" style="float:right; margin:20px 0;">Create</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>