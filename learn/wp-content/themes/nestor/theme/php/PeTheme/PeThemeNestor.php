<?php

class PeThemeNestor extends PeThemeController {

	public $preview = array();

	public function __construct() {

		// custom post types
		add_action("pe_theme_custom_post_type",array(&$this,"pe_theme_custom_post_type"));

		// wp_head stuff
		add_action("pe_theme_wp_head",array(&$this,"pe_theme_wp_head"));

		// google fonts
		add_filter("pe_theme_font_variants",array(&$this,"pe_theme_font_variants_filter"),10,2);

		// menu
		add_filter("pe_theme_menu_item_after",array(&$this,"pe_theme_menu_item_after_filter"),10,3);

		// custom menu fields
		add_filter("pe_theme_menu_custom_fields",array(&$this,"pe_theme_menu_custom_fields_filter"),10,3);

		// social links
		add_filter("pe_theme_social_icons",array(&$this,"pe_theme_social_icons_filter"));
		add_filter("pe_theme_content_get_social_link",array(&$this,"pe_theme_content_get_social_link_filter"),10,4);

		// comment submit button class
		add_filter("pe_theme_comment_submit_class",array(&$this,"pe_theme_comment_submit_class_filter"));

		// use prio 30 so gets executed after standard theme filter
		add_filter("the_content_more_link",array(&$this,"the_content_more_link_filter"),30);

		// remove junk from project screen
		add_action('pe_theme_metabox_config_project',array(&$this,'pe_theme_nestor_metabox_config_project'),200);

		// add featured image to testimonial
		add_action('init',array(&$this,'pe_theme_nestor_testimonial_supports'),200);

		// shortcodes
		add_filter("pe_theme_shortcode_columns_mapping",array(&$this,"pe_theme_shortcode_columns_mapping_filter"));
		add_filter("pe_theme_shortcode_columns_options",array(&$this,"pe_theme_shortcode_columns_options_filter"));
		add_filter("pe_theme_shortcode_columns_container",array(&$this,"pe_theme_shortcode_columns_container_filter"),10,2);

		// portfolio
		add_filter("pe_theme_filter_item",array(&$this,"pe_theme_project_filter_item_filter"),10,4);

		// remove staff meta
		add_action('pe_theme_metabox_config_staff',array(&$this,'pe_theme_metabox_config_staff_action'),11);

		// alter services meta
		add_action('pe_theme_metabox_config_service',array(&$this,'pe_theme_metabox_config_service_action'),11);

		// custom meta for gallery images
		add_filter( 'pe_theme_gallery_image_fields', array( $this, 'pe_theme_gallery_image_fields_filter' ) );

		// custom homepage meta js
		add_action( 'admin_enqueue_scripts', array( $this, 'pe_theme_nestor_custom_meta_js' ) );

		// font awesome admin picker
		add_action( 'admin_enqueue_scripts', array( $this, 'pe_theme_font_awesome_icons' ) );

		// custom video metabox
		add_action('pe_theme_metabox_config_video',array(&$this,'pe_theme_metabox_config_video'),99);

		// builder
		add_filter('pe_theme_view_layout_open',array(&$this,'pe_theme_view_layout_no_parent'));
		add_filter('pe_theme_view_layout_close',array(&$this,'pe_theme_view_layout_no_parent'));
		add_filter('pe_theme_layoutmodule_open',array(&$this,'pe_theme_view_layout_no_parent'));
		add_filter('pe_theme_layoutmodule_close',array(&$this,'pe_theme_view_layout_no_parent'));

		// header versions for demo
		add_filter( 'pe_theme_nestor_header_type', array( $this, 'pe_theme_nestor_header_type_filter' ) );

		// menu wrapper
		add_filter( 'pe_theme_menu_items_wrap', array( $this, 'pe_theme_menu_items_wrap_filter' ) );

	}

	public function pe_theme_nestor_header_type_filter( $header ) {

		if ( ! defined( 'PE_THEME_DEMO_SITE' ) || ! PE_THEME_DEMO_SITE ) return $header; //not a preview

		if ( empty( $_GET['header_type'] )  ) return $header; //manual header not set

		return sanitize_key( $_GET['header_type'] );

	}

	public function pe_theme_menu_items_wrap_filter( $html ) {

		return '<ul class="nav navbar-right navbar-nav">%3$s</ul>';

	}

	public function pe_theme_view_layout_no_parent($markup) {
		return "";
	}

	public function pe_theme_nestor_custom_meta_js() {

		PeThemeAsset::addScript("js/nestor-homepage-meta.js",array('jquery'),"pe_theme_nestor_homepage_meta");

		$screen = get_current_screen();

		if ( is_admin() && ( 'page' === $screen->post_type || 'post' === $screen->post_type ) ) {
			wp_enqueue_script("pe_theme_nestor_homepage_meta");
		}

	}

	public function pe_theme_font_awesome_icons() {

		PeThemeAsset::addStyle("css/ionicons.min.css",array(),"pe_theme_ionicons");

		$screen = get_current_screen();

		if ( is_admin() && ( 'page' === $screen->post_type || 'post' === $screen->post_type || 'project' === $screen->post_type ) ) {

			wp_enqueue_style( 'pe_theme_ionicons' );

		}

	}

	public function the_content_more_link_filter($link) {
		return sprintf('<a class="read-more-link blog-post-more" href="%s">%s</a>',get_permalink(),__("Read more",'Pixelentity Theme/Plugin'));
	}

	public function pe_theme_project_filter_item_filter( $html, $aclass, $slug, $name ) {
		return sprintf( '<li class="filter" data-filter="%s"><a href="#">%s</a></li>', '' === $slug ? 'all' : "filter-$slug", $name );
	}

	public function pe_theme_wp_head() {
		$this->font->apply();
		$this->color->apply();

		// custom CSS field
		if ($customCSS = $this->options->get("customCSS")) {
			printf('<style type="text/css">%s</style>',stripslashes($customCSS));
		}

		// custom JS field
		if ($customJS = $this->options->get("customJS")) {
			printf('<script type="text/javascript">%s</script>',stripslashes($customJS));
		}

	}

