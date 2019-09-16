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
		var to_em = false;
		var email = getUrlVars().email;
		if(email){
			to_em = email;
        }else{
			email = getUrlVars().em;
			if(email){
				to_em = email;
			}
		}
		console.log(to_em);
		if(to_em){
			createCookie("stored-email",to_em);
		}
	}
})();