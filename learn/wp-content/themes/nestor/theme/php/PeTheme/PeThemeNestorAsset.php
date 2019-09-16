<?php

class PeThemeNestorAsset extends PeThemeAsset  {

	public function __construct(&$master) {

		$this->minifiedJS = 'theme/compressed/theme.min.js';
		$this->minifiedCSS = 'theme/compressed/theme.min.css';

		//define( 'PE_THEME_LOCAL_VIDEO_SUPPORT',true);

		parent::__construct($master);
		
	}

	public function registerAssets() {

		add_filter( 'pe_theme_js_init_file',array(&$this, 'pe_theme_js_init_file_filter' ));
		add_filter( 'pe_theme_js_init_deps',array(&$this, 'pe_theme_js_init_deps_filter' ));
		add_filter( 'pe_theme_minified_js_deps',array(&$this, 'pe_theme_minified_js_deps_filter' ));
		
		parent::registerAssets();

		if ($this->minifyCSS) {

			$deps = array(
				'pe_theme_compressed',
			);

		} else {

			// theme styles
			$this->addStyle( 'css/bootstrap.min.css',array(), 'pe_theme_nestor-bootstrap' );
			$this->addStyle( 'css/flexslider.css',array(), 'pe_theme_nestor-flexslider' );
			$this->addStyle( 'css/ionicons.min.css',array(), 'pe_theme_nestor-ionicons' );
			$this->addStyle( 'css/venobox.css',array(), 'pe_theme_nestor-venobox' );
			$this->addStyle( 'css/style.css',array(), 'pe_theme_nestor-style' );
			$this->addStyle( 'css/color/blue.css',array(), 'pe_theme_nestor-color_blue' );
			$this->addStyle( 'css/blog.css',array(), 'pe_theme_nestor-blog' );
			$this->addStyle( 'css/custom.css',array(), 'pe_theme_nestor-custom' );

			$deps = array(
				'pe_theme_nestor-bootstrap',
				'pe_theme_nestor-flexslider',
				'pe_theme_nestor-ionicons',
				'pe_theme_nestor-venobox',
				'pe_theme_nestor-style',
				'pe_theme_nestor-color_blue',
				'pe_theme_nestor-blog',
				'pe_theme_nestor-custom',
			);

		}

		$this->addStyle( 'style.css',$deps, 'pe_theme_init' );

		$this->addScript( 'theme/js/pe/pixelentity.controller.js', array(
			//'pe_theme_mobile',
			'pe_theme_utils_browser',
			'pe_theme_selectivizr',
			'pe_theme_lazyload',
			//'pe_theme_flare',
			'pe_theme_widgets_contact',
			'pe_theme_nestor-bootstrap',
			'pe_theme_nestor-flexverticalcenter',
			'pe_theme_nestor-flexslider',
			'pe_theme_nestor-stellar',
			'pe_theme_nestor-mixitup',
			'pe_theme_nestor-waypoints',
			'pe_theme_nestor-waypoints_sticky',
			'pe_theme_nestor-venobox',
			'pe_theme_nestor-app',
			'pe_theme_nestor-fitvids',
			'pe_theme_nestor-custom',
		), 'pe_theme_controller' );

		$this->addScript( 'js/bootstrap.min.js',array(), 'pe_theme_nestor-bootstrap' );
		$this->addScript( 'js/jquery.flexverticalcenter.js',array(), 'pe_theme_nestor-flexverticalcenter' );
		$this->addScript( 'js/jquery.flexslider-min.js',array(), 'pe_theme_nestor-flexslider' );
		$this->addScript( 'js/jquery.stellar.min.js',array(), 'pe_theme_nestor-stellar' );
		$this->addScript( 'js/jquery.mixitup.min.js',array(), 'pe_theme_nestor-mixitup' );
		$this->addScript( 'js/waypoints.min.js',array(), 'pe_theme_nestor-waypoints' );
		$this->addScript( 'js/waypoints-sticky.min.js',array(), 'pe_theme_nestor-waypoints_sticky' );
		$this->addScript( 'js/venobox.min.js',array(), 'pe_theme_nestor-venobox' );
		$this->addScript( 'js/app.js',array(), 'pe_theme_nestor-app' );
		$this->addScript( 'js/jquery.fitvids.js',array(), 'pe_theme_nestor-fitvids' );
		$this->addScript( 'js/custom.js',array(), 'pe_theme_nestor-custom' );
		
	}

	public function pe_theme_js_init_file_filter( $js ) {

		return $js;
		//return 'js/custom.js';

	}

	public function pe_theme_js_init_deps_filter( $deps ) {

		return $deps;
		/*
		  return array(
		  'jquery',
		  );
		*/
	}

	public function pe_theme_minified_js_deps_filter( $deps ) {

		return $deps;
		//return array( 'jquery' );

	}

	public function style() {

		bloginfo( 'stylesheet_url' ); 

	}

	public function enqueueAssets() {

		$this->registerAssets();

		$t =& peTheme();

		if ( $this->minifyJS && file_exists( PE_THEME_PATH . '/preview/init.js' ) ) {

			$this->addScript( 'preview/init.js', array( 'jquery' ), 'pe_theme_preview_init' );
			
			wp_localize_script( 'pe_theme_preview_init', 'o', array(
			//'dark' => PE_THEME_URL.'/css/dark_skin.css',
				'css' => $this->master->color->customCSS( true, 'color1' )
			) );

			wp_enqueue_script( 'pe_theme_preview_init' );

		}	

		wp_enqueue_style( 'pe_theme_init' );
		wp_enqueue_script( 'pe_theme_init' );

		wp_localize_script( 'pe_theme_init', '_nestor', array(
			'ajax-loading' => PE_THEME_URL . '/images/ajax-loader.gif',
			'home_url'     => home_url( '/' ),
		) );

		if ( $this->minifyJS && file_exists( PE_THEME_PATH . '/preview/preview.js' ) ) {

			$this->addScript( 'preview/preview.js',array( 'pe_theme_init' ), 'pe_theme_skin_chooser' );

			wp_localize_script( 'pe_theme_skin_chooser', 'pe_skin_chooser', array( 'url' => urlencode( PE_THEME_URL . '/' ) ) );
			wp_enqueue_script( 'pe_theme_skin_chooser' );

		}

		wp_enqueue_script( 'pe_theme_nestor-gmap', 'http://maps.googleapis.com/maps/api/js?sensor=false' );

	}


}