	public function pe_theme_font_variants_filter($variants,$font) {
		if ($font === "Open Sans") {
			$variants="$font:400italic,300,400,700,800,100";
		}
		else if ($font === "Lato") {
			$variants="$font:100,200,300,700";
		}
		else if ($font === "Montserrat") {
			$variants="$font:400,700";
		}
		else if ($font === "Volkhov") {
			$variants="$font:400italic,700italic,400,700";
		}
		else if ($font === "Roboto") {
			$variants="$font:400,300,700,400italic,700italic,300italic,100";
		}
		else if ($font === 'Bitter') {
			$variants="$font:regular:italic:bold";
		}

		return $variants;
	}

	public function pe_theme_menu_custom_fields_filter($options,$depth = false,$item = false) {

		/*if (!empty($item->object) && $item->object != "page") {
			// if menu item is not a page, no custom option
			return $options;
		}

		$options =
			array(
				  "name" => 
				  array(
						"label"=>__("Section",'Pixelentity Theme/Plugin'),
						"type"=>"Text",
						"description" => __("Optional section link name.",'Pixelentity Theme/Plugin'),
						"default"=> ""
						)
				  );

		*/
		return $options;

	}

	public function pe_theme_menu_item_after_filter($after,$item,$depth) {
		if ($item->object == 'page' && !empty($item->pe_meta->name)) {
			$section = strtr($item->pe_meta->name,array('#' => ''));
			$item->url .= "#section-$section";
		}
		return $after;
	}

	public function pe_theme_social_icons_filter($icons = null) {
		return array(
			// label => icon | tooltip text
			__( 'Android' ,'Pixelentity Theme/Plugin')       => 'ion-social-android|Android',
			__( 'Apple' ,'Pixelentity Theme/Plugin')         => 'ion-social-apple|Apple',
			__( 'Bitcoin' ,'Pixelentity Theme/Plugin')       => 'ion-social-bitcoin|Bitcoin',
			__( 'Buffer' ,'Pixelentity Theme/Plugin')        => 'ion-social-buffer|Buffer',
			__( 'Designer News' ,'Pixelentity Theme/Plugin') => 'ion-social-designernews|Designer News',
			__( 'Dribbble' ,'Pixelentity Theme/Plugin')      => 'ion-social-dribbble-outline|Dribbble',
			__( 'Dropbox' ,'Pixelentity Theme/Plugin')       => 'ion-social-dropbox|Dropbox',
			__( 'Facebook' ,'Pixelentity Theme/Plugin')      => 'ion-social-facebook|Facebook',
			__( 'Foursquare' ,'Pixelentity Theme/Plugin')    => 'ion-social-foursquare|Foursquare',
			__( 'Github' ,'Pixelentity Theme/Plugin')        => 'ion-social-github|Github',
			__( 'Google+' ,'Pixelentity Theme/Plugin')       => 'ion-social-googleplus|Google+',
			__( 'Hacker News' ,'Pixelentity Theme/Plugin')   => 'ion-social-hackernews|Hacker News',
			__( 'Instagram' ,'Pixelentity Theme/Plugin')     => 'ion-social-instagram|Instagram',
			__( 'LinkedIn' ,'Pixelentity Theme/Plugin')      => 'ion-social-linkedin|LinkedIn',
			__( 'Linux' ,'Pixelentity Theme/Plugin')         => 'ion-social-tux|Linux',
			__( 'Pinterest' ,'Pixelentity Theme/Plugin')     => 'ion-social-pinterest|Pinterest',
			__( 'Reddit' ,'Pixelentity Theme/Plugin')        => 'ion-social-reddit|Reddit',
			__( 'RSS' ,'Pixelentity Theme/Plugin')           => 'ion-social-rss|Rss',
			__( 'Skype' ,'Pixelentity Theme/Plugin')         => 'ion-social-skype|Skype',
			__( 'Tumblr' ,'Pixelentity Theme/Plugin')        => 'ion-social-tumblr|Tumblr',
			__( 'Twitter' ,'Pixelentity Theme/Plugin')       => 'ion-social-twitter|Twitter',
			__( 'Vimeo' ,'Pixelentity Theme/Plugin')         => 'ion-social-vimeo|Vimeo',
			__( 'Windows' ,'Pixelentity Theme/Plugin')       => 'ion-social-windows|Windows',
			__( 'WordPress' ,'Pixelentity Theme/Plugin')     => 'ion-social-wordpress|WordPress',
			__( 'Yahoo' ,'Pixelentity Theme/Plugin')         => 'ion-social-yahoo|Yahoo',
			__( 'YouTube' ,'Pixelentity Theme/Plugin')       => 'ion-social-youtube|YouTube',
		);
	}

	public function pe_theme_content_get_social_link_filter($html,$link,$tooltip,$icon) {
		return sprintf('<li><a href="%s" target="_blank" title="%s"><i class="%s"></i></a></li>',$link,$tooltip,$icon);
	}

	public function pe_theme_comment_submit_class_filter() {
		return "contour-btn red";
	}

	public function init() {
		parent::init();

		if (PE_THEME_PLUGIN_MODE) {
			return;
		}
		
		if ($this->options->get("retina") === "yes") {
			add_filter("pe_theme_resized_img",array(&$this,"pe_theme_resized_retina_filter"),10,5);
		} else if ($this->options->get("lazyImages") === "yes") {
			add_filter("pe_theme_resized_img",array(&$this,"pe_theme_resized_img_filter"),10,4);
		}
	}

	public function pe_theme_custom_post_type() {
		$this->gallery->cpt();
		$this->video->cpt();
		$this->project->cpt();
		//$this->ptable->cpt();
		//$this->staff->cpt();
		//$this->service->cpt();
		//$this->testimonial->cpt();
		//$this->logo->cpt();
		//$this->slide->cpt();
		//$this->view->cpt();

	}

	public function pe_theme_shortcode_columns_mapping_filter($array) {
			return array(
				'1/1' => 'col-sm-12',
				"1/3" => "col-sm-4",
				"1/2" => "col-sm-6",
				"1/4" => "col-sm-3",
				"2/3" => "col-sm-8",
				"1/6" => "col-sm-2",
				"last" => '',
			);
		}

