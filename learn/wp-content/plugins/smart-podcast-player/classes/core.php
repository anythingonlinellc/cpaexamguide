<?php
/**
 * Smart Podcast Player
 * 
 * @package   SPP_Core
 * @author    jonathan@redplanet.io
 * @link      http://www.smartpodcastplayer.com
 * @copyright 2015 SPI Labs, LLC
 */

/**
 * @package SPP_Core
  * @author Jonathan Wondrusch <jonathan@redplanet.io?
 */
class SPP_Core {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.1.2';

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    0.8.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'askpat-player';

	/**
	 * Instance of this class.
	 *
	 * @since    0.8.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	protected $_ajax;

	/**
	 * Default (Green) Color for SPP/STP
	 *
	 * @since   1.0.2
	 *
	 * @var     string
	 */
	const SPP_DEFAULT_PLAYER_COLOR = '#60b86c';

	/**
	 * Soundcloud API URL 
	 *
	 * @since   1.0.3
	 *
	 * @var     string
	 */
	const SPP_SOUNDCLOUD_API_URL = 'https://api.soundcloud.com';

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_shortcode( 'smart_track_player', array( $this, 'shortcode_smart_track_player' ) );
		add_shortcode( 'smart_podcast_player', array( $this, 'shortcode_smart_podcast_player' ) );

		add_action( 'wp_head', array( $this, 'fonts' ) );

		// Start Remove >=1.0.5 release - Track feeds are SPP not just soundcloud
		add_action( 'wp_ajax_nopriv_get_soundcloud_tracks', array( $this, 'ajax_get_tracks' ) );
		add_action( 'wp_ajax_get_soundcloud_tracks', array( $this, 'ajax_get_tracks' ) );
		// End Remove

		add_action( 'wp_ajax_nopriv_get_spplayer_tracks', array( $this, 'ajax_get_tracks' ) );
		add_action( 'wp_ajax_get_spplayer_tracks', array( $this, 'ajax_get_tracks' ) );

		add_action( 'wp_ajax_nopriv_get_soundcloud_track', array( $this, 'ajax_get_soundcloud_track' ) );
		add_action( 'wp_ajax_get_soundcloud_track', array( $this, 'ajax_get_soundcloud_track' ) );

		add_action( 'template_redirect', array( $this, 'force_download' ), 1 );

		add_action( 'template_redirect', array( $this, 'cache_bust' ), 1 );

		add_action( 'init', array( $this, 'upgrade' ) );

		add_action( 'wp_footer', array( $this, 'add_body_class' ) );

		// Use shortcodes in text widgets.
		add_filter('widget_text', 'do_shortcode');

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    0.8.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.8.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.8.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    0.8.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.8.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    0.8.0
	 */
	private static function single_activate() {}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.8.0
	 */
	private static function single_deactivate() {}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.8.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    0.8.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', SPP_ASSETS_URL . 'css/style.css', array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    0.8.0
	 */
	public function enqueue_scripts() {

		global $post;

		$general_options = get_option( 'spp_player_general', array( 'show_title' => 'Podcast Episode' ) );
		$api_options = get_option( 'spp_player_soundcloud', array( 'consumer_key' => '' ) );
		$api_consumer_key = isset( $api_options['consumer_key'] ) ? $api_options['consumer_key'] : '';
		
		// Only one file for all of the Javascript, as it all auto loaded into main.min.js
		wp_register_script( $this->plugin_slug . '-plugin-script', SPP_ASSETS_URL . 'js/main.min.js', array( 'jquery', 'underscore' ), self::VERSION, true );

		$soundcloud = get_option( 'spp_player_soundcloud' );
		$key = isset( $soundcloud[ 'consumer_key' ] ) ? $soundcloud[ 'consumer_key' ] : '';
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'AP_Player', array(
			'homeUrl' => home_url(),
			'baseUrl' => SPP_ASSETS_URL . 'js/',
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'soundcloudConsumerKey' => $key,
			'version' => self::VERSION,
			'licensed' => self::is_paid_version()
		));

