(function($){
	'use strict';
	/*jslint undef: false, browser: true, devel: false, eqeqeq: false, bitwise: false, white: false, plusplus: false, regexp: false, nomen: false */
	/*jshint undef: false, browser: true, devel: false, eqeqeq: false, bitwise: false, white: false, plusplus: false, regexp: false, nomen: false, validthis: true */
	/*global jQuery */

	$(function(){

		var $window = $( window ),
			$body   = $( 'body' ),
			$header = $( '.navbar-wrapper' ),
			$nav    = $( '.navbar-collapse > ul' ),
			isMobile = $( 'html.mobile' ).length;

		// contact form
		if ( $( '.peThemeContactForm' ).length > 0 ) {

			$( '.peThemeContactForm' ).peThemeContactForm();

		}

		if ( $nav.length ) {

			$nav
				.addClass( 'nav navbar-right navbar-nav' )
				.find( '.dropdown' )
					.children( 'a' )
						.addClass( 'dropdown-toggle' )
						.attr( 'data-toggle', 'dropdown' )
						.children( 'b' )
							.remove()
							.end()
						.append( '<i class="fa fa-angle-down"></i>' );

			$nav
				.find( 'a' )
					.each( function() {

						var $this = $( this );

						if ( '' === $this.prop( 'hash' ) || ! ( window.location.pathname === $this.prop( 'pathname' ) && window.location.origin === $this.prop( 'origin' ) ) ) {

							$this.addClass( 'is-external' );

						}

					});
			
			/*$body.on( 'click', 'a', function( e ) {

				var $this = $( this );

				if ( window.location.href.replace( window.location.hash, '' ) === $this.attr( 'href' ) ) {

					$( 'html, body' ).animate({ scrollTop: 0 }, 650 );

					e.preventDefault();

				}

			});*/

		}

		// comments markup fix
		$( '.row-fluid' ).addClass( 'row' );

		$( '.vendor' ).fitVids();

		var $comments_wrap = $( '#comments' );

		if ( $comments_wrap.length ) {

			$comments_wrap.find( '.span1' ).addClass( 'col-md-1' ).removeClass( 'span1' );
			$comments_wrap.find( '.span11' ).addClass( 'col-md-11' ).removeClass( 'span11' );
			$comments_wrap.find( '.span12' ).addClass( 'col-md-12' ).removeClass( 'span12' );
			$comments_wrap.find( '.row-fluid' ).addClass( 'row' ).removeClass( 'row-fluid' );
			$comments_wrap.find( '.pe-offset1' ).addClass( 'col-md-offset-1' ).removeClass( 'pe-offset1' );
			$comments_wrap.find( '.comment-meta' ).wrapInner( '<h6></h6>' );
			$comments_wrap.find( '.comment-reply-link' ).removeClass( 'label' );
			$comments_wrap.find( 'button[type="submit"]' ).attr( 'class', 'btn btn-primary btn-sm' );
			$comments_wrap.find( 'input, textarea' ).addClass( 'form-control' );
			$comments_wrap.find( '#commentform > .control-group' ).not( '.comment-form-comment' ).wrapAll( '<div class="form-group" />' );
			$comments_wrap.find( '#commentform .control-group' ).not( '.comment-form-comment' ).addClass( 'col-md-4 margin-bottom-sm-20' ).children( 'label' ).each( function() { var $this = $( this ); $this.prependTo( $this.parent() ); });
			$comments_wrap.find( '#commentform > .comment-form-comment' ).addClass( 'form-group' ).children().addClass( 'col-xs-12' );
			$comments_wrap.find( '#commentform' ).addClass( 'form-horizontal' );

		}

		$window.load( function() {

			$window.resize();


			$window.resize( function() {

				$window.scroll();

			});

		});

		$window.resize();

	});

})(jQuery);