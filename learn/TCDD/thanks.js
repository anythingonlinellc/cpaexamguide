		var origin = window.location.hostname;
		jQuery("document").ready(function($){
			jQuery("#theloginurl, #theunam, #thepass").hide();
			<!--- Reset Password--->
			jQuery("body").on("click", "#resetpass", function(){
				console.log("clicked");
				jQuery(this).remove();
				jQuery("#password").text("\"Resetting Password...\"");
				var mid = jQuery(this).attr("data-id");
				var c_url = window.location.href+"&resetpass=1&mid="+mid+"&origin="+origin;
				var tourl = c_url.split("?");
				var url = remote+"thanks_process.php?"+tourl[1];
				console.log("reset",url);
				jQuery.post(url,{},function(d){
					jQuery("#password").text(d);
				});
			});
			
			/* Lets get order details here */
			setTimeout(GetOrder, 5000);
			
			function GetOrder(){
				var purl = remote+"thanks_process.php"+location.search;
				console.log(purl);
				jQuery.ajax({
					type: "POST",
					url: purl,
					dataType: 'json',
					data: {},
					success: function (r) {
						console.log(r);
						if(r.hasOwnProperty("order")){
							// donothing...
						}else{
							// return setTimeout(GetOrder, 500);
							return;
						}
						if(r.hasOwnProperty("Trackers")){
							var trackers = r.Trackers;
							if(trackers.Olark.identity !== ""){
								<!-- begin olark code -->
								;(function(o,l,a,r,k,y){if(o.olark)return;
								r="script";y=l.createElement(r);r=l.getElementsByTagName(r)[0];
								y.async=1;y.src="//"+a;r.parentNode.insertBefore(y,r);
								y=o.olark=function(){k.s.push(arguments);k.t.push(+new Date)};
								y.extend=function(i,j){y("extend",i,j)};
								y.identify=function(i){y("identify",k.i=i)};
								y.configure=function(i,j){y("configure",i,j);k.c[i]=j};
								k=y._={s:[],t:[+new Date],c:{},l:a};
								})(window,document,"static.olark.com/jsclient/loader.js");
								/* Add configuration calls bellow this comment */
								olark('api.visitor.updateFullName', {
									fullName: gfirstname
								});
								olark('api.visitor.updateEmailAddress', {
									emailAddress: gemail
								});
								olark('api.chat.updateVisitorStatus', {
										snippet: ['Source', gref]
									});
								// olark.identify('3543-339-10-6762');
								olark.identify(trackers.Olark.identity);
								<!-- end olark code -->
							}
							
							if(trackers.ACSiteTracker.tracker_id !== ""){
								<!-- AC Site Tracking Code -->
								var trackcmp_email = gemail;
								var trackcmp = document.createElement("script");
								trackcmp.async = true;
								trackcmp.type = 'text/javascript';
								trackcmp.src = '//trackcmp.net/visit?actid='+trackers.ACSiteTracker.tracker_id+'&e='+encodeURIComponent(trackcmp_email)+'&r='+encodeURIComponent(document.referrer)+'&u='+encodeURIComponent(window.location.href);
								var trackcmp_s = document.getElementsByTagName("script");
								if (trackcmp_s.length) {
									trackcmp_s[0].parentNode.appendChild(trackcmp);
								} else {
									var trackcmp_h = document.getElementsByTagName("head");
									trackcmp_h.length && trackcmp_h[0].appendChild(trackcmp);
								}
								<!-- End AC Site Tracking Code -->
							}
							
							if(trackers.Clicky.site_id !== ""){
								<!-- Clicky Custom Goal -->
								  var clicky_custom = clicky_custom || {};
								  clicky_custom.goal = {
									id: trackers.Clicky.revenue_id,
									revenue: gorder_total
								  };
								<!-- Regular Clicky Code -->
								var clicky_site_ids = clicky_site_ids || [];
								clicky_site_ids.push(trackers.Clicky.site_id);
								(function() {
								  var s = document.createElement('script');
								  s.type = 'text/javascript';
								  s.async = true;
								  s.src = '//static.getclicky.com/js';
								  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
								})();
								<!-- noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/101028034ns.gif" /></p></noscript -->
								<!-- End All Clicky Code -->
							}
							
							<!-- Google Analytics -->
							if(trackers.GoogleAnalytics.property_id !== ""){
								(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
								(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
								m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
								})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

								window.ga('create', trackers.GoogleAnalytics.property_id, 'auto');
								window.ga('send', 'pageview');
							}
							<!-- End of Google Analytics -->
							setProductDetails(r);
						}
					},
					error: function (err) {
						console.log("ERRRR ",err);
						if(err.hasOwnProperty("responseText") && err.responseText == "wait"){
							setTimeout(GetOrder, 3000);
						}
					}
				});
			}
			
			function setProductDetails(details){
				jQuery("#loadingdetails").remove();
				jQuery("#orderdetails").show();
				if(details.member.hasOwnProperty("id")){
					jQuery("#theunam, #thepass").show();
					if(typeof hideLoginUrl !=="undefined" && hideLoginUrl == true) jQuery("#theloginurl").hide();
					else jQuery("#theloginurl").show();
				}
				jQuery("#theloginurl").html("<a href=\""+details.member.loginurl+"\" target=\"_blank\"><b>Click here to login to the member's area</b></a>");
				jQuery("#theunam").html("<b>Username :</b> "+details.member.user_login);
				if(details.member.hasOwnProperty("user_pwd")){
					jQuery("#thepass").html("<b>Password :</b> "+details.member.user_pwd);
				}
				
				jQuery("#resetpass").attr("data-id", details.member.id);
				
				jQuery("#customer").append("<span>Order "+details.order_id+"</span>");
				jQuery("#customer").append("<span><b>"+details.customer.name+"</b></span>");
				if(details.customer.hasOwnProperty("address")){
					var caddress = details.customer.address;
					if(caddress.hasOwnProperty("line1"))jQuery("#customer").append("<span>"+caddress.line1+"</span>");
					var cityzip = "";
					var zip = ",";
					if(caddress.hasOwnProperty("state") && caddress.state != "" && caddress.state != "Not applicable"){
						cityzip = ", "+caddress.state;
						zip = "";
					}
					
					if(caddress.hasOwnProperty("zip") && caddress.zip != ""){
						cityzip += zip+" "+caddress.zip;
					}
					jQuery("#customer").append("<span>"+caddress.city+cityzip+"</span>");
					jQuery("#customer").append("<span>"+caddress.country+"</span>");
				}
				if(details.Trackers.GoogleAnalytics.property_id !== ""){
					window.ga('ec:setAction', 'purchase', {          // Transaction details are provided in an actionFieldObject.
					'id': details.order_id,                         // (Required) Transaction id (string).
					'affiliation': '', // Affiliation (string).
					'revenue': details.order.total,                     // Revenue (currency).
					'tax': '',                          // Tax (currency).
					'shipping': '',                     // Shipping (currency).
					'coupon': details.coupon_code                 // Transaction coupon (string).
					});
				}
				
				if(!details.hasOwnProperty("show_colum_type") || details.show_colum_type == "true"){
					jQuery("#products_table").addClass("showColumnType");
				}else{
					jQuery("#products_table").addClass("hideColumnType");
				}
				
				for(var i in details.order.charges){
					var product = details.order.charges[i];
					if(product.type == "recurring") continue;
					var n = "";
					if(product.url != "") n = '<a href="'+product.url+'" title="Once you\'re logged in, click here to go to your product\'s dashboard." target="_blank">'+product.name+'</a>';
					else n = '<a href="#">'+product.name+'</a>';
					var t = product.stype;
					var p = parseFloat((product.amount!=""?product.amount:0)).toFixed(2);
					jQuery("#thetotal").before("<tr><td class=\"product_name aleft\">"+n+"</td><td class=\"aleft\">"+t+"</td><td class=\"aright\">"+p+" "+currency+"</td></tr>");
					
					if(details.Trackers.GoogleAnalytics.property_id !== ""){					
						window.ga('ec:addProduct', {               // Provide product details in an productFieldObject.
						'id': product.reference,
						'name': product.name, // Product name (string).
						'category': product.stype,
						'brand': '',                // Product brand (string).
						'variant': '',               // Product variant (string).
						'price': p,                 // Product price (currency).
						'coupon': details.coupon_code,          // Product coupon (string).
						'quantity': 1                     // Product quantity (number).
						});
					}
				}
				if(details.hasOwnProperty("coupon_code") && details.coupon_code != "undefined" && details.coupon_code != ""){
					jQuery("#thetotal").before("<tr><td class=\"aleft\">Coupon : "+details.coupon_code+"</td><td class=\"aright\">&nbsp;</td><td class=\"aright\"></td></tr>");
				}
			}
		});
		
		/* Cookie Email */
		(function(){
			function createCookie(name,value,days) {
				if (days) {
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
				}
				else var expires = "";
				document.cookie = name+"="+value+expires+"; path=/";
			}

			function readCookie(cookieName) {
				var re = new RegExp('[; ]'+cookieName+'=([^\\s;]*)');
				var sMatch = (' '+document.cookie).match(re);
				if (cookieName && sMatch) return unescape(sMatch[1]);
				return false;
			}
			
			function getUrlVars() {
				var vars = {};
				var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
				function(m,key,value) {
				  vars[key] = value;
				});
				return vars;
			}
				
			if(!readCookie("stored-email")){
				// var email = getUrlVars().email;
				var email = gemail;
				if(email && email != ""){
					console.log(email);
					createCookie("stored-email",email);
				}
			}
		})();