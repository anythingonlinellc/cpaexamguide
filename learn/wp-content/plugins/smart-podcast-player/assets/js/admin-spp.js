(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });

    $(function() {
    	$( window ).on( 'spp_shortcode', function(e) {
        	// $('.color-field').wpColorPicker();
        	
        });
    });

    $(function() {
        
        $( '.spp-color-list' ).on( 'change', function(e) {
        	
        	var $parent = $( this ).parent();

			if( $( this ).val() != 'other' ) {
				$parent.find( '.color-field' ).val( $( this ).val() );
				$parent.find( '.wp-color-result' ).css({ 'background-color' : $( this ).val() });
			}

        });

        $( '.color-field' ).on( 'change', function(e) {
			
			var $parent = $( this ).parents( '.spp-color-picker' );
			var $dropdown = $parent.find( '.spp-color-list' );
			var color = $( this ).val();

			$dropdown.find( 'option' ).each( function( index, element ) {

				if( $( element ).val() == color ) {
					$( element ).attr( 'selected', 'selected' );
				} else {

					if( $( element ).val() == 'other' ) {
						$( element ).attr( 'selected', 'selected' );
					} else {
						$( element ).removeAttr( 'selected' );	
					}

				}

			});

        });

    });
     
})( jQuery );

function isPaidVersionAdmin() {
	if ( Smart_Podcast_Player_Admin.licensed )
		return true;
	return false;
}