	public function pe_theme_shortcode_columns_options_filter($array) {
		unset($array['2 Column layouts']['5/6 1/6']);
		unset($array['2 Column layouts']['1/6 5/6']);
		unset($array['2 Column layouts']['1/4 3/4']);
		unset($array['2 Column layouts']['3/4 1/4']);
		unset($array['3 Column layouts']['1/4 1/4 2/4']);
		unset($array['3 Column layouts']['2/4 1/4 1/4']);

		$single['Single column layout']['1/1'] = '1/1';

		$array = 
			array_merge(
						$single,
						$array
						);
		//unset($array['4 Column layouts']);
		//unset($array['6 Column layouts']);

		return $array;
	}

	public function pe_theme_shortcode_columns_container_filter( $template, $content ) {

		return sprintf('<div class="row">%s</div>',$content);

	}


	public function boot() {
		parent::boot();

		
		PeGlobal::$config["content-width"] = 990;
		PeGlobal::$config["post-formats"] = array("video","gallery");
		PeGlobal::$config["post-formats-project"] = array("video","gallery");

		PeGlobal::$config["image-sizes"]["thumbnail"] = array(120,90,true);
		PeGlobal::$config["image-sizes"]["post-thumbnail"] = array(260,200,false);
		

		// blog layouts
		PeGlobal::$config["blog"] = array(
			__("Default",'Pixelentity Theme/Plugin')   => "",
			__("Search",'Pixelentity Theme/Plugin')    => "search",
			__("Alternate",'Pixelentity Theme/Plugin') => "project",
		);

		PeGlobal::$config["shortcodes"] = array(
			'NestorAlert',
			'NestorButton',
			'NestorIcon',
			'NestorLabel',
			'NestorPanel',
			'NestorProgress',
			'NestorWell',
			'NestorAccordion',
			'NestorTabs',
			'BS_Columns',
			'BS_Video',
		);

		PeGlobal::$config["views"] = array(
			"LayoutModuleNestorAbout",
			"LayoutModuleNestorBlog",
			"LayoutModuleNestorCalltoaction",
			"LayoutModuleNestorColumns",
			"LayoutModuleNestorContact",
			"LayoutModuleNestorFeature",
			"LayoutModuleNestorHighlight",
			"LayoutModuleNestorIcons",
			"LayoutModuleNestorPanel",
			"LayoutModuleNestorPanels",
			"LayoutModuleNestorPartner",
			"LayoutModuleNestorPartners",
			"LayoutModuleNestorPortfolio",
			"LayoutModuleNestorPricingTable",
			"LayoutModuleNestorPricingTables",
			"LayoutModuleNestorRecentPosts",
			"LayoutModuleNestorRecentWork",
			"LayoutModuleNestorService",
			"LayoutModuleNestorServices",
			"LayoutModuleNestorStatistic",
			"LayoutModuleNestorStats",
			"LayoutModuleNestorTeamMember",
			"LayoutModuleNestorTeamMembers",
			"LayoutModuleNestorTestimonial",
			"LayoutModuleNestorTestimonials",
			"LayoutModuleNestorText",
		);

		PeGlobal::$config["sidebars"] = array(
			"default" => __("Default post/page",'Pixelentity Theme/Plugin'),
			//"footer" => __("Footer Widgets",'Pixelentity Theme/Plugin'),
		);

		PeGlobal::$config["colors"] = array(
			"color1" => array(
				"label"     => __("Primary Color",'Pixelentity Theme/Plugin'),
				"selectors" => array(
					"a" => "color",
					".btn-blue" => "color",
					".text-color-blue" => "color",
					".nav-pills > li > a:hover" => "color",
					".nav-pills > li > a:focus" => "color",
					".nav-pills > li.active > a" => "color",
					".nav-pills > li.active > a:hover" => "color",
					".nav-pills > li.active > a:focus" => "color",
					".nestor-main-menu .active > a" => "color",
					".nestor-main-menu .nav .open > a" => "color",
					".nestor-main-menu .nav .open > a:hover" => "color",
					".nestor-main-menu .nav .open > a:focus" => "color",
					".nestor-main-menu .nav > li > a:hover" => "color",
					".nestor-main-menu .nav > li > a:focus" => "color",
					".nestor-main-menu .nav > li:hover > a" => "color",
					".nestor-main-menu .nav > li:focus > a" => "color",
					".nestor-main-menu .dropdown-menu > .active > a" => "color",
					".nestor-main-menu .dropdown-menu > li > a:hover" => "color",
					".nestor-main-menu .dropdown-menu > li > a:focus" => "color",
					".social-networks-top-header a:hover" => "color",
					".social-networks-top-header a:focus" => "color",
					".social-networks-top-header a:active" => "color",
					".social-networks-footer a:hover" => "color",
					".social-networks-footer a:focus" => "color",
					".social-networks-footer a:active" => "color",
					".pagination > li > a:hover" => "color",
					".pagination > li > span:hover" => "color",
					".pagination > li > a:focus" => "color",
					".pagination > li > span:focus" => "color",
					".pagination > .active > a" => "color",
					".pagination > .active > span" => "color",
					".pagination > .active > a:hover" => "color",
					".pagination > .active > span:hover" => "color",
					".pagination > .active > a:focus" => "color",
					".pagination > .active > span:focus" => "color",
					".nav-tabs > li > a:hover" => "color",
					".nav-tabs > li.active > a" => "color",
					".nav-tabs > li.active > a:hover" => "color",
					".nav-tabs > li.active > a:focus" => "color",
					".nav-tabs.nav-justified > li > a:hover" => "color",
					".nav-tabs.nav-justified > .active > a" => "color",
					".nav-tabs.nav-justified > .active > a:hover" => "color",
					".nav-tabs.nav-justified > .active > a:focus" => "color",
					".btn-primary" => "color",
					
					".open .dropdown-toggle.btn-primary" => "color",
					".btn-link" => "color",
					".text-color-light .social-networks-footer a:hover" => "color",
					".text-color-light .social-networks-footer a:focus" => "color",
					".text-color-light .social-networks-footer a:active" => "color",
					".text-color-theme" => "color",

					".pricing-tables-1 .pricing-featured .pricing-table-price" => "background-color",
					".pricing-table-blue .pricing-table-price" => "background-color",
					"a.btn-blue:hover" => "background-color",
					"a.btn-blue:focus" => "background-color",
					"a.btn-blue:active" => "background-color",
					"a.btn-blue.active" => "background-color",
					"* .open .dropdown-toggle.btn-blue" => "background-color",
					".overlay-color-theme" => "background-color:0.8",
					".bg-color-blue" => "background-color",
					".nestor-main-menu button.navbar-toggle" => "background-color",
					".portfolio-overlay" => "background-color:0.8",
					".our-work-1-overlay" => "background-color:0.8",
					".team-item-overlay" => "background-color:0.8",
					".pricing-tables-1 .pricing-table-promotional .pricing-table-price" => "background-color",
					".bg-color-theme" => "background-color",
					".btn-primary:hover" => "background-color",
					".btn-primary:focus" => "background-color",
					".btn-primary:active" => "background-color",
					".btn-primary.active" => "background-color",

					"a.btn-blue" => "border-color",
					".btn-blue:hover" => "border-color",
					".btn-blue:focus" => "border-color",
					".btn-blue:active" => "border-color",
					".btn-blue.active" => "border-color",
					".open .dropdown-toggle.btn-blue" => "border-color",
					".nestor-main-menu .navbar-toggle" => "border-color",
					".nav-tabs > li.active >a" => "border-color",
					".nav-tabs > li.active >a:hover" => "border-color",
					".nav-tabs > li.active >a:focus" => "border-color",
					".nav-tabs.nav-justified > .active >a" => "border-color",
					".nav-tabs.nav-justified > .active >a:hover" => "border-color",
					".nav-tabs.nav-justified > .active >a:focus" => "border-color",
					".panel-primary > .panel-heading" => "border-color",
					"* .btn-primary" => "border-color",
					"* .btn-primary:hover" => "border-color",
					"* .btn-primary:focus" => "border-color",
					"* .btn-primary:active" => "border-color",
					"* .btn-primary.active" => "border-color",
					"* .open .dropdown-toggle.btn-primary" => "border-color",
					".form-control:focus" => "border-color",
					".post.sticky h2 a" => "border-color",

				),
				"default" => "#2ac5ee",
			),
		);
		

		PeGlobal::$config["fonts"] = array(
			"fontBody" => array(
				"label"     => __("General Font",'Pixelentity Theme/Plugin'),
				"selectors" => array(
					"body",
					".lato-font",
				),
				"default" => "Lato",
			),
			"fontHeadings" => array(
				"label"     => __("Headings Font",'Pixelentity Theme/Plugin'),
				"selectors" => array(
					"h1",
					"h2",
					"h3",
					"h4",
					"h5",
					"h6",
					".bitter-font",
				),
				"default" => "Bitter",
			),
		);		

		$options = array();

		$galleries = $this->gallery->option();

		$none = array( __("None",'Pixelentity Theme/Plugin') => '-1' );

		$galleries = array_merge( $none, $galleries );

		$options = array_merge( $options, array(
			"import_demo" => $this->defaultOptions["import_demo"],
			"logo" => array(
				"label"       => __("Logo",'Pixelentity Theme/Plugin'),
				"type"        => "Upload",
				"section"     => __("General",'Pixelentity Theme/Plugin'),
				"description" => __("This is the main site logo image. The image should be a .png file.",'Pixelentity Theme/Plugin'),
				"default"     => '',
			),
			"siteTitle" => array(
				"wpml"        => true,
				"label"       => __("Header Title",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"section"     => __("General",'Pixelentity Theme/Plugin'),
				"description" => __("Used if logo is left empty.",'Pixelentity Theme/Plugin'),
				"default"     => "Nestor",
			),
			"favicon" => array(
				"label"       => __("Favicon",'Pixelentity Theme/Plugin'),
				"type"        => "Upload",
				"section"     => __("General",'Pixelentity Theme/Plugin'),
				"description" => __("This is the favicon for your site. The image can be a .jpg, .ico or .png with dimensions of 16x16px ",'Pixelentity Theme/Plugin'),
				"default"     => PE_THEME_URL."/favicon.png",
			),
			"bodyLayout" => array(
				"label"       => __("Layout",'Pixelentity Theme/Plugin'),
				"type"        => "RadioUI",
				"section"     => __("General",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__("Full width",'Pixelentity Theme/Plugin') => 'fullscreen',
					__("Boxed",'Pixelentity Theme/Plugin')      => 'boxed',
				),
				"description" => __("Choose between two body widths.",'Pixelentity Theme/Plugin'),
				"default"     => "fullscreen",
			),
			"customCSS" => $this->defaultOptions["customCSS"],
			"customJS"  => $this->defaultOptions["customJS"],
			"colors"    => array(
				"label"       => __("Custom Colors",'Pixelentity Theme/Plugin'),
				"type"        => "Help",
				"section"     => __("Colors",'Pixelentity Theme/Plugin'),
				"description" => __("In this page you can set alternative colors for the main colored elements in this theme. One color options has been provided. To change the color used on these elements simply write a new hex color reference number into the fields below or use the color picker which appears when each field obtains focus. Once you have selected your desired colors make sure to save them by clicking the <b>Save All Changes</b> button at the bottom of the page. Then just refresh your page to see the changes.<br/><br/><b>Please Note:</b> Some of the elements in this theme are made from images (Eg. Icons) and these items may have a color. It is not possible to change these elements via this page, instead such elements will need to be changed manually by opening the images/icons in an image editing program and manually changing their colors to match your theme's custom color scheme. <br/><br/>To return all colors to their default values at any time just hit the <b>Restore Default</b> link beneath each field.",'Pixelentity Theme/Plugin'),
			),
			"googleFonts" => array(
				"label"       => __("Custom Fonts",'Pixelentity Theme/Plugin'),
				"type"        => "Help",
				"section"     => __("Fonts",'Pixelentity Theme/Plugin'),
				"description" => __("In this page you can set the typefaces to be used throughout the theme. For each elements listed below you can choose any front from the Google Web Font library. Once you have chosen a font from the list, you will see a preview of this font immediately beneath the list box. The icons on the right hand side of the font preview, indicate what weights are available for that typeface.<br/><br/><strong>R</strong> -- Regular,<br/><strong>B</strong> -- Bold,<br/><strong>I</strong> -- Italics,<br/><strong>BI</strong> -- Bold Italics<br/><br/>When decideing what font to use, ensure that the chosen font contains the font weight required by the element. For example, main headings are bold, so you need to select a new font for these elements which supports a bold font weight. If you select a font which does not have a bold icon, the font will not be applied. <br/><br/>Browse the online <a href='http://www.google.com/webfonts'>Google Font Library</a><br/><br/><b>Custom fonts</b> (Advanced Users):<br/> Other then those available from Google fonts, custom fonts may also be applied to the elements listed below. To do this an additional field is provided below the google fonts list. Here you may enter the details of a font family, size, line-height etc. for a custom font. This information is entered in the form of the shorthand 'font:' CSS declaration, for example:<br/><br/><b>bold italic small-caps 1em/1.5em arial,sans-serif</b><br/><br/>If a font is specified in this field then the font listed in the Google font drop menu above will not be applied to the element in question. If you wish to use the Google font specified in the drop down list and just specify a new font size or line height, you can do so in this field also, however the name of the Google font <b>MUST</b> also be entered into this field. You may need to visit the Google fonts web page to find the exact CSS name for the font you have chosen.",'Pixelentity Theme/Plugin'),
			),
			"contactEmail" => $this->defaultOptions["contactEmail"],
			"contactSubject" => $this->defaultOptions["contactSubject"],
			"headerType" => array(
				"label"       => __("Header Type",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"section"     => __("Header",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__("Default",'Pixelentity Theme/Plugin')       => 'default',
					__("Sticky",'Pixelentity Theme/Plugin')        => 'sticky',
					__("Centered logo",'Pixelentity Theme/Plugin') => 'centered',
					__("Detailed",'Pixelentity Theme/Plugin')      => 'detailed',
				),
				"description" => __("Choose between multiple header designs.",'Pixelentity Theme/Plugin'),
				"default"     => "default",
			),
			"headerText" => array(
				"label"        => __("Header text",'Pixelentity Theme/Plugin'),
				"type"         => "Items",
				"section"      => __("Header",'Pixelentity Theme/Plugin'),
				"description"  => __("Add one or text snippets displayed at the top left area of your Detailed header.",'Pixelentity Theme/Plugin'),
				"button_label" => __("Add Text",'Pixelentity Theme/Plugin'),
				"sortable"     => true,
				"auto"         => __("Lorem ipsum",'Pixelentity Theme/Plugin'),
				"unique"       => false,
				"editable"     => false,
				"legend"       => false,
				"fields"       => array(
					array(
						"name"    => "text",
						"type"    => "text",
						"width"   => 500, 
						"default" => __("Lorem ipsum",'Pixelentity Theme/Plugin'),
					),
				),
				"default" => "",
			),
			"headerSocialLinks" => array(
				"label"        => __("Social Profile Links",'Pixelentity Theme/Plugin'),
				"type"         => "Items",
				"section"      => __("Header",'Pixelentity Theme/Plugin'),
				"description"  => __("Add one or more social network icons to the top of your Detailed header.",'Pixelentity Theme/Plugin'),
				"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
				"sortable"     => true,
				"auto"         => __("Social Network Name",'Pixelentity Theme/Plugin'),
				"unique"       => false,
				"editable"     => false,
				"legend"       => false,
				"fields"       => array(
					array(
						"label"   => __("Social Network",'Pixelentity Theme/Plugin'),
						"name"    => "name",
						"options" => array(
							__( 'Android' ,'Pixelentity Theme/Plugin')       => 'android',
							__( 'Apple' ,'Pixelentity Theme/Plugin')         => 'apple',
							__( 'Bitcoin' ,'Pixelentity Theme/Plugin')       => 'bitcoin',
							__( 'Buffer' ,'Pixelentity Theme/Plugin')        => 'buffer',
							__( 'Designer News' ,'Pixelentity Theme/Plugin') => 'designernews',
							__( 'Dribbble' ,'Pixelentity Theme/Plugin')      => 'dribbble-outline',
							__( 'Dropbox' ,'Pixelentity Theme/Plugin')       => 'dropbox',
							__( 'Facebook' ,'Pixelentity Theme/Plugin')      => 'facebook',
							__( 'Foursquare' ,'Pixelentity Theme/Plugin')    => 'foursquare',
							__( 'Github' ,'Pixelentity Theme/Plugin')        => 'github',
							__( 'Google+' ,'Pixelentity Theme/Plugin')       => 'googleplus',
							__( 'Hacker News' ,'Pixelentity Theme/Plugin')   => 'hackernews',
							__( 'Instagram' ,'Pixelentity Theme/Plugin')     => 'instagram',
							__( 'LinkedIn' ,'Pixelentity Theme/Plugin')      => 'linkedin',
							__( 'Linux' ,'Pixelentity Theme/Plugin')         => 'tux',
							__( 'Pinterest' ,'Pixelentity Theme/Plugin')     => 'pinterest',
							__( 'Reddit' ,'Pixelentity Theme/Plugin')        => 'reddit',
							__( 'RSS' ,'Pixelentity Theme/Plugin')           => 'rss',
							__( 'Skype' ,'Pixelentity Theme/Plugin')         => 'skype',
							__( 'Tumblr' ,'Pixelentity Theme/Plugin')        => 'tumblr',
							__( 'Twitter' ,'Pixelentity Theme/Plugin')       => 'twitter',
							__( 'Vimeo' ,'Pixelentity Theme/Plugin')         => 'vimeo',
							__( 'Windows' ,'Pixelentity Theme/Plugin')       => 'windows',
							__( 'WordPress' ,'Pixelentity Theme/Plugin')     => 'wordpress',
							__( 'Yahoo' ,'Pixelentity Theme/Plugin')         => 'yahoo',
							__( 'YouTube' ,'Pixelentity Theme/Plugin')       => 'youtube',
						),
						"type"    => "select",
						"width"   => 185,
						"default" => __("Twitter",'Pixelentity Theme/Plugin'),
					),
					array(
						"name"    => "url",
						"type"    => "text",
						"width"   => 300, 
						"default" => "#",
					),
				),
				"default" => "",
			),
			"footerAddress" => array(
				"label"       => __("Address",'Pixelentity Theme/Plugin'),
				"wpml"        =>  true,
				"type"        => "Text",
				"section"     => __("Footer",'Pixelentity Theme/Plugin'),
				"description" => __("Displayed below the location icon in the footer.",'Pixelentity Theme/Plugin'),
				"default"     => '795 Fake Ave, Door 6<br>Wonderland, CA 94107',
			),
			"footerEmail" => array(
				"label"       => __("Email address",'Pixelentity Theme/Plugin'),
				"wpml"        =>  true,
				"type"        => "Text",
				"section"     => __("Footer",'Pixelentity Theme/Plugin'),
				"description" => __("Email address displayed below the envelope icon in footer.",'Pixelentity Theme/Plugin'),
				"default"     => '<a href="mailto:test@nestor.pt">info@nestor.pt</a><br><a href="mailto:test@nestor.pt">support@nestor.pt</a>',
			),
			"footerPhone" => array(
				"label"       => __("Phone number",'Pixelentity Theme/Plugin'),
				"wpml"        =>  true,
				"type"        => "Text",
				"section"     => __("Footer",'Pixelentity Theme/Plugin'),
				"description" => __("Displayed below the phone icon in the footer.",'Pixelentity Theme/Plugin'),
				"default"     => '+351123456789<br>+351987654321',
			),
			"footerCopyright" => array(
				"label"       => __("Copyright",'Pixelentity Theme/Plugin'),
				"wpml"        =>  true,
				"type"        => "TextArea",
				"section"     => __("Footer",'Pixelentity Theme/Plugin'),
				"description" => __("This is the footer copyright message.",'Pixelentity Theme/Plugin'),
				"default"     => '&copy; 2014 Nestor. All rights reserved.',
			),
			"footerSocialLinks" => array(
				"label"        => __("Social Profile Links",'Pixelentity Theme/Plugin'),
				"type"         => "Items",
				"section"      => __("Footer",'Pixelentity Theme/Plugin'),
				"description"  => __("Add one or more links to social networks.",'Pixelentity Theme/Plugin'),
				"button_label" => __("Add Social Link",'Pixelentity Theme/Plugin'),
				"sortable"     => true,
				"auto"         => __("Social Network Name",'Pixelentity Theme/Plugin'),
				"unique"       => false,
				"editable"     => false,
				"legend"       => false,
				"fields"       => array(
					array(
						"label"   => __("Social Network",'Pixelentity Theme/Plugin'),
						"name"    => "name",
						"options" => array(
							__( 'Android' ,'Pixelentity Theme/Plugin')       => 'android',
							__( 'Apple' ,'Pixelentity Theme/Plugin')         => 'apple',
							__( 'Bitcoin' ,'Pixelentity Theme/Plugin')       => 'bitcoin',
							__( 'Buffer' ,'Pixelentity Theme/Plugin')        => 'buffer',
							__( 'Designer News' ,'Pixelentity Theme/Plugin') => 'designernews',
							__( 'Dribbble' ,'Pixelentity Theme/Plugin')      => 'dribbble-outline',
							__( 'Dropbox' ,'Pixelentity Theme/Plugin')       => 'dropbox',
							__( 'Facebook' ,'Pixelentity Theme/Plugin')      => 'facebook',
							__( 'Foursquare' ,'Pixelentity Theme/Plugin')    => 'foursquare',
							__( 'Github' ,'Pixelentity Theme/Plugin')        => 'github',
							__( 'Google+' ,'Pixelentity Theme/Plugin')       => 'googleplus',
							__( 'Hacker News' ,'Pixelentity Theme/Plugin')   => 'hackernews',
							__( 'Instagram' ,'Pixelentity Theme/Plugin')     => 'instagram',
							__( 'LinkedIn' ,'Pixelentity Theme/Plugin')      => 'linkedin',
							__( 'Linux' ,'Pixelentity Theme/Plugin')         => 'tux',
							__( 'Pinterest' ,'Pixelentity Theme/Plugin')     => 'pinterest',
							__( 'Reddit' ,'Pixelentity Theme/Plugin')        => 'reddit',
							__( 'RSS' ,'Pixelentity Theme/Plugin')           => 'rss',
							__( 'Skype' ,'Pixelentity Theme/Plugin')         => 'skype',
							__( 'Tumblr' ,'Pixelentity Theme/Plugin')        => 'tumblr',
							__( 'Twitter' ,'Pixelentity Theme/Plugin')       => 'twitter',
							__( 'Vimeo' ,'Pixelentity Theme/Plugin')         => 'vimeo',
							__( 'Windows' ,'Pixelentity Theme/Plugin')       => 'windows',
							__( 'WordPress' ,'Pixelentity Theme/Plugin')     => 'wordpress',
							__( 'Yahoo' ,'Pixelentity Theme/Plugin')         => 'yahoo',
							__( 'YouTube' ,'Pixelentity Theme/Plugin')       => 'youtube',
						),
						"type"    => "select",
						"width"   => 185,
						"default" => __("Twitter",'Pixelentity Theme/Plugin'),
					),
					array(
						"name"    => "url",
						"type"    => "text",
						"width"   => 300, 
						"default" => "#",
					),
				),
				"default" => "",
			),
		));

		foreach( PeGlobal::$const->gmap->metabox["content"] as $key => $value ) {

			PeGlobal::$const->gmap->metabox["content"][ $key ]["section"] = __("Footer",'Pixelentity Theme/Plugin');

		}

		unset( PeGlobal::$const->gmap->metabox["content"]["title"] );
		unset( PeGlobal::$const->gmap->metabox["content"]["description"] );
		
		//$options = array_merge($options, PeGlobal::$const->gmap->metabox["content"]);

		$options = array_merge($options,$this->font->options());
		$options = array_merge($options,$this->color->options());

		//$options["retina"] =& $this->defaultOptions["retina"];
		//$options["lazyImages"] =& $this->defaultOptions["lazyImages"];
		$options["minifyJS"] =& $this->defaultOptions["minifyJS"];
		$options["minifyCSS"] =& $this->defaultOptions["minifyCSS"];

		$options["minifyJS"]['default'] = 'yes';

		$options["adminThumbs"] =& $this->defaultOptions["adminThumbs"];
		if (!empty($this->defaultOptions["mediaQuick"])) {
			$options["mediaQuick"] =& $this->defaultOptions["mediaQuick"];
			$options["mediaQuickDefault"] =& $this->defaultOptions["mediaQuickDefault"];
		}

		$options["adminLogo"] =& $this->defaultOptions["adminLogo"];
		$options["adminUrl"] =& $this->defaultOptions["adminUrl"];

		
		
		PeGlobal::$config["options"] = apply_filters("pe_theme_options",$options);

	}

	public function splash() {

		$splash = array(
			'type'     => '',
			'title'    => __( 'Splash' ,'Pixelentity Theme/Plugin'),
			'priority' => 'core',
			'where'    => array(
				'post' => 'all',
			),
			'content' => array(),
		);

		$splash['content']['type'] = array(
			'label'       => __( 'Type' ,'Pixelentity Theme/Plugin'),
			'type'        => 'RadioUI',
			'description' => __( 'Choose between two different splash types.' ,'Pixelentity Theme/Plugin'),
			'options'     => array(
				__( 'None' ,'Pixelentity Theme/Plugin')    => 'none',
				__( 'Image' ,'Pixelentity Theme/Plugin')   => 'image',
				__( 'Gallery' ,'Pixelentity Theme/Plugin') => 'gallery',
			),
			'default' => 'none',
		);

		$splash['content']['image_type'] = array(
			'label'       => __( 'Layout type' ,'Pixelentity Theme/Plugin'),
			'type'        => 'RadioUI',
			'description' => __( 'Choose between two different image splash types.' ,'Pixelentity Theme/Plugin'),
			'options'     => array(
				__( 'Simple tagline' ,'Pixelentity Theme/Plugin')    => 'simple',
				__( 'Multiple taglines' ,'Pixelentity Theme/Plugin') => 'multiple',
			),
			'default' => 'simple',
		);

		$splash['content']['background'] = array(
			'label'       => __( 'Background Image' ,'Pixelentity Theme/Plugin'),
			'type'        => 'Upload',
			'description' => __( 'Upload image displayed in the background of the splash area.' ,'Pixelentity Theme/Plugin'),
			'default'     => '',
		);

		$splash['content']['gallery'] = array(
			'label'       => __( 'Gallery' ,'Pixelentity Theme/Plugin'),
			'type'        => 'Select',
			'description' => __( 'Gallery used for splash area. Captions can be added when editing that gallery.' ,'Pixelentity Theme/Plugin'),
			'options'     => $this->gallery->option(),
			'default'     => '',
		);

		$splash['content']['title'] = array(
			'label'       => __( 'Title' ,'Pixelentity Theme/Plugin'),
			'type'        => 'Text',
			'description' => __( 'Text used as a splash title.' ,'Pixelentity Theme/Plugin'),
			'default'     => '',
		);

		$splash['content']['headlines'] = array(
			'label'        => __('Headlines','Pixelentity Theme/Plugin'),
			'type'         => 'Items',
			'description'  => __('Add one or headlines displayed on top of the splash image.','Pixelentity Theme/Plugin'),
			'button_label' => __('Add Headline','Pixelentity Theme/Plugin'),
			'sortable'     => true,
			'auto'         => __('Headline','Pixelentity Theme/Plugin'),
			'unique'       => false,
			'editable'     => false,
			'legend'       => false,
			'fields'       => array(
				array(
					'label'   => __('Title','Pixelentity Theme/Plugin'),
					'name'    => 'title',
					'type'    => 'text',
					'width'   => 250,
					'default' => __('Title','Pixelentity Theme/Plugin'),
				),
				array(
					'label'   => __('Description','Pixelentity Theme/Plugin'),
					'name'    => 'description',
					'type'    => 'text',
					'width'   => 350,
					'default' => __('Description','Pixelentity Theme/Plugin'),
				),
				array(
					'label'   => __('Button text','Pixelentity Theme/Plugin'),
					'name'    => 'button_text',
					'type'    => 'text',
					'width'   => 150,
					'default' => __('Button text','Pixelentity Theme/Plugin'),
				),
				array(
					'label'   => __('Button url','Pixelentity Theme/Plugin'),
					'name'    => 'button_url',
					'type'    => 'text',
					'width'   => 150,
					'default' => __('Button url','Pixelentity Theme/Plugin'),
				),
			),
			'default' => '',
		);

		return $splash;
	}


	public function pe_theme_metabox_config_video() {
		unset( PeGlobal::$config["metaboxes-video"]['video']['content']['fullscreen'] );
		unset( PeGlobal::$config["metaboxes-video"]['video']['content']['width'] );
	}

	public function pe_theme_metabox_config_post() {
		parent::pe_theme_metabox_config_post();

		unset( PeGlobal::$config["metaboxes-post"]['gallery']['content']['type'] );

	}

	public function pe_theme_metabox_config_page() {
		parent::pe_theme_metabox_config_page();

		$builder = isset(PeGlobal::$config["metaboxes-page"]["builder"]) ? PeGlobal::$config["metaboxes-page"]["builder"] : false;
		$builder = $builder ? array("builder"=> $builder) : array();

		if (PE_THEME_MODE && $builder) {
			// top level builder element can only member of the "section" group
			$builder["builder"]["content"]["builder"]["allowed"] = "section";
		}

		$sidebar = array(
			'type'     => '',
			'title'    => __( 'Sidebar' ,'Pixelentity Theme/Plugin'),
			'priority' => 'core',
			'where'    => array(
				'post' => 'default',
			),
			'content' => array(
				'sidebar' => array(
					'label'       => __( 'Show sidebar' ,'Pixelentity Theme/Plugin'),
					'type'        => 'RadioUI',
					'description' => __( 'Choose whether sidebar should be displayed or not.' ,'Pixelentity Theme/Plugin'),
					'options'     => array(
						__( 'Yes' ,'Pixelentity Theme/Plugin') => 'yes',
						__( 'No' ,'Pixelentity Theme/Plugin')  => 'no',
					),
					'default' => 'no',
				),
			),
			'context' => 'side',
		);

		PeGlobal::$config["metaboxes-page"] = array_merge(
			$builder,
			array(
				'splash'  => $this->splash(),
				'sidebar' => $sidebar,
			)
		);		
	}

	public function pe_theme_metabox_config_project() {
		parent::pe_theme_metabox_config_project();

		$galleryMbox = array(
			"title"    => __("Slider",'Pixelentity Theme/Plugin'),
			"type"     => "GalleryPost",
			"priority" => "core",
			"where"    => array(
				"post" => "gallery"
			),
			"content" => array(
				"id" => PeGlobal::$const->gallery->id,
			),
		);

		PeGlobal::$config["metaboxes-project"] =  array(
			"gallery" => $galleryMbox,
			"video"   => PeGlobal::$const->video->metaboxPost,
		);

	}

	public function pe_theme_nestor_testimonial_supports() {

		//add_post_type_support( 'service', 'thumbnail' );
		//add_post_type_support( 'testimonial', 'thumbnail' );

	}

	public function pe_theme_nestor_metabox_config_project() {

		unset( PeGlobal::$config["metaboxes-project"]['portfolio'] );
		unset( PeGlobal::$config["metaboxes-project"]['info'] );

		$cta_mbox = array(
			"title"    => __("Call To Action",'Pixelentity Theme/Plugin'),
			"priority" => "core",
			"where"    => array(
				"post" => "all"
			),
			"content" => array(
				'text' => array(
					'label'       => __( 'Call To Action text' ,'Pixelentity Theme/Plugin'),
					'description' => __( 'Text displayed in Call To Action block.' ,'Pixelentity Theme/Plugin'),
					'type'        => 'Text',
					'default'     => __( 'If you liked what you saw hire us' ,'Pixelentity Theme/Plugin'),
				),
				'color' => array(
					'label'       => __( 'Background color' ,'Pixelentity Theme/Plugin'),
					'description' => __( 'Background color of the block' ,'Pixelentity Theme/Plugin'),
					'type'        => 'Color',
					'default'     => '#2AC5EE',
				),
				'button_text' => array(
					'label'       => __( 'Button text' ,'Pixelentity Theme/Plugin'),
					'description' => __( 'Text for optional button. Leave empty to not use teh button.' ,'Pixelentity Theme/Plugin'),
					'type'        => 'Text',
					'default'     => '',
				),
				'button_url' => array(
					'label'       => __( 'Button url' ,'Pixelentity Theme/Plugin'),
					'description' => __( 'Url button will link to.' ,'Pixelentity Theme/Plugin'),
					'type'        => 'Text',
					'default'     => '',
				),
				'button_target' => array(
					'label'       => __( 'Open in new window' ,'Pixelentity Theme/Plugin'),
					'type'        => 'Select',
					'description' => __( 'Should the url be opened in new window?' ,'Pixelentity Theme/Plugin'),
					'options'   => array(
						__( 'Yes' ,'Pixelentity Theme/Plugin') => 'yes',
						__( 'No' ,'Pixelentity Theme/Plugin')  => 'no',
					),
					'default'     => 'no',
				),
			),
		);

		PeGlobal::$config["metaboxes-project"]['cta'] = $cta_mbox;

	}

	public function pe_theme_metabox_config_staff_action() {

		

	}

	public function pe_theme_metabox_config_service_action() {

		

	}

	public function pe_theme_gallery_image_fields_filter( $fields ) {

		unset( $fields['video'] );

		$link = $fields['link'];
		$save = $fields['save'];
		$ititle = $fields['ititle'];
		$caption = $fields['caption'];

		unset( $fields['link'] );
		unset( $fields['save'] );
		unset( $fields['ititle'] );
		unset( $fields['caption'] );

		$caption['type'] = 'TextArea';

		$fields['ititle'] = $ititle;

		$fields['subtitle'] = array(
			"label"=>__("Subtitle",'Pixelentity Theme/Plugin'),
			"type"=>"Text",
			"section"=>"main",
			"description" => __("Subtitle of the slide (used only in splash).",'Pixelentity Theme/Plugin'),
			"default"=> ""
		);

		$fields['button'] = array(
			"label"=>__("Button text",'Pixelentity Theme/Plugin'),
			"type"=>"Text",
			"section"=>"main",
			"description" => __("Text of the button (used only in splash).",'Pixelentity Theme/Plugin'),
			"default"=> ""
		);

		$fields['button_new_window'] = array(
			"label"       => __("Open in new window",'Pixelentity Theme/Plugin'),
			"type"        => "Select",
			"section"     => "main",
			"description" => __("Should the url be opened in new window?",'Pixelentity Theme/Plugin'),
			"options"     => array(
				__("Yes",'Pixelentity Theme/Plugin') => "yes",
				__("No",'Pixelentity Theme/Plugin')  => "no",
			),
			"default"     => "no",
		);

		$fields['link'] = $link;
		$fields['save'] = $save;

		$fields['link']['label'] = __("Button link",'Pixelentity Theme/Plugin');
		$fields['link']['description'] = __("Link button will point to (used only in splash).",'Pixelentity Theme/Plugin');

		return $fields;

	}

	protected function init_asset() {
		return new PeThemeNestorAsset($this);
	}

	protected function init_template() {
		return new PeThemeNestorTemplate($this);
	}

}