		// Handle OptimizePress enqueue script
		if( is_object( $post ) && get_post_meta( $post->ID, '_optimizepress_pagebuilder', true ) == 'Y' ) {
			wp_enqueue_script( $this->plugin_slug . '-plugin-script' );			
		}

	}

	/**
	 * Output the shortcode for social customization or default it
	 * 
	 * @param  array  $atts Shortcode arguments array
	 * @return string $html Shortcode HTML
	 */
	public function shortcode_social_customize ( $atts = array(), $full_player = true) {

		$search_array = array(
				'social_twitter'=>'social_twitter','social_facebook'=>'social_facebook','social_gplus'=>'social_gplus',
				'social_linkedin'=>'social_linkedin','social_pinterest'=>'social_pinterest',
				'social_stumble'=>'social_stumble','social_email'=>'social_email');

		$html = '';

		$customized = false;

		if( isset( $atts['social'] ) && $atts['social'] == 'false' ) {
			$html .= ' data-social="' . $atts['social'] . '" ';
			return $html;
		}

		foreach ( $search_array as $value ) {
			if ( is_array ($atts) && array_key_exists( $value, $atts ) ) 
				$customized = true;
	
			if ( $customized )
				break;	
		}	 

		if ( !$customized ) {
			$atts['social']='true';
			$atts['social_twitter']='true';
			$atts['social_facebook']='true';
			$atts['social_gplus']='true';

			if ( $full_player )
				$atts['social_email']='true';
		}


		if( isset( $atts['social'] ) && $atts['social'] )
			$html .= ' data-social="' . $atts['social'] . '" ';

		foreach ( $search_array as $key => $value ) {
			if( isset( $atts[$value] ) ) {
				$html .= ' data-' . $key . '="' . $atts[$value] . '" ';
			}
		}

		return $html;

	}
	

	/**
	 * Output the shortcode for the podcast player
	 * 
	 * @param  array  $atts Shortcode arguments array
	 * @return string $html Shortcode HTML
	 */
	public function shortcode_smart_podcast_player( $atts = array() ) {

		$options = get_option( 'spp_player_defaults' );

		// Only include these scripts when the shortcode is present
		wp_enqueue_script( $this->plugin_slug . '-plugin-script' );

		add_action( 'wp_footer', array( $this, 'add_csshead_class' ) );

		$seed = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$uniq_id = array();

		for ($i=0; $i < 8; $i++) { 
			$index = rand( 0, 61 );
			$uniq_id[] = $seed[$index];
		}
		
		$uid = implode( '', $uniq_id );
		
		// Include some intelligent defaults based on the options.
		extract( shortcode_atts( array(
			'url' => '',
			'style' => ( isset( $options['style'] ) ? $options['style'] : 'light' ),
			'numbering' => '',
			'show_name' => ( isset( $options['show_name'] ) ? $options['show_name'] : '' ),
			'image' => '',
			'color' => ( isset( $options['bg_color'] ) ? $options['bg_color'] : self::SPP_DEFAULT_PLAYER_COLOR ),
			'link_color' => ( isset( $options['link_color'] ) ? $options['link_color'] : self::SPP_DEFAULT_PLAYER_COLOR ),
			'hashtag' => '',
			'permalink' => '',
			'download' => 'true',
			'subscription' => ( isset( $options['subscription'] ) ? $options['subscription'] : '' ),
			'social' => 'true',
			'social_twitter' => 'true',
			'social_facebook' => 'true',
			'social_gplus' => 'true',
			'social_linkedin' => 'false',
			'social_stumble' => 'false',
			'social_pinterest' => 'false',
			'social_email' => 'true',
			'speedcontrol' => 'true',
			'poweredby' => ( isset( $options['poweredby'] ) ? $options['poweredby'] : 'true' ),
			'sort' => ( isset( $options['sort_order'] ) ? $options['sort_order'] : 'newest' )
		), $atts ) );

		// Check URL to see if it is an html link or a url
		if( strpos( $url, ' href="' ) !== false ) {
			preg_match( '/href="(.+)"/', $url, $match);
			$url = parse_url( $match[1] );
		}

		$url = $url ? $url : ( isset( $options['url'] ) ? $options['url'] : '' );

		$html = '<div data-stream="' . $url . '" class="smart-podcast-player ';
		
		$free_colors = self::get_free_colors();
		$free_colors = array_change_key_case( $free_colors, CASE_LOWER );
		// If the user put in the name of a known color, replace it with the hex code
		if( array_key_exists( $color, $free_colors ) ) 
			$color = $free_colors[ $color ];

		if( !self::is_paid_version() ) {
			$color = self::SPP_DEFAULT_PLAYER_COLOR;
			$link_color = self::SPP_DEFAULT_PLAYER_COLOR;
			$download = false;
			$social = false;
			$speedcontrol = false;
			$poweredby = true;
			$sort = 'newest';
		}
			
		// Add all of the data attributes to the player div
		if( $color != '' )
			$html .= ' smart-podcast-player-' . str_replace( '#', '', $color ) . '  spp-color-' . str_replace( '#', '', $color ) . ' ';

		if( $link_color != '' )
			$html .= ' spp-link-color-' . str_replace( '#', '', $link_color ) . ' ';

		if( $style != 'light' )
			$html .= 'smart-podcast-player-' . $style . ' ';

		$html .= '" ';

		if( $numbering )
			$html .= 'data-numbering="' . $numbering . '" ';

		if( $download )
			$html .= 'data-download="' . $download . '" ';

		if( $permalink )
			$html .= 'data-permalink="' . $permalink . '" ';

		if( $show_name )
			$html .= 'data-show-name="' . $show_name . '" ';

		if( $hashtag )
			$html .= 'data-hashtag="' . $hashtag . '" ';

		if( $image )
			$html .= 'data-image="' . $image . '" ';

		if( $color )
			$html .= 'data-color="' . $color . '" ';

		if( $link_color )
			$html .= 'data-link-color="' . $link_color . '" ';

		if( $sort ) {
			$sort = $sort == 'newest' || $sort == 'oldest' ? $sort : 'newest';
			$html .= 'data-sort="' . $sort . '" ';
		}

		if( $social )
			$html .= $this->shortcode_social_customize( $atts, true );

		if( $speedcontrol )
			$html .= 'data-speedcontrol="' . $speedcontrol . '" ';

		if( $poweredby )
			$html .= 'data-poweredby="' . $poweredby . '" ';

		if( $subscription )
			$html .= 'data-subscription="' . $subscription . '" ';
		
		if( self::is_paid_version() )
			$html .= 'data-paid="true" ';

		$html .= 'data-uid="' . $uid . '" ';
		$html .= '></div>';

		// Output the shortcode HTML, javascript will take over after that.
		return $html;	

	}

	/**
	 * Output the shortcode for the track player
	 * @param  array  $atts Shortcode arguments, needs to be extracted
	 * @return string $html Shortcode HTML
	 */
	public function shortcode_smart_track_player( $atts = array() ) {

		global $spp_buffer_size;

		// Include the MP3 class to handle MP3 data
		require_once( SPP_PLUGIN_BASE . 'classes/mp3.php' );

		// Only include the javascript files if the shortcode is in place
		wp_enqueue_script( $this->plugin_slug . '-plugin-script' );

		add_action( 'wp_footer', array( $this, 'add_csshead_class' ) );

		// Intelligent defaults
		extract( shortcode_atts( array(
			'url' => '',
			'style' => 'light',
			'show_numbering' => '',
			'title' => '',
			'image' => '',
			'download' => 'true',
			'social' => 'true',
			'social_twitter' => 'true',
			'social_facebook' => 'true',
			'social_gplus' => 'true',
			'social_linkedin' => 'false',
			'social_stumble' => 'false',
			'social_pinterest' => 'false',
			'social_email' => 'false',
			'speedcontrol' => 'true',
			'color' => '',
			'artist' => ''
		), $atts ) );

		if( !self::is_paid_version() ) {
			$atts['color'] = self::SPP_DEFAULT_PLAYER_COLOR;
			$atts['download'] = false;
			$atts['social'] = false;
			$atts['speedcontrol'] = false;
		}

		// Check URL to see if it is an html link or a url
		// Users were very often including an HTML link (<a href=""></a>) 
		// instead of just a raw URL

		if( strpos( $url, 'href=' ) !== false ) {

			$xml = simplexml_load_string( $url );
		    $list = $xml->xpath("//@href");

		    $preparedUrls = array();
		    foreach($list as $item) {
		    	$i = $item;
		        $item = parse_url($item);
		        $preparedUrls[] = $item['scheme'] . '://' .  $item['host'] . $item['path'];
		    }

		    $url = $preparedUrls[0];

		}

		$url = $url ? $url : '';

		// If URL Type is not set, there was an error, so we give nothing out so as to not crash the page.
		$url_type = $this->get_url_type( $url );
		if( !$url_type )
			return;

		$output = '';

		// Based on the type, get the right track data
		switch( $url_type ) {
			case 'feed' :
				$output = $this->get_track_feed_html( $url, $atts );
				break;

			case 'mp3' :
			default :
				$output = $this->get_track_mp3_html( $url, $atts );
				break;

		}

		return $output;

	}

	/**
	 * Get the HTML of the 
	 * @param  string 	$feed_url 	URL of the feed
	 * @param  array 	$atts 		Existing string of the 
	 * @return void
	 */
	public function get_track_feed_html( $feed_url, $atts ) {

		$html = '';

		$transient = 'spp_cachem_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', md5($feed_url) ), -32 );
		$no_cache = filter_input( INPUT_GET, 'spp_no_cache' ) ? filter_input( INPUT_GET, 'spp_no_cache' ) : 'false';
		
		if ( ( ( false === ( $tracks = get_transient( $transient ) ) && strpos( $feed_url, 'soundcloud.com' ) === false ) ) || $no_cache == 'true' || empty( $tracks ) ) {
			
			$tracks = array();

			// Handle SoundCloud specific functionality
			if( strpos( $feed_url, 'http://soundcloud.com/' ) !== false || strpos( $feed_url, 'http://soundcloud.com/sets/' ) !== false || strpos( $feed_url, 'https://soundcloud.com/' ) !== false || strpos( $feed_url, 'https://soundcloud.com/sets/' ) !== false ) {
				$tracks = self::get_soundcloud_tracks( $feed_url );
				$audio_url = $tracks[0]->uri;

			// Otherwise, treat it like an RSS feed
			} else {
				$tracks = self::get_rss_tracks( $feed_url );
				$audio_url = $tracks[0]->stream_url;
			}

			//BPD Data?
			//if( !empty( $data ) )
			//	set_transient( $transient, $data, 5 * MINUTE_IN_SECONDS );
		
		} else {

			if( strpos( $feed_url, 'http://soundcloud.com/' ) !== false || strpos( $feed_url, 'http://soundcloud.com/sets/' ) !== false || strpos( $feed_url, 'https://soundcloud.com/' ) !== false || strpos( $feed_url, 'https://soundcloud.com/sets/' ) !== false ) {
				$audio_url = $tracks[0]->uri;
			} else {
				$audio_url = $tracks[0]->stream_url;
			}			

		}

		// Set attributes for the shortcode based on the latest feed data
		$atts['url'] = $audio_url;

		if( !isset( $atts['title'] ) || empty( $atts['title'] ) )
			$atts['title'] = $tracks[0]->title;

		// Since in the end we're still only doing a single track, then utilize the track-mp3 function
		return $this->get_track_mp3_html( $audio_url, $atts );

	}

	/**
	 * Output HTML for a single 
	 * @param  string 	$audio_url 	Link to an MP3
	 * @param  array 	$atts      	Array of shortcode attributes
	 * @return string 	$html 		HTML output for shortcode
	 */
	public function get_track_mp3_html( $audio_url, $atts ) {

		$options = get_option( 'spp_player_defaults' );

		$seed = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$uniq_id = array();

		for ($i=0; $i < 8; $i++) { 
			$index = rand( 0, 61 );
			$uniq_id[] = $seed[$index];
		}

		$uid = implode( '', $uniq_id );

		extract( shortcode_atts( array(
			'url' => '',
			'style' => 'light',
			'show_numbering' => '',
			'title' => '',
			'image' => ( isset( $options['stp_image'] ) ? $options['stp_image'] : '' ),
			'download' => 'true',
			'social' => 'true',
			'social_twitter' => 'true',
			'social_facebook' => 'true',
			'social_gplus' => 'true',
			'social_linkedin' => 'false',
			'social_stumble' => 'false',
			'social_pinterest' => 'false',
			'social_email' => 'false',
			'speedcontrol' => 'true',
			'color' => ( isset( $options['bg_color'] ) ? $options['bg_color'] : self::SPP_DEFAULT_PLAYER_COLOR ),
			'artist' => ''
		), $atts ) );

		$class = 'smart-track-player ';

		$transient = 'spp_cachem_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', md5($audio_url) ), -32 );
		$no_cache = isset( $_GET['spp_no_cache'] ) && $_GET['spp_no_cache'] == 'true' ? 'true' : 'false';
			
		$data = array();
		
		// If the user typed the name of a known color, replace it with the hex code
		$free_colors = self::get_free_colors();
		$free_colors = array_change_key_case( $free_colors, CASE_LOWER );
		if( array_key_exists( $color, $free_colors ) ) 
			$color = $free_colors[ $color ];
		
		if ( ( ( false === ( $data = get_transient( $transient ) ) && strpos( $url, 'soundcloud.com' ) === false ) ) || $no_cache == 'true' ) {
			$data = array();
		}

		if( $style != 'light' )
			$class .= ' stp-' . $style . ' ';

		if( $color != '' )
			$class .= ' stp-color-' . str_replace( '#', '', $color ) . ' ';

		$html = '<div class="' . trim( $class ) . '" data-url="' . $url . '" ';

		if( $show_numbering )
			$html .= 'data-numbering="' . $show_numbering . '" ';

		if( $image )
			$html .= 'data-image="' . $image . '" ';

		if( $download )
			$html .= 'data-download="' . $download . '" ';

		if( $color != '' ) 
			$html .= 'data-color="' . str_replace( '#', '', $color ) . '" ';

		if( $title != '' ) {
			$html .= 'data-title="' . $title . '" ';
		} else {
			if( isset( $data['title'] ) )
				$html .= 'data-title="' . $data['title'] . '" ';
			elseif( isset( $data['album'] ) )
				$html .= 'data-title="' . $data['album'] . '" ';
			elseif( isset( $data['artist'] ) )
				$html .= 'data-title="' . $data['artist'] . '" ';
			elseif( isset( $options['show_name']  ) && $options['show_name'] != '' && !empty( $data ) )
				$html .= 'data-title="' . $options['show_name']  . '" ';
		}

		if( $artist != '' ) {
			$html .= 'data-artist="' . $artist . '" ';
		} else {
			if( isset( $data['artist'] ) )
				$html .= 'data-artist="' . $data['artist'] . '" ';
			elseif( isset( $data['album'] ) )
				$html .= 'data-title="' . $data['album'] . '" ';
			elseif( isset( $options['show_name']  ) && $options['show_name'] != '' && !empty( $data ) )
				$html .= 'data-title="' . $options['show_name']  . '" ';
		}
		
		if( self::is_paid_version() )
			$html .= 'data-paid="true" ';

		if( $social )
			$html .= $this->shortcode_social_customize( $atts, false );

		if( $speedcontrol )
			$html .= 'data-speedcontrol="' . $speedcontrol . '" ';

		if( empty( $data ) && ( $title == '' )  )
			$html .= 'data-get="true" ';

		$html .= 'data-uid="' . $uid . '" ';
		
		require_once( SPP_PLUGIN_BASE . 'classes/download.php' );
		$download_id = SPP_Download::save_download_id($url);
		$html .= 'data-download_id="' . $download_id . '" ';

		$html .= '></div>';

		return $html;

	}

	/**
	 * Initialize handler for AJAX calls
	 * @return void
	 */
	public function ajax() {
		$this->_ajax = new SPP_Admin_Ajax();
	}

	/**
	 * Create a unique ID to refer to playlists based on their name, and to use when storing their cache
	 * @return string 	$uniq_id 	Unique ID
	 */
	public static function generate_playlist_id() {
	
		global $wpdb;

		$seed = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$uniq_id = array();

		for ($i=0; $i < 8; $i++) { 
			$index = rand( 0, 61 );
			$uniq_id[] = $seed[$index];
		}

		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'spp_feed_' . implode( '', $uniq_id ) ) );

		return empty( $results ) ? 'spp_feed_' . implode( '', $uniq_id ) : self::generate_playlist_id();

	}

	/**
	 * A function save feed data based on the asyncronous functions in smart-podcast-player.php
	 * @param  string 	$url 	URL of the feed
	 * @return void
	 */
	public static function save_feed_data( $url ) {
		
		global $wpdb;

		// Get a Unique ID
		$transient = self::generate_playlist_id();

		$data = array(
			'url' => $url
		); 

		if( $url ) {

			// Different steps for SoundCloud or RSS Feed
			if( strpos( $url, 'http://soundcloud.com/' ) !== false || strpos( $url, 'http://soundcloud.com/sets/' ) !== false || strpos( $url, 'https://soundcloud.com/' ) !== false || strpos( $url, 'https://soundcloud.com/sets/' ) !== false ) {
				$data['tracks'] = self::get_soundcloud_tracks( $url );	
			} else {
				$data['tracks'] = self::get_rss_tracks( $url );
			}

		} 

		// BPD Utilized?
		// Put the results in a transient. Expire after 10 minutes
		if ( !empty ( $data['tracks'] ) )
			set_transient( $transient, $data, 5 * MINUTE_IN_SECONDS );

	}

	/**
	 * Get track data via AJAX
	 * @return 	json 	JSON object representing all tracks
	 */
	public function ajax_get_tracks() {

		//global $wpdb;

		$url = isset( $_POST['stream'] ) ? $_POST['stream'] : '';

		//BPD URL based transient?
		//$existing = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_value LIKE %s", '%' . $url . '%' ) );
		//$transient =  empty( $existing ) ? self::generate_playlist_id() : str_replace( '_transient_', '', $existing->option_name );
		if ( !empty( $url ) )
			$transient = 'spp_cachea_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', self::VERSION . $url ), -32 );

		$no_cache = filter_input( INPUT_GET, 'spp_no_cache' ) ? filter_input( INPUT_GET, 'spp_no_cache' ) : 'false';
		
		if( ( false === ( $data = get_transient( $transient ) ) || !isset( $data['tracks'] ) ) || $no_cache == 'true' ) {

			$data = array(
				'url' => $url,
				'tracks' => array()
			);

			if( $url ) {

				if( strpos( $url, 'http://soundcloud.com/' ) !== false || strpos( $url, 'http://soundcloud.com/sets/' ) !== false || strpos( $url, 'https://soundcloud.com/' ) !== false || strpos( $url, 'https://soundcloud.com/sets/' ) !== false ) {

					$data['tracks'] = $this->get_soundcloud_tracks( $url );

				} else {
					$data['tracks'] = $this->get_rss_tracks( $url );

				}

				if ( is_array( $data['tracks'] ) || !empty ( $data['tracks'] ) ) {
					 	$settings = get_option( 'spp_player_advanced' );

        				$val = isset( $settings['cache_timeout'] ) ? $settings['cache_timeout'] : '15';
        				if ( $val > 60 || $val < 5 || !is_numeric( $val ) )
        					$val = 15;
						set_transient( $transient, $data, $val * MINUTE_IN_SECONDS );
				}
				else {
					// Prevent crazy load and re-fetching
					set_transient( $transient, $data, MINUTE_IN_SECONDS );
				}
			} 

		}
		
		header('Content-Type: application/json');
		echo json_encode( $data['tracks'] );

		exit;

	}

	/**
	 * Called by SPP_Core::ajax_get_tracks, specifically to retrieve SoundCloud tracks
	 * @param  string 	$url 		URL of SoundCloud feed
	 * @return array 	$tracks		Array of tracks
	 */
	public static function get_soundcloud_tracks( $url ) {
		
		$tracks = array();
		$api_options = get_option( 'spp_player_soundcloud', array( 'consumer_key' => '' ) );
		$api_consumer_key = isset( $api_options['consumer_key'] ) ? $api_options['consumer_key'] : '';

		// Determine if it's a feed URL
		if( strpos( $url, '/sets/' ) === false ) {
			
			$user_id = '';

			$url_prof = self::SPP_SOUNDCLOUD_API_URL . '/resolve?url=' . urlencode( $url ) . '&format=json&consumer_key=' . $api_consumer_key;
			$transient = 'spp_cachep_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', md5( $url_prof ) ), -32 );
			
			if(  false === ( $profile = get_transient( $transient ) )  ) {

				$response = wp_remote_get( $url_prof );
				if( !is_wp_error( $response ) && ( $response['response']['code'] < 400 ) ) {

					$profile = json_decode( $response['body'] );

					if ( !empty ( $profile  ) && isset( $profile->id ) )
							set_transient( $transient, $profile, 5 * MINUTE_IN_SECONDS );

				}

			}

			$user_id = $profile->id;
			$track_count = $profile->track_count;

			if ( !is_numeric( $track_count ) || $track_count <= 0 )
				$track_count = 1;

			$offset = 0;
			$limit = 200;
			$tracks_arr = array();
			
			// Limit the free version to ten tracks
			if( !self::is_paid_version() && $track_count > 10 ) {
				$track_count = 10;
				$limit = 10;
			}

			$transient = 'spp_caches_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', self::VERSION . $url . substr( $track_count, -1 ) ), -32 );

			if(  false === ( $tracks = get_transient ( $transient ) ) ) {

				$url = self::SPP_SOUNDCLOUD_API_URL . '/users/' . $user_id . '/tracks?format=json&client_id=' . $api_consumer_key . '&limit=' . $limit .'&linked_partitioning=1';

				while ( $track_count > $offset ) {

						$response = wp_remote_get( $url );
						if( !is_wp_error( $response ) && ( $response['response']['code'] < 400 ) ) {

							$json_obj = json_decode( $response['body'] );
							$tracks_arr[] = json_encode( $json_obj->collection );

							if ( empty( $json_obj->next_href ) )
								break;
							
							$url =  $json_obj->next_href; 
							
						}

					$offset += 200;

				}

				if ( is_array( $tracks_arr ) && !empty( $tracks_arr ) ) {

					if ( empty($tracks) && ( count( $tracks_arr ) == 1 ) ) {
							$tracks = json_decode( $tracks_arr[0] );
					}

					else {
							
						foreach($tracks_arr as $val) {

							if ( empty($tracks) ) {
								$tracks = $val;
							}
							else {
									if ( is_array( $tracks ) )
										$tracks = array_merge( $tracks, json_decode( $val, true ) ); 
									else
										$tracks = array_merge( json_decode( $tracks, true ), json_decode( $val, true ) ); 
							}
						}

						$tracks = json_decode( json_encode( $tracks ) );

					}

					if ( !empty ( $tracks ) )
						set_transient( $transient, $tracks , 4 * HOUR_IN_SECONDS );
				}
			}

		// Or if it's a profile URL
		} else {

			$url = self::SPP_SOUNDCLOUD_API_URL . '/resolve?url=' . urlencode( $url ) . '&format=json&consumer_key=' . $api_consumer_key;
			$transient = 'spp_cachesu_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', md5( $url ) ), -32 );
			if(  false === ( $tracks = get_transient( $transient ) )  ) {

				$response = wp_remote_get( $url );
				if( !is_wp_error( $response ) && ( $response['response']['code'] < 400 ) ) {

					$playlist = json_decode( $response['body'] );
					$tracks = $playlist->tracks;
					
					// Limit the free version to ten tracks
					if( !self::is_paid_version() )
						$tracks = array_slice( $tracks, 0, 10 );

					if ( !empty ( $tracks ) )
						set_transient( $transient, $tracks , 5 * MINUTE_IN_SECONDS );

				}

			}

		}

		if ( !empty( $tracks ) ) {
			return $tracks;
		}
		else
		{
			for( $track_count = 0; $track_count < 10; ++$track_count ) {
				$transient = 'spp_caches_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', self::VERSION . $url . substr( $track_count, -1 ) ), -32 );
				$tracks = null;
				if( ( $tracks = get_transient ( $transient ) ) && !empty( $tracks ) ) {
					return $tracks;
				}
			}
			return null;
		}
	}

	/**
	 * Rewrite of WP Core fetch_feed function, removing the WP_SimplePie_File, which was causing issues 
	 * with FeedBlitz feeds
	 * 
	 * @param  string $url Url of RSS feed
	 * @return void
	 */
	public static function fetch_feed( $url ) {

		require_once( ABSPATH . WPINC . '/class-simplepie.php' );
		require_once( ABSPATH . WPINC . '/class-feed.php' );

		$rss = new SimplePie();

		$rss->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );

		// We must manually overwrite $feed->sanitize because SimplePie's
		// constructor sets it before we have a chance to set the sanitization class
		$rss->sanitize = new WP_SimplePie_Sanitize_KSES();
		$rss->set_cache_class( 'WP_Feed_Cache' );
		$rss->set_feed_url( $url );
		// extend for slow feed generation/hosts
		$rss->set_timeout(15);

		// Also changed cache duration
		$rss->set_cache_duration( 5 * MINUTE_IN_SECONDS );

		$rss->init();
		$rss->handle_content_type();

		if ( $rss->error() )
			return new WP_Error( 'simplepie-error', $rss->error() );

		return $rss;

	}

	/**
	 * Retrieve track data from RSS feeds
	 * 
	 * @param  string $url URL of the RSS feed
	 * @return array 	Data for all of the tracks
	 */
	public static function get_rss_tracks( $url ) {

		$rss = self::fetch_feed( $url );

		if( is_wp_error( $rss ) )
			return array();

		$transient = 'spp_cachesx_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', self::VERSION . $url  ), -32 );
		
		// See if RAW XML is already available from SimplePie. Indicates when feed new/changed too.
		if ( $rss->get_raw_data() ) {
				$data = $rss->get_raw_data();
				set_transient( $transient, $data , HOUR_IN_SECONDS );
		}
		else {	
			if(  false === ( $data = get_transient( $transient ) )  ) {
				$data = wp_remote_retrieve_body ( wp_remote_get( $url ) );

				if ( !empty ( $data ) && !is_wp_error( $data )  )
					set_transient( $transient, $data , 5 * MINUTE_IN_SECONDS );
			}
		}

		if ( !empty ( $data ) )
			$xml = simplexml_load_string( $data );	// URL file-access is disabled? HS1438

		if ( empty( $xml ) || empty( $data ) )
			$xml = simplexml_load_file( $url ); 	// Raw xml so we can fetch other data

		$base = new StdClass;
		$user = new StdClass;

		// Many of these fields are pulled from the data that soundcloud includes in their track player
		$attr = array( 'kind', 'id', 'created_at', 'user_id', 'duration', 'user_id', 'duration', 'commentable', 'state', 'original_content_size', 'sharing', 'tag_list', 'permalink', 'streamable', 'embeddable_by', 'downloadable', 'purchase_url', 'label_id', 'purchase_title', 'genre', 'title', 'description', 'label_name', 'release', 'track_type', 'key_signature', 'isrc', 'video_url', 'bpm', 'release_year', 'release_month', 'release_day', 'original_format', 'license', 'uri', 'user', 'permalink_url', 'artwork_url', 'waveform_url', 'stream_url', 'download_url', 'download_count', 'favoritings_count', 'comment_count', 'attachments_uri', 'episode_number', 'content' );

		$user_attr = array( 'id', 'kind', 'permalink', 'username', 'uri', 'permalink_url', 'avatar_url' );

		foreach( $attr as $a ) {
			$base->{$a} = '';
		}

		foreach( $user_attr as $a ) {
			$user->{$a} = '';
		}

		$base->user = $user;

		$channel = $xml->channel;
		$items = $channel->item;

		$tracks = array();

		$episode_number = count( $items );
		$i = 0;

		if( !is_wp_error( $rss ) ) {
		
			require_once( SPP_PLUGIN_BASE . 'classes/download.php' );

			foreach ( $rss->get_items() as $item) {
				
				$enclosures = $item->get_enclosures();
				$enclosure = $item->get_enclosure();

				foreach( $enclosures as $enc ) {
					if( $enc->handler == 'mp3' ) {
						$enclosure = $enc;
					}
				}

	 			$track = clone( $base );
				$date = new DateTime( $item->get_date() );

				$content = $item->get_content();
				$description = $item->get_description();
			
				$track->id = $i;
				$track->title = $item->get_title();

				// Display full show notes instead of abbreviated
				if ( !empty($content) && strstr($description, "[&#8230;]</p>") && ( strlen($content) > strlen($description) ) )
					$description = strip_tags( $content ,'<p><a>' );
				//elseif ( !empty($content) && strstr($description, "...") && ( strlen($content) > strlen($description) ) )
				//	$description = strip_tags( $content, '<p><a>' );
				$track->description = self::scrub_html( $description );

				$item_link = $item->get_link();
				$track->permalink_url = is_null($item_link) ? "" : $item_link;
				$track->uri = is_null($item_link) ? "" : $item_link;
				$track->stream_url = $enclosure->link;
				$track->download_url = $enclosure->link;
				$track->duration = $enclosure->duration;
				$track->created_at = $date->format( 'Y/m/d h:i:s O' );
				
				$track->artwork_url = (string) $channel->image->url;
				if ( stripos( $track->artwork_url, "http://i1.sndcdn.com" ) !== FALSE )
					$track->artwork_url = str_replace( "http://i1.sndcdn.com", "//i1.sndcdn.com", $track->artwork_url );
				
				if( $track->artwork_url == '' || empty( $track->artwork_url ) ) {

					if( is_array( $enclosure->thumbnails ) && !empty( $enclosure->thumbnails[0] ) && $enclosure->thumbnails[0] != '' ) {
						
						$track->artwork_url = $enclosure->thumbnails[0];

					} else {

						$itunes_image = $rss->get_channel_tags( SIMPLEPIE_NAMESPACE_ITUNES, 'image' );

						if( is_array( $itunes_image ) ) {
							$track->artwork_url = $itunes_image[0]['attribs']['']['href'];	
						}

					}					

				}
				
				$track->show_name = (string) $channel->title;
				$track->episode_number = $episode_number;
				$track->download_id = SPP_Download::save_download_id($enclosure->link);

				if( !empty( $track->stream_url ) && $track->stream_url != '' ) {
					$tracks[] = $track;	
					$episode_number--;
				} else {}

				$i++;
				
				// Limit the free version to ten tracks
				if( !self::is_paid_version() && $i >= 10 )
					break;
				
			}
		}

		return $tracks;
		
	}

	/**
	 * Return the tracks from a soundcloud feed for ajax
	 * 
	 * @return JSON Array
	 */
	public function ajax_get_soundcloud_track() {

		$api_options = get_option( 'spp_player_soundcloud', array( 'consumer_key' => '' ) );
		$api_consumer_key = isset( $api_options['consumer_key'] ) ? $api_options['consumer_key'] : '';

		$url = isset( $_POST['stream'] ) ? $_POST['stream'] : '';

		if ( !empty( $url ) )
			$transient = 'spp_cachet_' . substr( preg_replace("/[^a-zA-Z0-9]/", '', md5($url) ), -32 );
		
		$url = self::SPP_SOUNDCLOUD_API_URL . '/resolve.json?url=' . urlencode( $url ) . '&consumer_key=' . $api_consumer_key;

		if(  false === ( $track = get_transient( $transient ) )  ) {

			$response = wp_remote_get( $url );
			if( !is_wp_error( $response ) && ( $response['response']['code'] < 400 ) ) {
				$track = json_decode( $response['body'] );

				if ( !empty ( $track  ) ) {
					
					$settings = get_option( 'spp_player_advanced' );

        			$val = isset( $settings['cache_timeout'] ) ? $settings['cache_timeout'] : '15';
        			if ( $val > 60 || $val < 5 || !is_numeric( $val ) )
        				$val = 15;
					set_transient( $transient, $track, $val * HOUR_IN_SECONDS );
					// Single STP for Soundcloud
				}
			}

		}
		
		header('Content-Type: application/json');
		echo json_encode( $track );

		exit;

	}

	/**
	 * Automatically include google fonts on people's pages to use with the player
	 * 
	 * @return void
	 */
	public function fonts() {
		echo '<link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700" rel="stylesheet" type="text/css">';
	}

	/**
	 * Use the SPP_Download class to force file downloads based on methods available
	 * 
	 * @return void
	 */
	public function force_download() {
		if( isset( $_GET['spp_download'] ) ) {
			require_once( SPP_PLUGIN_BASE . 'classes/download.php' );
			$download_id = $_GET['spp_download'];
			$download = new SPP_Download( $download_id );
			$download->get_file();
			exit;
		}
	}

	/**
	 * Delete the internal spp_cache when the URL variables are present
	 * 
	 * @return void
	 */
	public function cache_bust() {

		$bust = filter_input( INPUT_GET, 'spp_cache' );

		if( $bust == 'bust' && current_user_can( 'update_plugins' ) ) {

			global $wpdb;

			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE autoload='no' AND option_name LIKE %s", '%spp\_cache%' ) );
			
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE autoload='no' AND option_name LIKE %s", '%spp\_license\_chk' ) );
			
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE autoload='no' AND option_name LIKE %s", '%spp\_feed_%' ) );

		}

	}

	/**
	 * Scrub the HTML passed in for any attributes we don't want, like class, style, and ID
	 * 
	 * @param  string $input Can be any valid HTML text
	 * @return string $output Scrubbed HTML output, minus the doctype
	 */
	public static function scrub_html( $input ) {

		if( !extension_loaded( 'libxml' ) || !extension_loaded( 'dom' ) || empty( $input ) )
			return $input;

		require_once( dirname( __FILE__ ) . '/vendor/SmartDOMDocument.php' );

		$dom = new SmartDOMDocument;
		$dom->loadHTML( $input );

		$xpath = new DOMXPath( $dom );
		$nodes = $xpath->query('//@*');

		foreach ($nodes as $node) {
			if( $node->nodeName == 'style' || $node->nodeName == 'class' || $node->nodeName == 'id' ) {
			    $node->parentNode->removeAttribute($node->nodeName);
			}
		}

		$links = $dom->getElementsByTagName('a');

		foreach ( $links as $item ) {
			$item->setAttribute('target','_blank');  
		}
		
		$output = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML() ); // Extract w/o the doctype and html/body tags
		
		return $output;

	}

	/**
	 * Get CSS tools ready to process.
	 * 
	 * @return SPP_Player_CSS Instance of the Player CSS class
	 */
	public function style_setup() {
		
		require_once( SPP_PLUGIN_BASE . 'classes/css.php' );		

		$css = SPP_Player_CSS::get_instance();

		return $css;

	}	

	/**
	 * Used when determining if we're dealing with an MP3 or an RSS Feed
	 * @param  string $url 
	 * @return string 'feed' or 'mp3' only
	 */
	public function get_url_type( $url ) {
			
		$type = false;

		if( strpos( $url, 'soundcloud.com' ) !== false ) {

			$test = rtrim( $url, '/' );
			$count = substr_count( $test, '/' );

			if( $count > 3 && strpos( $url, '/sets/' ) === false ) {

				$type = 'mp3';

			}

			if( $count <= 3 ) {

				$feed = self::fetch_feed( $url );

				if( is_wp_error( $feed ) )
					return $type;

				$feed->init();
				$feed->handle_content_type();

				if ( !$feed->error() ) 
					$type = 'feed';

			}			

		} else {

			if( strpos( $url, '.mp3' ) !== false ) {

				$type = 'mp3';

			} else {

				$feed = self::fetch_feed( $url );

				if( is_wp_error( $feed ) )
					return $type;
				
				$feed->init();
				$feed->handle_content_type();

				if ( !$feed->error() ) 
					$type = 'feed';

			}

		}


		return $type;

	}
	
	/**
	 * Tells whether this version is the paid or free version
	 *
	 * @return true if this is the paid version of the player, false otherwise
     *
	 * @since 1.0.2
	 */
	public static function is_paid_version() {
		
		$settings = get_option( 'spp_player_general' );

		if( !isset( $settings[ 'license_key' ] ) || empty( $settings[ 'license_key' ] ) ) 
			return false;

		$transient = 'spp_license_chk';
		
		if ( false !== ( $check = get_transient( $transient ) ) ) {
			return true;
		}

		// plugin updater class confirms valid checks
		$optionName = 'external_updates-smart-podcast-player';
		$state = get_site_option($optionName, null);
		
		if ( !empty($state) && is_object($state) && isset($state->update) && is_object($state->update) ){
			set_transient( $transient, time(), 12 * WEEK_IN_SECONDS );
			return true;
		}

		return false;
	
	}
	
	/**
	 * Gets an array of the colors included in the free version
	 *
	 * @return an array of the colors included in the free version
	 *
	 * @since 1.0.20
	 */
	public static function get_free_colors() {
		return array( 'Green' => self::SPP_DEFAULT_PLAYER_COLOR ,
				'Blue' => '#006cb5',
				'Yellow' => '#f0af00',
				'Orange' => '#e7741b',
				'Red' => '#dc1a26',
				'Purple' => '#943f93' );
	}

	/**
	 * Process upgrade of the plugin
	 * 
	 * @return void
	 */
	public function upgrade() {

	    $version = get_option( 'spp_version' );

	    if ( $version != self::VERSION ) {

	    	add_option( 'spp_version', self::VERSION );
	        
	        // Migrate old option names to the new ones if any of the new ones don't exist
	        if(( 
	        	!get_option( 'spp_player_general' ) || 
	        	!get_option( 'spp_player_defaults' ) || 
	        	!get_option( 'spp_player_soundcloud' ) 
	        	) && ( 
	        	get_option( 'ap_player_general' ) !== false || 
	        	get_option( 'ap_player_defaults' ) !== false || 
	        	get_option( 'ap_player_soundcloud' ) !== false 
	        )) { 
	        	$this->migrate_options(); 
	        }

	    }

	}

	/**
	 * Migrate old ap_* based options to spp_* based options based on the version of the plugin
	 * 
	 * @return void
	 */
	public function migrate_options() {
		
		$options = array(
			'ap_player_general' => 'spp_player_general',
			'ap_player_default' => 'spp_player_defaults',
			'ap_player_soundcloud' => 'spp_player_soundcloud'
		);

		foreach( $options as $old => $new ) {
			
			$option = get_option( $old );
			
			if( get_option( $new ) == false && $option !== false ) {
				add_option( $new, $option );
			}

			delete_option( $old );

		}

	}

	/**
	 * Automatically add spp as a body class
	 * 
	 * @return  void
	 */
	public function add_body_class() {
		echo "\n" . '<script type="text/javascript">document.getElementsByTagName(\'body\')[0].className+=\' spp\'</script>' . "\n";
	}

	public function add_csshead_class( $color = null ) {

		$options = get_option( 'spp_player_defaults' );

		if ( !empty ( $options ) && isset( $options['bg_color'] ) ) {
			$color = str_replace("#","",$options['bg_color']);
		}
		else {
			return;
		}

		if ( !empty ($color) ) {
			echo '<style>' . "\n\t";
			echo 'body.spp .smart-track-player .spp-track .spp-loaded-container { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-track-player .spp-track { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-podcast-player .spp-player-container .spp-track { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-podcast-player .spp-player-container .spp-track .spp-loaded-container { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-podcast-player .spp-track-details-container .spp-track-details .spp-button-subscribe { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-podcast-player .spp-track-details-container .spp-track-details .spp-button-download { background: #'.$color.'; }' . "\n\t";
			echo 'body.spp .smart-podcast-player .spp-track-details-container .spp-track-details .spp-button-downloada { background: #'.$color.'; }' . "\n\t";
			echo '</style>';
		}

	}

}
