jQuery(function(){
  var location= window.location.href;
  var location_array= location.split("?");
  var page_link=location_array[0];
  
function getParameterByName(name, url) {
if (!url) {
url = window.location.href;
}
name = name.replace(/[\[\]]/g, "\\$&");
var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
results = regex.exec(url);
if (!results) return null;
if (!results[2]) return '';
return decodeURIComponent(results[2].replace(/\+/g, " "));
}
var leadboxid= getParameterByName("id");

if(leadboxid){

 var reference_link_selector= $('[data-leadbox-popup="'+leadboxid+'"]');
 console.log(reference_link_selector.offset().top ,'dddd');
  

$(window).load(function(){
   
    
    
    
    if(reference_link_selector.length)
    {
    	
    	/*$('html,body').animate({
          scrollTop:reference_link_selector.offset().top 
        }, 1000);*/
        $('[data-leadbox-popup="'+leadboxid+'"]')[0].click();
     
    } 
})



}
$("[data-leadbox-popup-id]").click(function(ev){
ev.preventDefault();
var selector= $(this);
var leadboxid= selector.data("leadbox-popup-id");
var url = selector.data("url");
var a_tab= page_link+"?id="+leadboxid;

var win = window.open(a_tab, '_blank');
win.focus();
setTimeout(function(){ window.location=url; } ,100);

return false;
})


});