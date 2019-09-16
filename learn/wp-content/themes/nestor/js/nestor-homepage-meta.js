jQuery( window ).load( function() {

	var showWhenImage   = jQuery( '#pe_theme_meta_splash__background_, #pe_theme_meta_splash__image_type__0, #pe_theme_meta_splash__title_, #pe_theme_meta_splash__headlines_' ).closest( '.option' ),
		showWhenGallery = jQuery( '#pe_theme_meta_splash__gallery_' ).closest( '.option' );

	var showWhenSimpleTagline    = jQuery( '#pe_theme_meta_splash__background_, #pe_theme_meta_splash__title_' ).closest( '.option' ),
		showWhenMultipleTaglines = jQuery( '#pe_theme_meta_splash__background_, #pe_theme_meta_splash__headlines_' ).closest( '.option' );

	if ( jQuery( 'label[for="pe_theme_meta_splash__type__1"]' ).hasClass( 'ui-state-active') ) { // image home

		showWhenGallery.hide();
		showWhenImage.show();

		if ( jQuery( 'label[for="pe_theme_meta_splash__image_type__0"]' ).hasClass( 'ui-state-active' ) ) { // simple tagline

			showWhenMultipleTaglines.hide();
			showWhenSimpleTagline.show();

		} else { // multiple taglines

			showWhenSimpleTagline.hide();
			showWhenMultipleTaglines.show();

		}

	} else if ( jQuery( 'label[for="pe_theme_meta_splash__type__2"]' ).hasClass( 'ui-state-active') ) { // gallery home

		showWhenImage.hide();
		showWhenGallery.show();
		
	} else {

		showWhenImage.hide();
		showWhenGallery.hide();

	}

	jQuery( 'label[for="pe_theme_meta_splash__type__0"], label[for="pe_theme_meta_splash__type__1"], label[for="pe_theme_meta_splash__type__2"], label[for="pe_theme_meta_splash__image_type__0"], label[for="pe_theme_meta_splash__image_type__1"]' ).on( 'click', function(e) {

		if ( jQuery( 'label[for="pe_theme_meta_splash__type__1"]' ).hasClass( 'ui-state-active') ) { // image home

			showWhenGallery.hide();
			showWhenImage.show();

			if ( jQuery( 'label[for="pe_theme_meta_splash__image_type__0"]' ).hasClass( 'ui-state-active' ) ) { // simple tagline

				showWhenMultipleTaglines.hide();
				showWhenSimpleTagline.show();

			} else { // multiple taglines

				showWhenSimpleTagline.hide();
				showWhenMultipleTaglines.show();

			}

		} else if ( jQuery( 'label[for="pe_theme_meta_splash__type__2"]' ).hasClass( 'ui-state-active') ) { // gallery home

			showWhenImage.hide();
			showWhenGallery.show();
			
		} else {

			showWhenImage.hide();
			showWhenGallery.hide();

		}

	});

});