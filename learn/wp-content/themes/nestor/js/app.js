(function ($) {
  "use strict;"
  
  $(document).ready(function() {

    // prevent the # links to scroll to the top of the page
    $("[href=#]").click(function(e) {
      e.preventDefault();
    });

    $("[data-toggle=popover]").popover();
    
    $("[data-toggle=tooltip]").tooltip();

    // flexslider
    $('.flex-bullet-slider').flexslider({
      slideshowSpeed: 10000,
      directionNav: false,
      animation: "fade"
    });

    $('.flex-arrow-slider').flexslider({
      slideshowSpeed: 5000,
      directionNav: true,
      controlNav: false,
      animation: "fade",
      smoothHeight: true
    });

    $('.vertical-center').flexVerticalCenter('padding-top');

    // Lightbox
    $('.venobox').venobox();

    //Portfolio page functionalities
    $('.portfolio-grid').each( function() {

      $( this ).mixitup({
        filterSelector: $( this ).siblings().children( '.filter' )
      });

    });

    //Sticky Header
    if ($( '.sticky-menu' ).length) {
      $('header').waypoint('sticky', {
        offset: "-25px"
      });
    }

    $(window).scroll(function() {
      if ($(this).scrollTop() > 200) { 
        $('#back-to-top').fadeIn();
      } else {
        $('#back-to-top').fadeOut();
      }
    });

    $("#back-to-top").click(function () {
      $("html, body").animate({scrollTop: 0}, 300);
    });

  });
  
})(jQuery);

jQuery(window).load(function() {
  "use strict";

  // Parallax
  if (jQuery(window).width() >= 991 && !navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)) {
    jQuery(window).stellar({
      horizontalScrolling: false,
      horizontalOffset: 0
    });
  }

  jQuery(window).resize(function() {
    (jQuery(window).width() < 991 || navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)) ? jQuery(window).stellar('destroy') : jQuery(window).stellar({ horizontalScrolling: false, horizontalOffset: 0 });
  });
  
  // Google Maps Goodness
  if (document.getElementById('map_canvas')) {

    var $gmap = jQuery( '#map_canvas' );
    
    var gLatitude = $gmap.data( 'latitude' );
    var gLongitude = $gmap.data( 'longitude' );
    var gZoom = $gmap.data( 'zoom' );
    var gTitle = $gmap.data( 'pintitle' );
    var gDescription = $gmap.data( 'pindescription' );
      
    var latlng = new google.maps.LatLng(gLatitude, gLongitude);
    
    var settings = {
      zoom: parseInt(gZoom),
      center: latlng,
      scrollwheel: false,
      mapTypeControl: true,
      mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
      navigationControl: true,
      navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
    
    
    
    var companyMarker = new google.maps.Marker({
      position: latlng,
           map: map,
         title: gTitle
    });
    
    var contentString = '<div id="content-map">'+
        '<h3>' + gTitle + '</h3>'+
        '<p>' + gDescription + '</p>'+
        '</div>';
    
    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });

    if ( gTitle || gDescription ) {

      google.maps.event.addListener(companyMarker, 'click', function() {
        infowindow.open(map,companyMarker);
      });

    }
    
  }

});