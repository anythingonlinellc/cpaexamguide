<?php
	if(!defined("THANKSTHRIVE"))die();
	$s = $sites->getSite($_GET["id"]);
	$site = json_decode(@$s["details"], true);
	// print_r($site);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="tabs.css">
		<link rel="stylesheet" type="text/css" href="common.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.15/r-2.1.1/rr-1.2.0/datatables.min.css"/>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-2.2.4/dt-1.10.15/r-2.1.1/rr-1.2.0/datatables.min.js"></script>
		<script>
			var ACDATA;
			jQuery(document).ready(function($){
				loadACData();
				WP_connect();
				$("body").on("click", "#tabs li", function(){/* Switching tabs */
					$("#tabs li").removeClass("active");
					$(this).addClass("active");
					var id = $(this).attr("id");
					$("#bodycontent .tab").removeClass("active");
					$("#bodycontent .tab."+id).addClass("active");
					if(id == "activecampaign"){
						// loadACData();
					}else if(id == "products"){
						makeSiteWLM();
						loadWPData();
					}else if(id == "orders"){
						loadSiteOrders();
					}
					fixWidth($);
				});
				
				$("body").on("click", ".reloadCF", function(){
					$(".reloadCF").addClass("loadinggif");
					loadACData("finishReloadCF");
				});
				
				function finishReloadCF(acdata){
					$(".reloadCF").removeClass("loadinggif");
				}
							
				$("body").on("change", ".tab.apikeys #ActiveCampaign input[name='url'], .tab.apikeys #ActiveCampaign input[name='key']", function(){
					if($(".tab.apikeys #ActiveCampaign input[name='url']").val()!= "" || $(".tab.apikeys #ActiveCampaign input[name='key']").val() != ""){
						loadACData();
					}
				});
				
				/* $("body").on("change", ".tab.apikeys #ActiveCampaign input[name='key']", function(){
					$(".tab.apikeys #Membership input[name='ActiveMember360_API_Key']").val($(this).val());
				}); */	
				
				/* $("body").on("change", ".tab.apikeys #ThriveCart input[name='secert']", function(){
					$(".tab.apikeys #Membership input[name='Memberium_API_Key']").val($(this).val());
				}); */
				
				$("body").on("change", ".tab.apikeys #Membership select[name='plugin'], .tab.apikeys #Membership input[name='WP_Member_Extend_API_Key'], .tab.apikeys #Membership input[name='url']", function(){
					WP_connect();
				});
				
				function WP_connect(){
					$("#WPstatus").text("Status: Connecting...");
					$.post("?action=plugin-keys",{wp: $(".tab.apikeys #Membership input[name='url']").val(), wpmea_key: $(".tab.apikeys #Membership input[name='WP_Member_Extend_API_Key']").val()},function(r){
						console.log(r);
						try{
							var keys = JSON.parse(r);
							if(keys.hasOwnProperty("wishlist")){
								$(".tab.apikeys #Membership input[name='WishList_API_Key']").val(keys.wishlist);
							}else{
								$(".tab.apikeys #Membership input[name='WishList_API_Key']").val("");
							}
							if(keys.hasOwnProperty("acm360")){
								$(".tab.apikeys #Membership input[name='ActiveMember360_API_Key']").val(keys.acm360);
							}else{
								$(".tab.apikeys #Membership input[name='ActiveMember360_API_Key']").val("");
							}
							if(keys.hasOwnProperty("memberium")){
								$(".tab.apikeys #Membership input[name='Memberium_API_Key']").val(keys.memberium);
							}else{
								$(".tab.apikeys #Membership input[name='Memberium_API_Key']").val("");
							}
							
							if(!keys || (keys.hasOwnProperty("success") && keys.success == 0)){
								$("#WPstatus").html("Status: <b style='color:red;'>Not Connected</b>");
							}else{
								$("#WPstatus").html("Status: <b>Connected</b>");
							}
						}catch(e){
							$("#WPstatus").html("Status: <b style='color:red;'>Not Connected</b>");
						}
					});
				}
				
				$("body").on("change", ".tab input[type='checkbox']", function(){
					fixWidth($);
				});
				
				$("body").on("change", "select[name='plugin']", function(){
					var mplugin = $("option:selected",this).val();
					$(".wpplugin").hide();
					switch(mplugin){
						case "wishlist":
							$("input[name='WishList_API_Key']").parent().show();
						break;
						case "memberium":
							$("input[name='Memberium_API_Key']").parent().show();
						break;
						case "activemember360":
							$("input[name='ActiveMember360_API_Key']").parent().show();
						break;
					}
					makeSiteWLM();
					fixWidth($)
				});
				
				$("body").on("change", "#useActiveCampaign", function(){ /* Use or not use AC */
					if($(this).is(":checked")) $(".dontuseActiveCampaign").addClass("useActiveCampaign").removeClass("dontuseActiveCampaign");
					else $(".useActiveCampaign").addClass("dontuseActiveCampaign").removeClass("useActiveCampaign");
					fixWidth($);
				});
				
				$("body").on("click", "#addLists", function(){ /* Selecting of Lists */
					var lid = $(".tab.activecampaign select[name='Lists'] option:selected").val();
					if(lid == 0) return false;
					var lname = $(".tab.activecampaign select[name='Lists'] option:selected").text();
					var list = "<span class=\"siteLists\" id=\""+lid+"\">"+lname+"<span>x</span></span>";
					$("#addedLists").append(list);
					UpdateCustomFieldsOptions();
				});
				
				$("body").on("change", "#showAllCustomFields", UpdateCustomFieldsOptions);
				$("body").on("click", ".siteLists span", function(){
					$(this).parent().remove();
				});
				$("body").on("click", ".deltr", function(){
					$(this).closest("tr").remove();
				});
				
				$("body").on("click", "#CreateThriveCartCustomField", function(){ /* Create ThriveCart Custom Field */
					var new_custom_field = $("#ThriveCartCustomField").val();
					var theA = new_custom_field.split(".");
					var thekey = theA.length-1;
					new_custom_field = theA[thekey];
					if(new_custom_field != ""){
						// var slug = new_custom_field.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
						var slug = new_custom_field;
						new_custom_field = new_custom_field.replace(/[^\w- ]+/g,' ');
						var data = {siteid:$("#siteid").val(),cf:slug, label:new_custom_field};
						console.log(data);
						$.post("?action=site-addCustomerCustomFields", data, function(r){
							console.log("res",r);
							try{
								var ccf = JSON.parse(r);
								if($("#thrivecartfield select[name='TF'] option[value='"+ccf.slug+"']").val()){
									// Do nothing
								}else{								
									$("#thrivecartfield select[name='TF']").append("<option class='cf' value='"+ccf.slug+"'>"+ccf.label+"</option>");
								}
								$("#thrivecartfield select[name='TF'] option").removeAttr("selected");
								$("#thrivecartfield select[name='TF'] option[value='"+ccf.slug+"']").attr("selected", "Selected");
							}catch(e){}
						});
						$("#CreateThriveCartCustomFieldForm").hide();
						$("#ThriveCartCustomField").val("");
					}
				});
				$("body").on("change", "select[name='TF']", function(){ /* Popup ThriveCart Custom Field */
					if($("option:selected", this).val() == "create_custom_field"){
						$("#CreateThriveCartCustomFieldForm").show();
						$("option", this).removeAttr("selected");
						$("option[value='0']", this).attr("selected", "Selected");
						fixWidth($);
					};
				});
				
				$("body").on("click", ".assignCustomField", function(){ /* Assign Custom Fields*/
					var cont = $(this).parent();
					var t = $("select[name='TF'] option:selected", cont).val();
					var c = $("select[name='CF'] option:selected", cont).val();
					var tv = $("select[name='TF'] option:selected", cont).text();
					var cv = $("select[name='CF'] option:selected", cont).text();
					if(!c || !t || c == "0" || t == "0" || t == "create_custom_field") return false;
					$(".assignedCustomFields table tbody", cont).append("<tr><td data-val=\""+t+"\">"+tv+"</td><td data-val=\""+c+"\">"+cv+"</td><td><span class=\"deltr\">-</span></td></tr>");
				});
				
				function loadACData(callback){ /* Fetches Lists and Custom Fields*/
					var data = {url:$(".section#ActiveCampaign input[name='url']").val(),key:$(".section#ActiveCampaign input[name='key']").val()};
					console.log(data);
					$("#ACstatus").html("Status: Connecting...");
					$.post("?action=load-acdata",data, function(r){
						try{
							ACDATA = JSON.parse(r);
							if(ACDATA.result_code == 1){
								$("#ACstatus").html("Status: <b>Connected</b>");
							}else{
								$("#ACstatus").html("Status: <b style='color:red;'>Not Connected</b>");
							}
							console.log(ACDATA);
							getListsOptions();
							UpdateCustomFieldsOptions();
							if(callback)eval(callback+"(ACDATA);");
						}catch(e){
							$("#ACstatus").html("Status: <b style='color:red;'>Not Connected</b>");
						}
					});
				}
				
				$("body").on("click", "#vieworder", function(){
					var details = JSON.parse($(this).attr("data-details"));
					var full = JSON.parse($(this).attr("data-full"));
					if(typeof TDDI_DEBUG != "undefined"){
						console.log(details);
						console.log(full);
					}
					$("#viewOrderDetials #accountinfo").html("<p>Order ID: <b>"+details.order_id+"</b><p>Product: <b>"+fixAMP(details.purchases[0])+"</b></p><p>Account Name: "+details.thrivecart_account+"</p><p>Date: "+details.order_date+"</p><p style='text-transform: capitalize;'>Payment Processor: "+details.order.processor+"</p>");
					
					var line1 = "";
					var line2 = "";
					var sep = "";
					var caddress = "";
					var contactno = "";
					if(details.customer.hasOwnProperty("address")){
						if(details.customer.address.hasOwnProperty("city")){
							line2 += details.customer.address.city;
							sep = ", ";
						}
						if(details.customer.address.hasOwnProperty("zip")){
							line2 += sep+details.customer.address.zip;
							sep = ", ";
						}
						if(details.customer.address.hasOwnProperty("state") && details.customer.address.state != "Not applicable"){
							line2 += " "+details.customer.address.state;
							sep = ", ";
						}
						if(details.customer.address.hasOwnProperty("country")){
							line2 += sep+details.customer.address.country;
						}
						if(details.customer.address.hasOwnProperty("line1")){
							line1 = details.customer.address.line1;
						}
						if(details.customer.hasOwnProperty("contactno")){
							contactno = "<p>"+details.customer.contactno+"</p>";
						}
						caddress = "<p>"+line1+sep+line2+"</p>";
					}
					
						$("#viewOrderDetials #customerinfo").html("<p>ID: "+details.customer_id+"</p><p>"+details.customer.name+"</p><p>"+details.customer.email+"</p>"+contactno+caddress);
					
					var typeLabels = gather_Product_Type_Labels();
					$("#viewOrderDetials #ordersinfo tbody").html("");
					for(var i in details.order.charges){
						var p = details.order.charges[i];
						var pamount =(parseFloat(p.amount)?p.amount:0);
						p.amount = (pamount/100).toFixed(2);
						
						var pt = getProductType(p.reference, i, details.purchase_map);
						// console.log("pt", pt);
						// console.log("labels", typeLabels.labels);
						var tl = "";
						if(typeLabels.labels.hasOwnProperty(pt)){
							if(typeLabels.labels[pt] == ""){
								tl = pt;
							}else{
								tl = typeLabels.labels[pt];
							}
						}else{
							tl = pt;
						}
						
						$("#viewOrderDetials #ordersinfo tbody").append("<tr><td>"+p.reference+"</td><td>"+fixAMP(p.name)+"</td><td>"+tl+"</td><td class='talign-right'>"+p.amount+" "+details.currency+"</td></tr>");
					}
					var ccode = "";
					if(details.hasOwnProperty("coupon_code")) ccode = details.coupon_code;
					$("#viewOrderDetials #ordersinfo tbody").append("<tr><td>Coupon : "+ccode+"</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");
					$("#viewOrderDetials #ordersinfo tbody").append("<tr><td>&nbsp;</td><td>&nbsp;</td><td class='talign-right'><b>Total</b></td><td class='talign-right'><b>"+(parseFloat(details.order.total)/100).toFixed(2)+" "+details.currency+"</b></td></tr>");
					
					$("#viewOrderDetials #historyinfo tbody").html("");
					for(var a in details.update){
						try{
							var update = details.update[a];
							var type = "";
							var amount = "";
							var freq = "";
							if(update.event == "order.refund"){
								type = "Refund";
								update.refund.amount = (parseFloat(update.refund.amount)?update.refund.amount:0);
								amount = (parseFloat(update.refund.amount)/100).toFixed(2);
							}else if(update.event == "order.subscription_payment"){
								type = "Recurring";
								update.refund.amount = (parseFloat(update.subscription.amount)?update.subscription.amount:0);
								amount = (parseFloat(update.subscription.amount)/100).toFixed(2);
								for(x in details.order.charges){
									var c =  details.order.charges[x];
									if(c.reference == update.subscription.id && c.type == "recurring"){
										freq = c.frequency;
									}
								}
							}else if(update.event == "order.subscription_cancelled"){
								type = "Cancel";
							}
							$("#viewOrderDetials #historyinfo tbody").append("<tr><td>"+type+"</td><td class='talign-right'>"+amount+"<td style='text-transform:capitalize;'>"+freq+"</td></td><td>"+update.rec_date+"</td></tr>");
						}catch(e){}
					}
					$("#viewOrderDetials").show();
				});
				
				function getProductType(id, i, purchase_map){
					var pm = purchase_map[i];
					if(pm == "product-"+id) return "Main Product";
					else if(pm == "bump-"+id) return "Bump";
					else if(pm == "upsell-"+id) return "Upsell";
					else if(pm == "downsell-"+id) return "Downsell";
					else{
						for(var k in purchase_map){
							pm = purchase_map[k];
							if(pm == "product-"+id) return "Main Product";
							else if(pm == "bump-"+id) return "Bump";
							else if(pm == "upsell-"+id) return "Upsell";
							else if(pm == "downsell-"+id) return "Downsell";
						}
					}
				}
				
				$("body").on("click", ".tab.products #addProduct", function(){
					var pid = $(".tab.products input[name='id']").val();
					var pname = $(".tab.products input[name='name']").val();
					var wlm_id = $(".tab.products select[name='wlm_id'] option:selected").val();
					var dashboard = $(".tab.products select[name='dashboard'] option:selected").val();
					var is_bump = ($(".tab.products input[name='is_bump']").is(":checked")?"BUMP":"");
					var purl = "";
					
					if(dashboard == 0){
						purl = '<span class="title" style="display:none;"></span><span class="url">'+$(".tab.products input[name='url']").val()+"</span>";
					}else{
						purl = '<span class="title">'+$(".tab.products select[name='dashboard'] option:selected").text()+'</span><span class="url"  style="display:none;">'+$(".tab.products select[name='dashboard'] option:selected").val()+"</span>";
					}
					
					var wlm = '<span class="name">'+($(".tab.products select[name='wlm_id'] option:selected").val() !=""?$(".tab.products select[name='wlm_id'] option:selected").text():"")+'</span><span class="id"  style="display:none;">'+$(".tab.products select[name='wlm_id'] option:selected").val()+"</span>";
				

					if(pid == "") return false;
					$("#productslist tbody").append("<tr><td>"+pid+"</td><td>"+is_bump+"</td><td>"+pname+"</td><td>"+wlm+"</td><td>"+purl+"</td><td><span class=\"deltr\">-</span></td></tr>");
					
					$(".tab.products input[name='id']").val("");
					$(".tab.products input[name='name']").val("");
					$(".tab.products select[name='wlm_id'] option").removeAttr("selected");
					$(".tab.products select[name='dashboard'] option").removeAttr("selected");
					$(".tab.products input[name='url']").val("").closest(".field").show();
					$(".tab.products input[name='is_bump']").removeAttr("checked");
				});
				$("body").on("change", ".tab.products select[name='dashboard']", function(){
					if($("option:selected",this).val() == "0"){
						$(".tab.products input[name='url']").closest(".field").show();
					}else{
						$(".tab.products input[name='url']").closest(".field").hide();
					}
				});
				function loadWPData(){
					var apis = gather_API_tab_info();
					console.log(apis);
					if(!apis.Membership.url)return false;
					apis.Membership.url=apis.Membership.url.replace("https:","");
					apis.Membership.url=apis.Membership.url.replace("http:","");
					if(apis.Membership.WP_Member_Extend_API_Key){
						$.post(apis.Membership.url+"/wp-admin/admin-ajax.php?action=wpmea_getPosts", function(r){
							$(".tab.products select[name='dashboard']").html("");
							$(".tab.products select[name='dashboard']").append("<option value='0'>-- Input Custom URL --</option>");
							$(".tab.products input[name='url']").closest(".field").show();
							try{
								var posts = JSON.parse(r);
								// console.log(posts);
								for(var p in posts){
									var post = posts[p];
									$(".tab.products select[name='dashboard']").append('<option value="'+post.guid+'">'+post.post_title+'</option>');
								}
							}catch(e){}
						})
						.fail(function() {
							$(".tab.products select[name='dashboard']").html("");
							$(".tab.products select[name='dashboard']").append("<option value='0'></option>");
						});
					}
					
					if(apis.Membership.plugin == "wishlist" && apis.Membership.WishList_API_Key != ""){
						$.post("?action=wlm-levels", {url: apis.Membership.url, key: apis.Membership.WishList_API_Key}, function(r){
							console.log("whishlist",r);
							$(".tab.products select[name='wlm_id']").html("");
							$(".tab.products select[name='wlm_id']").append('<option value="">None</option>');
							try{
								var levels = JSON.parse(r);
								if(levels.hasOwnProperty("success") && levels.success == 0){
									alert("An error occured with communicating to Wishlist API with the message: "+levels.error);
								}else{
									for(var l in levels){
										var level = levels[l];
										$(".tab.products select[name='wlm_id']").append('<option value="'+level.id+'">'+level.name+'</option>');
									}
								}
							}catch(e){}
						});
					}
				}
				
				function loadSiteOrders(){
					$.fn.dataTable.render.myControls = function ( data, type, full, meta ) {
						var details = {};
						if(full) details = full.details;
						return function(){							
							var button = jQuery("<button id='vieworder'>View Order</button>");
							jQuery(button).attr("data-details", details).attr("data-full", JSON.stringify(full));
							return button[0].outerHTML;
						};
					}
					
					if ( ! $.fn.DataTable.isDataTable( '#siteOrders-list' ) ) {
						window.siteOrders = $("#siteOrders-list").DataTable({
							ajax: "?action=site-orders&siteid="+$("#siteid").val(),
							"oLanguage": {
								"sEmptyTable": "No Orders as of this time yet."
							},
							rowId: 'id',
							columns: [
								{ data: "order_id" },
								{ data: "email" },
								{ data: "order_total" },
								{ data: "coupon_code" },
								{ data: "order_date" },
								{ data: "hash", render: $.fn.dataTable.render.myControls	}
							], 
							// "columnDefs": [
								// { "width": "140px", "targets": 4 }
							// ],
							"order": [[1, "asc"]], "pageLength": 50
						});
					}else{
						 window.siteOrders.ajax.reload();
					}
				}
				function getListsOptions(){ /* Generates Lists Dropdown Options */
					var options = "<option value=\"0\">-- Select Lists --</option>";
					for(var i in ACDATA.Lists){
						var list = ACDATA.Lists[i];
						options = options+"<option value=\""+list.id+"\">"+list.name+"</option>";
					}
					$(".tab.activecampaign select[name='Lists']").html(options);
				}
				
				function get_cf_lists(lid){ /* Lists Custom Fields from specific Lists */
					if(typeof lid == "undefined") return {};
					var cf = new Array;
					for(var c in ACDATA.cf){
						if(lid == "all"){
							cf.push(ACDATA.cf[c]);
						}else{
							for(var l in ACDATA.cf[c]){
								if(ACDATA.cf[c][l] == lid)cf.push(ACDATA.cf[c]);
							}
						}
					}
					return cf;
				}
				
				function get_added_lists(){ /* Lists all Added Lists */
					var lists = new Array;
					$("#addedLists .siteLists").each(function(i, v){
						lists.push({id:$(this).attr("id"), name:$(this).text()});
					});
					return lists;
				}
				
				function UpdateCustomFieldsOptions(){ /* Updates the Options of Custom Fields */
					var showall = $("#showAllCustomFields").is(":checked");
					var cf = new Array;
					// if(showall == true){
						cf = get_cf_lists("all");
					// }else{
						// var lists = get_added_lists();
						// if(lists.length > 0){
							// for(var l in lists){
								// var lid = lists[l].id;
								// cf = cf.concat(get_cf_lists(lid));
							// }
						// }
					// }
					
					var options = "<option value=\"0\">-- Custom Field --</option>";
					for(var c in cf){
						var cfield = cf[c];
						options = options+"<option value=\""+cfield.id+"\">"+cfield.title+"</option>";
					}
					$("select[name='CF']").html(options);
				}
				
				
				function makeSiteWLM(){
					var iswlm = ($("select[name='plugin'] option:selected").val() == "wishlist"?true:false);
					if(iswlm == true){
						$(".tab.products select[name='wlm_id']").parent().show();
						jQuery("#productslist").removeClass("WLM");
					}else{
						$(".tab.products select[name='wlm_id']").parent().hide();
						jQuery("#productslist").addClass("WLM");
					}
				}
				
				$("body").on("click", ".updateSite", updateSite);
				
				function updateSite(){
					var site = {
						id:$("#siteid").val(),
						APIs : gather_API_tab_info(),
						Trackers : gather_Trackers_tab_info(),
						AC : gather_AC_tab_info(),
						Products : gather_Products_tab_info(),
						Product_Type_Labels : gather_Product_Type_Labels(),
					};
					$(".updateSite").hide();
					$(".updatingSite").show();
					console.log(site);
					$.post("?action=site-update",{site:site},function(r){
						console.log(r);
						$(".updatingSite").hide();
						$(".updateSite").show();
						jQuery("#saveProgressNotif").css("right", "-150px").show().stop().animate({right:0},200);
						myVar = setTimeout(function(){
							jQuery("#saveProgressNotif").fadeOut(200,function(){$(this).hide().css({opacity:1})});
						}, 3000);
					});
				}
				fixWidth($);
				$( window ).resize(function(){
					fixWidth($);
				});
			});
			
			function fixWidth($){
				$(".field input:not([type='checkbox']), .field select").each(function(i, v){
					var lwidth = $(this).prev().outerWidth();
					var pwidth = $(this).parent().outerWidth();
					var iwidth = pwidth - lwidth;
					// console.log(pwidth+" - "+lwidth+" = "+iwidth);
					$(this).outerWidth(iwidth-20);
				});
			}
			
			function gather_API_tab_info(){
				var APIs = {};
				jQuery(".tab.apikeys .section").each(function(){
					var n = jQuery(this).attr("id");
					APIs[n] = {};
					jQuery("input, select", this).each(function(){
						var type = jQuery(this).prop('nodeName');
						var v;
						console.log(type);
						if($(this).attr("type") == "checkbox"){
							v = $(this).is(":checked");
						}else if(type == "INPUT"){
							v  = jQuery(this).val();
						}else if(type == "SELECT"){
							v  = jQuery("option:selected",this).val();
						}
						APIs[n][jQuery(this).attr("name")] = v;
					});
				});
				return APIs;
			}
			
			function gather_Trackers_tab_info(){
				var APIs = {};
				jQuery(".tab.jstracker .section").each(function(){
					var n = jQuery(this).attr("id");
					APIs[n] = {};
					jQuery("input, select", this).each(function(){
						var type = jQuery(this).prop('nodeName');
						var v;
						if(type == "INPUT"){
							v  = jQuery(this).val();
						}else if(type == "SELECT"){
							v  = jQuery("option:selected",this).val();
						}
						APIs[n][jQuery(this).attr("name")] = v;
					});
				});
				return APIs;
			}
			
			function gather_AC_tab_info(){
				var AC = {Lists:[], CF:[], Note:jQuery("#addNote").is(":checked"), UseStoredEmail:jQuery("#UseStoredEmail").is(":checked")};
				AC.DynamicFields = {
					addOrderDate : jQuery(".tab.activecampaign input[name='addOrderDate']").is(":checked"),
					addOrderTime : jQuery(".tab.activecampaign input[name='addOrderTime']").is(":checked"),
					addSrouce : jQuery(".tab.activecampaign input[name='addSrouce']").is(":checked"),
					addAccess : jQuery(".tab.activecampaign input[name='addAccess']").is(":checked"),
					
					addSubAmount : jQuery(".tab.activecampaign input[name='addSubAmount']").is(":checked"),
					addSubFrequency : jQuery(".tab.activecampaign input[name='addSubFrequency']").is(":checked"),
					addSubDue : jQuery(".tab.activecampaign input[name='addSubDue']").is(":checked"),
					addSubValueDue : jQuery(".tab.activecampaign input[name='addSubValueDue']").is(":checked"),
				}
				jQuery(".tab.activecampaign #addedLists .siteLists").each(function(){
					AC.Lists.push({id:jQuery(this).attr("id"),name:jQuery(this).contents().get(0).nodeValue});
				});
				jQuery(".tab.activecampaign .assignedCustomFields tbody tr").each(function(){
					AC.CF.push({
						thrive:jQuery("td:eq(0)",this).attr("data-val"),
						field:{
								id:jQuery("td:eq(1)",this).attr("data-val"),
								title:jQuery("td:eq(1)",this).text()
						}
					});
				});
				
				AC.ThriveCartCustomFields = [];
				// jQuery("select[name='TF'] option.cf").each(function(){
					// AC.ThriveCartCustomFields[$(this).val()] = $(this).text();
				// });
				console.log("note :", AC);
				return AC;
			}
			
			function gather_Products_tab_info(){
				var Products = [];
				jQuery(".tab.products #productslist tbody tr").each(function(){
					Products.push({
						product_id: jQuery("td:eq(0)",this).text(),
						is_bump: jQuery("td:eq(1)",this).text(),
						product_name: jQuery("td:eq(2)",this).text(),
						wishlist_id: jQuery("td:eq(3) .id",this).text(),
						wishlist_name: jQuery("td:eq(3) .name",this).text(),
						dashboard_title: jQuery("td:eq(4) .title",this).text(),
						dashboard_url: jQuery("td:eq(4) .url",this).text(),
					});
				});
				return Products;
			}
			
			function gather_Product_Type_Labels(){
				var ptl = {
					show: jQuery(".tab.products input[name='showTypeColumn']").is(":checked"),
					labels:{
						"Main Product": jQuery(".tab.products input[name='main_product']").val(),
						"Bump": jQuery(".tab.products input[name='bump']").val(),
						"Upsell": jQuery(".tab.products input[name='upsell']").val(),
						"Downsell": jQuery(".tab.products input[name='downsell']").val(),
						}
					};
					return ptl;
				
			}
			function fixAMP(text){
				return text.replace(";amp;", ";");
			}
		</script>
	</head>
	<body>
		<?php include("main-nav.php"); ?>
		<div id="main" style="padding:20px;">
			<input type="hidden" id="siteid" value="<?php echo $_GET["id"]; ?>" />
			<h1>Name: <?php echo @$s["name"]; ?> <button class="addnew updateSite" style="margin: 10px 0;">Save</button> <span class="tooltip blue" title="<?php echo $tooltips["Save Progress"]; ?>">i</span><span class="loadinggif updatingSite">&nbsp;</span></h1>
			<?php
			if(@$site["APIs"]["ActiveCampaign"]["useActiveCampaign"] == "true"){
				$checked = "checked";
				$acclass = "useActiveCampaign";
			}else{
				$checked = "";
				$acclass = "dontuseActiveCampaign";
			}
			?>
			<div id="tabscont">
				<div class="mobileMenu">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
				<ul id="tabs">
					<li class="active" id="apikeys">APIs <?php tooltip($tooltips["APIs tab"], "white"); ?></li>
					<li id="activecampaign" class="<?php echo $acclass; ?>">ActiveCampaign <?php tooltip($tooltips["ActiveCampaign tab"], "white"); ?></li>
					<li id="products">Products <?php tooltip($tooltips["Products tab"], "white"); ?></li>
					<li id="jstracker">Trackers <?php tooltip($tooltips["Trackers tab"], "white"); ?></li>
					<li id="orders">Orders <?php tooltip($tooltips["Orders tab"], "white"); ?></li>
				</ul>
			</div>
			<div id="bodycontent">
				<div class="tab apikeys active">
					<h2>APIs <?php tooltip($tooltips["APIs tab"], "blue"); ?></h2>
					<div class="row">
						<div class="section" id="ThriveCart">
							<div class="heading">ThriveCart Deep Data Integration <?php tooltip($tooltips["ThriveCart API"], "blue"); ?> <?php vidtutorial($video_tutorials["ThriveCart API"], $tooltips["video icon"]); ?>
							</div>
							<div class="field">
								<label>Page Name <?php tooltip(@$tooltips["Success Page Name"], "blue"); ?></label>
								<input type="text" name="name" value="<?php echo @$s["name"]; ?>" />
							</div>
							<div class="field">
								<label>Secret Key <?php tooltip($tooltips["ThriveCart Secret Key"], "blue"); ?></label>
								<input type="text" name="secert" value="<?php echo @$site["APIs"]["ThriveCart"]["secert"]; ?>" />
							</div>
						</div>
						<div class="section" id="ActiveCampaign">
							<div class="heading">ActiveCampaign <?php tooltip($tooltips["ActiveCampaign API"], "blue"); ?> <?php vidtutorial($video_tutorials["ActiveCampaign API"], $tooltips["video icon"]); ?>
							<span style="float:right;color:green;font-weight:normal;" id="ACstatus">Status: Connecting...</span>
							</div>
							<div class="field">
								<label style="width:auto;"  class="forcheckbox" for="useActiveCampaign"><input style="width:auto;" type="checkbox" id="useActiveCampaign" name="useActiveCampaign" value="true" <?php echo $checked; ?>/> Use ActiveCampaign with this ThriveCart Deep Data Integration <?php tooltip($tooltips["Use ActiveCampaign"], "blue"); ?></label>
							</div>
							<div class="field <?php echo $acclass; ?>">
								<label>URL <?php tooltip($tooltips["ActiveCampaign API URL"], "blue"); ?></label>
								<input type="text" name="url" value="<?php echo @$site["APIs"]["ActiveCampaign"]["url"]; ?>" />
							</div>
							<div class="field <?php echo $acclass; ?>">
								<label>Key <?php tooltip($tooltips["ActiveCampaign API Key"], "blue"); ?></label>
								<input type="text" name="key" value="<?php echo @$site["APIs"]["ActiveCampaign"]["key"]; ?>" />
							</div>
							<div class="field <?php echo $acclass; ?>">
								<label>Plan <?php tooltip(@$tooltips["ActiveCampaign API Plan"], "blue"); ?></label>
								<?php
									${"plan".@$site["APIs"]["ActiveCampaign"]["plan"]} = "Selected";
								?>
								<select name="plan">
									<option value="lite" <?php echo @$planlite; ?>>Lite</option>
									<option value="plus" <?php echo @$planplus; ?>>Plus or Higher</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="section" id="Membership">
							<div class="heading">Membership <?php tooltip($tooltips["Membership API"], "blue"); ?> <?php vidtutorial($video_tutorials["Membership API"], $tooltips["video icon"]); ?>
							<span style="float:right;color:green;font-weight:normal;" id="WPstatus">Status: Connecting...</span>
							</div>
							<div class="field">
								<label>Wordpress Directory <?php tooltip($tooltips["Wordpress site URL"], "blue"); ?></label>
								<input type="text" name="url" value="<?php echo @$site["APIs"]["Membership"]["url"]; ?>" />
							</div>
							<div class="field">
								<label>Members Login URL <?php tooltip($tooltips["Members Login URL"], "blue"); ?></label>
								<input type="text" name="loginurl" value="<?php echo @$site["APIs"]["Membership"]["loginurl"]; ?>" />
							</div>
							<div class="field">
								<label>WPMEA Plugin Key <?php tooltip($tooltips["WPMEA Key"], "blue"); ?></label>
								<input type="text" name="WP_Member_Extend_API_Key" value="<?php echo @$site["APIs"]["Membership"]["WP_Member_Extend_API_Key"]; ?>" />
							</div>
							<div class="field">
								<label style="width:auto;"  class="forcheckbox" for="Enable_One_Click_Login"><input style="width:auto;" type="checkbox" id="Enable_One_Click_Login" name="Enable_One_Click_Login" value="true" <?php echo (@$site["APIs"]["Membership"]["Enable_One_Click_Login"] === "true"?"Checked":""); ?>/> Enable One Click Login <?php tooltip($tooltips["Enable One Click Login"], "blue"); ?></label>
							</div>
							<?php
								$wishlist_show = "";
								$memberium_show = "";
								$activemember360_show = "";
								${@$site["APIs"]["Membership"]["plugin"]."_show"} = "style=\"display:block;\"";
								${@$site["APIs"]["Membership"]["plugin"]."_selected"} = "selected";
								// print_r(@$site["APIs"]["Membership"]);
							?>
							<div class="field">
								<label>Plugin <?php tooltip(@$tooltips["Membership Plugin"], "blue"); ?></label>
								<select name="plugin">
									<option value="0">None</option>
									<option value="wishlist" <?php echo @$wishlist_selected; ?>>WishList</option>
									<!-- option value="memberium" <?php echo @$memberium_selected; ?>>Memberium</option>
									<option value="activemember360" <?php echo @$activemember360_selected; ?>>ActiveMember360</option -->
								</select>
							</div>
							<div class="field wpplugin" <?php echo $wishlist_show; ?>>
								<label>WishList Key <?php tooltip(@$tooltips["WishList API Key"], "blue"); ?> <?php vidtutorial(@$video_tutorials["WishList Key"], $tooltips["video icon"]); ?></label>
								<input type="text" name="WishList_API_Key" value="<?php echo @$site["APIs"]["Membership"]["WishList_API_Key"]; ?>" readOnly />
							</div>
							<div class="field wpplugin" <?php echo $memberium_show; ?>>
								<label>Memberium Key <?php tooltip(@$tooltips["Memberium Key"], "blue"); ?> <?php vidtutorial(@$video_tutorials["Memberium Key"], $tooltips["video icon"]); ?></label>
								<input type="text" name="Memberium_API_Key" value="<?php echo @$site["APIs"]["Membership"]["Memberium_API_Key"]; ?>" readOnly />
							</div>
							<div class="field wpplugin" <?php echo $activemember360_show; ?>>
								<label>ActiveMember360 Key <?php tooltip(@$tooltips["ActiveMember360 Key"], "blue"); ?> <?php vidtutorial(@$video_tutorials["ActiveMember360 Key"], $tooltips["video icon"]); ?></label>
								<input type="text" name="ActiveMember360_API_Key" value="<?php echo @$site["APIs"]["Membership"]["ActiveMember360_API_Key"]; ?>" readOnly />
							</div>
						</div>
					</div>
				</div>
				<div class="tab jstracker">
					<h2>Trackers <?php tooltip($tooltips["Trackers tab"], "blue"); ?></h2>
					<div class="row short">
						<div class="section" id="GoogleAnalytics">
							<div class="heading">Google Analytics <?php tooltip($tooltips["Google Analytics Tracker"], "blue"); ?> <?php vidtutorial($video_tutorials["Google Analytics Tracker"], $tooltips["video icon"]); ?>
							</div>
							<div class="field">
								<label>ID <span class="tooltip blue" title="Google Analytics Property ID">i</span></label>
								<input type="text" name="property_id" value="<?php echo @$site["Trackers"]["GoogleAnalytics"]["property_id"]; ?>" />
							</div>
						</div>
						<div class="section" id="Clicky">
							<div class="heading">Clicky <?php tooltip($tooltips["Clicky Tracker"], "blue"); ?> <?php vidtutorial($video_tutorials["Clicky Tracker"], $tooltips["video icon"]); ?>
							</div>
							<div class="field">
								<label>Site ID <?php tooltip($tooltips["Clicky Site ID"], "blue"); ?></label>
								<input type="text" name="site_id" value="<?php echo @$site["Trackers"]["Clicky"]["site_id"]; ?>" />
							</div>
							<div class="field">
								<label>Revenue ID <?php tooltip($tooltips["Clicky Revenue ID"], "blue"); ?></label>
								<input type="text" name="revenue_id" value="<?php echo @$site["Trackers"]["Clicky"]["revenue_id"]; ?>" />
							</div>
						</div>
					</div>
					<div class="row short">
						<div class="section" id="ACSiteTracker">
							<div class="heading">ActiveCampaign Site Tracker <?php tooltip($tooltips["ActiveCampaign Site Tracker"], "blue"); ?> <?php vidtutorial($video_tutorials["ActiveCampaign Site Tracker"], $tooltips["video icon"]); ?>
							</div>
							<div class="field">
								<label>Tracker ID <?php tooltip($tooltips["ActiveCampaign Tracker ID"], "blue"); ?></label>
								<input type="text" name="tracker_id" value="<?php echo @$site["Trackers"]["ACSiteTracker"]["tracker_id"]; ?>" />
							</div>
						</div>
						<div class="section" id="Olark">
							<div class="heading">Olark <?php tooltip($tooltips["Olark Tracker"], "blue"); ?> <?php vidtutorial($video_tutorials["Olark Tracker"], $tooltips["video icon"]); ?>
							</div>
							<div class="field">
								<label>Identity <?php tooltip($tooltips["Olark Identity"], "blue"); ?></label>
								<input type="text" name="identity" value="<?php echo @$site["Trackers"]["Olark"]["identity"]; ?>" />
							</div>
						</div>
					</div>
				</div>
				<div class="tab activecampaign">
					<h2>ActiveCampaign <?php tooltip(@$tooltips["ActiveCampaign tab"], "blue"); ?> <?php vidtutorial(@$video_tutorials["ActiveCampaign Tab"], $tooltips["video icon"]); ?></h2>
					<!-- div class="section wide">						
						<div class="heading">Add Contact to Lists <?php tooltip($tooltips["Add Contact to Lists"], "blue"); ?>  <?php vidtutorial($video_tutorials["Add Contact to Lists"], $tooltips["video icon"]); ?>
						</div>
						<div class="field2">
							<select name="Lists" style="max-width:200px;"></select>
							<button class="additem" id="addLists">Add</button> <?php tooltip($tooltips["Add Contact to Lists Button"], "blue"); ?>
						</div>
						<div id="addedLists">
							<?php foreach((array)@$site["AC"]["Lists"] as $list): ?>
							<span class="siteLists" id="<?php echo  @$list["id"]; ?>"><?php echo  @$list["name"]; ?><span>x</span></span>
							<?php endforeach; ?>
						</div>
					</div -->
					<div class="section wide" style="min-height:100px;">
						<div class="heading">Stored Email <?php tooltip($tooltips["Stored Email"], "blue"); ?> <?php vidtutorial($video_tutorials["Stored Email"], $tooltips["video icon"]); ?>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["UseStoredEmail"] == "true") $UseStoredEmail = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="UseStoredEmail" name="UseStoredEmail" value="true" <?php echo @$UseStoredEmail; ?>/>
							<label for="UseStoredEmail" style="width:275px;">Update ActiveCampaign Contact Record With Stored Email?  <?php tooltip($tooltips["Update ActiveCampaign Contact Record With Stored Email"], "blue"); ?></label>
						</div>
					</div>
					<div class="section wide" style="min-height:100px;">
						<div class="heading">Note <?php tooltip($tooltips["Add Note"], "blue"); ?> <?php vidtutorial($video_tutorials["Add Note"], $tooltips["video icon"]); ?>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["Note"] == "true") $Note = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addNote" name="addNote" value="true" <?php echo @$Note; ?>/>
							<label for="addNote" style="width:275px;">Add Note about the Order Details  <?php tooltip($tooltips["Enable Note"], "blue"); ?></label>
						</div>
					</div>
					<div class="section wide" style="min-height:100px;">
						<div class="heading">Dynamic Custom Fields <?php tooltip(@$tooltips["Dynamic Custom Fields"], "blue"); ?> <?php vidtutorial(@$video_tutorials["Dynamic Custom Fields"], $tooltips["video icon"]); ?>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addOrderDate"] == "true") $addOrderDate = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addOrderDate" name="addOrderDate" value="true" <?php echo @$addOrderDate; ?>/>
							<label for="addOrderDate" style="width:275px;">Order Date - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Order Date"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addOrderTime"] == "true") $addOrderTime = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addOrderTime" name="addOrderTime" value="true" <?php echo @$addOrderTime; ?>/>
							<label for="addOrderTime" style="width:275px;">Order Time - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Order Time"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addSrouce"] == "true") $addSrouce = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addSrouce" name="addSrouce" value="true" <?php echo @$addSrouce; ?>/>
							<label for="addSrouce" style="width:275px;">Source - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Source"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addAccess"] == "true") $addAccess = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addAccess" name="addAccess" value="true" <?php echo @$addAccess; ?>/>
							<label for="addAccess" style="width:275px;">Access - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Access"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addSubAmount"] == "true") $addSubAmount = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addSubAmount" name="addSubAmount" value="true" <?php echo @$addSubAmount; ?>/>
							<label for="addSubAmount" style="width:275px;">Subscription Amount - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Subscription Amount"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addSubFrequency"] == "true") $addSubFrequency = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addSubFrequency" name="addSubFrequency" value="true" <?php echo @$addSubFrequency; ?>/>
							<label for="addSubFrequency" style="width:275px;">Subscription Frequency - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Subscription Frequency"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addSubDue"] == "true") $addSubDue = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addSubDue" name="addSubDue" value="true" <?php echo @$addSubDue; ?>/>
							<label for="addSubDue" style="width:275px;">Subscription Due - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Subscription Due"], "blue"); ?></label>
						</div>
						<div class="field2">
							<?php if(@$site["AC"]["DynamicFields"]["addSubValueDue"] == "true") $addSubValueDue = "checked"; ?>
							<input style="width:auto;" type="checkbox" id="addSubValueDue" name="addSubValueDue" value="true" <?php echo @$addSubValueDue; ?>/>
							<label for="addSubValueDue" style="width:275px;">Subscription Value Due - %PRODNAME%  <?php tooltip(@$tooltips["Dynamic - Subscription Value Due"], "blue"); ?></label>
						</div>
					</div>
					<div class="section wide">
						<div class="heading">Assign ThriveCart Customer Information to Custom Fields <?php tooltip($tooltips["Assign Information"], "blue"); ?> <?php vidtutorial($video_tutorials["Assign Information"], $tooltips["video icon"]); ?>
						</div>
						<!-- div class="field2">
							<input style="width:auto;" type="checkbox" id="showAllCustomFields" name="showAllCustomFields" value="all" checked/>
							<label for="showAllCustomFields" style="width:275px;">Show Custom Fields from all Lists <?php tooltip($tooltips["Show All Custom Fields"], "blue"); ?></label>
						</div -->
						<div class="field2" id="thrivecartfield">
							<span class="reloadCF" title="Reload Custom Fields">Reload</span>
							<select name="TF" style="max-width:200px;">
								<option value="0">-- ThriveCart Field --</option>
								<option value="create_custom_field">-- Create Custom Field --</option>
								<?php foreach($thrive_data as $k=>$td): ?>
								<option value="<?php echo $k; ?>"><?php echo $td["label"]; ?></option>
								<?php endforeach; ?>
								<?php foreach($site["AC"]["ThriveCartCustomFields"] as $k=>$l): ?>
								<option value="<?php echo $k; ?>" class="cf"><?php echo $l; ?></option>
								<?php endforeach; ?>
							</select>
							<select name="CF" style="max-width:200px;"></select>
							<button class="additem assignCustomField">Assign</button> <?php tooltip($tooltips["Assign Fields Button"], "blue"); ?>
							<div class="assignedCustomFields">
								<table>
									<thead>
										<tr>
											<th>Thrive Info</th>
											<th>Custom Field</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach((array)@$site["AC"]["CF"] as $CF): ?>
										<?php if(@$thrive_data[$CF["thrive"]]["label"] || @$site["AC"]["ThriveCartCustomFields"][$CF["thrive"]]): ?>
										<tr>
											<td data-val="<?php echo $CF["thrive"]; ?>"><?php echo (@$thrive_data[$CF["thrive"]]["label"] != "" ?$thrive_data[$CF["thrive"]]["label"]:@$site["AC"]["ThriveCartCustomFields"][$CF["thrive"]]); ?></td>
											<td data-val="<?php echo $CF["field"]["id"]; ?>"><?php echo $CF["field"]["title"]; ?></td>
											<td><span class="deltr">-</span></td>
										</tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="heading">Assign ThriveCart Customer Deep Data Information to Custom Fields <?php tooltip(@$tooltips["Assign Deep Data Information"], "blue"); ?> <?php vidtutorial(@$video_tutorials["Assign Deep Data Information"], $tooltips["video icon"]); ?>
						</div>
						<div class="field2" id="deepdatafield">
							<span class="reloadCF" title="Reload Custom Fields">Reload</span>
							<select name="TF" style="max-width:200px;">
								<option value="0">-- Deep Data ECommerce Fields --</option>
								<?php foreach($deep_data as $k=>$td): ?>
								<option value="<?php echo $k; ?>"><?php echo $td["label"]; ?></option>
								<?php endforeach; ?>
							</select>
							<select name="CF" style="max-width:200px;"></select>
							<button class="additem assignCustomField">Assign</button> <?php tooltip(@$tooltips["Assign Fields Button"], "blue"); ?>
							<div class="assignedCustomFields">
								<table>
									<thead>
										<tr>
											<th>Thrive Info</th>
											<th>Custom Field</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach((array)@$site["AC"]["CF"] as $CF): ?>
										<?php if(@$deep_data[$CF["thrive"]]["label"]): ?>
										<tr>
											<td data-val="<?php echo $CF["thrive"]; ?>"><?php echo $deep_data[$CF["thrive"]]["label"]; ?></td>
											<td data-val="<?php echo $CF["field"]["id"]; ?>"><?php echo $CF["field"]["title"]; ?></td>
											<td><span class="deltr">-</span></td>
										</tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="tab products">
					<h2>Products <?php tooltip($tooltips["Products tab"], "blue"); ?> <?php vidtutorial($video_tutorials["Products tab"], $tooltips["video icon"]); ?></h2>
					<div class="row">
						<div class="section">
							<div class="heading">Add Product</div>
							<div class="field">
								<label>Product ID <?php tooltip($tooltips["ThriveCart Product ID"], "blue"); ?></label>
								<input style="width: 80px;" type="text" name="id" value="" placeholder="Product ID" />
							</div>
							<div class="field">
								<label style="width:auto;"  class="forcheckbox" for="is_bump"><input style="width:auto;" type="checkbox" id="is_bump" name="is_bump" value="true" <?php echo (@$site["APIs"]["Membership"]["is_bump"] === "true"?"Checked":""); ?>/> Bump <?php tooltip($tooltips["is_bump"], "blue"); ?></label>
							</div>
							<div class="field">
								<label>Product Name <?php tooltip($tooltips["ThriveCart Product Name"], "blue"); ?></label>
								<input type="text" name="name" value="" placeholder="Product Name" />
							</div>
							<div class="field">
								<label>WishList ID <?php tooltip($tooltips["WishList Level"], "blue"); ?></label>
								<select name="wlm_id"><option value="-1"></option></select>
							</div>
							<div class="field">
								<label>Product Dashboard <?php tooltip(@$tooltips["Product Dashboard"], "blue"); ?></label>
								<select name="dashboard"><option value="-1"></option></select>
							</div>
							<div class="field">
								<label>Product URL <?php tooltip($tooltips["Product Dashboard URL"], "blue"); ?></label>
								<input type="text" name="url" value="" placeholder="Product Dashboard URL" />
							</div>
							<button class="additem" id="addProduct">Add</button> <?php tooltip($tooltips["Add Product Button"], "blue"); ?>
						</div>
						<div class="section">
							<?php
								$showTypeColumn = (!@$site["Product_Type_Labels"]["show"] || @$site["Product_Type_Labels"]["show"] == "true"?"checked":"");
							?>
							<div class="heading">Product Type Labels</div>
							<div class="field">
								<label style="width:auto;"  class="forcheckbox" for="showTypeColumn"><input style="width:auto;" type="checkbox" id="showTypeColumn" name="showTypeColumn" value="true" <?php echo $showTypeColumn; ?>/> Show Product Type Column on Thank You Page <?php tooltip($tooltips["Show Product Type Column"], "blue"); ?></label>
							</div>
							<div class="field">
								<label>Main Product <?php tooltip($tooltips["Main Product"], "blue"); ?></label>
								<input style="width: 80px;" type="text" name="main_product" value="<?php echo @$site["Product_Type_Labels"]["labels"]["Main Product"]; ?>" placeholder="Main Product" />
							</div>
							<div class="field">
								<label>Bump <?php tooltip($tooltips["Bump"], "blue"); ?></label>
								<input style="width: 80px;" type="text" name="bump" value="<?php echo @$site["Product_Type_Labels"]["labels"]["Bump"]; ?>" placeholder="Bump" />
							</div>
							<div class="field">
								<label>Upsell <?php tooltip($tooltips["Upsell"], "blue"); ?></label>
								<input style="width: 80px;" type="text" name="upsell" value="<?php echo @$site["Product_Type_Labels"]["labels"]["Upsell"]; ?>" placeholder="Upsell" />
							</div>
							<div class="field">
								<label>Downsell <?php tooltip($tooltips["Downsell"], "blue"); ?></label>
								<input style="width: 80px;" type="text" name="downsell" value="<?php echo @$site["Product_Type_Labels"]["labels"]["Downsell"]; ?>" placeholder="Downsell" />
							</div>
						</div>
					</div>
					
					<div class="section wide" style="overflow:auto;">
						<p><b>Products List</b></p>
						<div id="productslist">
							<table>
								<thead>
									<tr>
										<th>ID</th>
										<th>Bump</th>
										<th>Name</th>
										<th>Wishlist Level</th>
										<th>URL</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach((array)@$site["Products"]as $p): 
										if(!@$p["dashboard_title"]){
											$hide_title="style='display:none'";
											$hide_url="";
										}else{
											$hide_title="";
											$hide_url="style='display:none'";
										}
									?>
									<tr>
										<td><?php echo @$p["product_id"]; ?></td>
										<td><?php echo @$p["is_bump"]; ?></td>
										<td><?php echo @$p["product_name"]; ?></td>
										<td><span class="name"><?php echo (@$p["wishlist_name"] != "{Loading Options. Please wait.}"?$p["wishlist_name"]:""); ?></span><span class="id" style="display:none;"><?php echo @$p["wishlist_id"]; ?></span></td>
										<td><span class="title" <?php echo $hide_title; ?>><?php echo $p["dashboard_title"]; ?></span><span class="url" <?php echo $hide_url; ?>><?php echo $p["dashboard_url"]; ?></span></td>
										<td><span class="deltr">-</span></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="tab orders">
					<h2>Orders <?php tooltip($tooltips["Orders tab"], "blue"); ?> <?php vidtutorial($video_tutorials["Orders tab"], $tooltips["video icon"]); ?></h2>
					<table class="table" id="siteOrders-list">
						<thead>
							<th>Order ID</th>
							<th>Email</th>
							<th>Order Total</th>
							<th>Coupon</th>
							<th>Date</th>
							<th>View</th>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div style="float: right;margin: 10px 0;"><button class="addnew updateSite" style="margin-right: 10px;">Save</button><span class="tooltip blue" title="<?php echo $tooltips["Save Progress"]; ?>">i</span></div>
			</div>
		</div>
		<?php 
			$footer = str_replace("[YEAR]", Date("Y"), base64_decode($pages["footer"])); 
			echo $footer;
		?>
		<div id="saveProgressNotif">Settings Successfully Saved.</div>
	</body>
</html>