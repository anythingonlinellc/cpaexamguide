<?php

class SPP_Admin_Settings {

	public $plugin_slug;
	
	public function __construct() {

		$plugin = SPP_Core::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		add_action( 'admin_menu', array( $this, 'register' ) );
		add_action( 'admin_init', array( $this, 'settings_sections' ) );

	}


	public function register() {
		
		register_setting( 'spp-player-soundcloud', 'spp_player_soundcloud' );
		register_setting( 'spp-player', 'spp_player_social' );
		register_setting( 'spp-player-general', 'spp_player_general' );
		register_setting( 'spp-player-defaults', 'spp_player_defaults' );
		register_setting( 'spp-player-advanced', 'spp_player_advanced' );
		
		add_options_page( 'Smart Podcast Player Settings', 'Smart Podcast Player', 'manage_options', 'spp-player', array( $this, 'settings_page' ) );

	}

	public function settings_page() {
		require_once( SPP_ASSETS_PATH . 'views/settings.php' );
	}

	public function settings_sections() {		

		add_settings_section(  
	        'spp_player_general_settings',
	        '',
	        array( $this, 'general_section' ),
	        'spp-player-general'
	    ); 

			add_settings_field(   
			    'spp_player_general[license_key]',
			    'License Key: ',
			    array( $this, 'field_license_key' ),
			    'spp-player-general',
			    'spp_player_general_settings'
			);

		add_settings_section(  
	        'spp_player_soundcloud_settings',
	        '',
	        array( $this, 'soundcloud_section' ),
	        'spp-player-soundcloud'
	    ); 

			add_settings_field(   
			    'spp_player_soundcloud[consumer_key]',
			    'API Consumer Key: ',
			    array( $this, 'field_soundcloud_api_key' ),
			    'spp-player-soundcloud',
			    'spp_player_soundcloud_settings'
			);

		add_settings_section(  
	        'spp_player_default_section',
	        'Podcast Player Defaults',
	        array( $this, 'default_section' ),
	        'spp-player-defaults'
	    ); 

	    	add_settings_field(   
			    'spp_player_defaults[bg_color]',
			    'Background Color: ',
			    array( $this, 'field_default_color' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);



			add_settings_field(   
			    'spp_player_defaults[link_color]',
			    'Link Color: ',
			    array( $this, 'field_default_link_color' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[style]',
			    'Style: ',
			    array( $this, 'field_default_style' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[sort_order]',
			    'Sort Order: ',
			    array( $this, 'field_default_sort_order' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[url]',
			    'Podcast RSS Feed URL: ',
			    array( $this, 'field_default_url' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[subscription]',
			    'Subscription URL: ',
			    array( $this, 'field_default_subscription' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[show_name]',
			    'Show Name: ',
			    array( $this, 'field_default_show_name' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[stp_image]',
			    'Track Player Image URL: ',
			    array( $this, 'field_default_stp_image' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_field(   
			    'spp_player_defaults[poweredby]',
			    'Powered By SPP: ',
			    array( $this, 'field_default_poweredby' ),
			    'spp-player-defaults',
			    'spp_player_default_section'
			);

			add_settings_section(  
		        'spp_player_advanced_settings',
		        '',
		        array( $this, 'advanced_section' ),
		        'spp-player-advanced'
	    	); 

			add_settings_field(   
			    'spp_player_advanced[cache_timeout]',
			    'Cache Timeout: ',
			    array( $this, 'field_advanced_cache_timeout' ),
			    'spp-player-advanced',
			    'spp_player_advanced_settings'
			);

			add_settings_field(   
			    'spp_player_advanced[downloader]',
			    'Download Method: ',
			    array( $this, 'field_advanced_downloader' ),
			    'spp-player-advanced',
			    'spp_player_advanced_settings'
			);

	}

	public function default_section() {}
	public function social_section() {}
	public function general_section() {}
	public function soundcloud_section() {}
	public function advanced_section() {}

	public function field_default_color() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $disabled = '';
        if ( !SPP_Core::is_paid_version() ) 
        	$disabled = 'disabled';

        $free_colors = SPP_Core::get_free_colors();
        $color = isset( $settings['bg_color'] ) && !empty( $settings['bg_color'] ) ? $settings['bg_color'] : SPP_Core::SPP_DEFAULT_PLAYER_COLOR;
        $other_selected = isset( $settings['bg_color'] ) && !in_array( $settings['bg_color'], $free_colors ) ? 'selected="selected"' : '';

		// Construct the drop-down menu of colors
        $html .= '<div class="spp-color-picker">';
        $html .= '<select class="spp-color-list" name="spp_player_defaults[bg_color]" '. $disabled .'>';
		// Add all the free version's colors
		foreach( $free_colors as $color_name => $hex ) {
			$html .= '<option value="' . $hex . '" '
				. selected( strtolower( $color ), $hex, false )
				. '>' . $color_name . '</option>';
		}
		// For the paid version, add the 'other' option
		if (SPP_Core::is_paid_version()) {
			$html .= '<option value="other" ' . $other_selected . '> Other</option>';
		}
        $html .= '</select>';
		
		// Color picker for paid version
		if (SPP_Core::is_paid_version()) {
			$html .= ' or ';
			$html .= '<input type="text" class="color-field" name="spp_player_defaults[bg_color]" value="' . $color . '" />';
		}
		
        $html .= '</div>';

		echo $html;

	}

	public function field_default_link_color() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $disabled = '';
        if ( !SPP_Core::is_paid_version() ) 
        	$disabled = 'disabled';

        $free_colors = SPP_Core::get_free_colors();
        $color = isset( $settings['link_color'] ) && !empty( $settings['link_color'] ) ? $settings['link_color'] : SPP_Core::SPP_DEFAULT_PLAYER_COLOR;
        $other_selected = isset( $settings['link_color'] ) && !in_array( $settings['link_color'], $free_colors ) ? 'selected="selected"' : '';

		// Construct the drop-down menu of colors
        $html .= '<div class="spp-color-picker">';
        $html .= '<select class="spp-color-list" name="spp_player_defaults[link_color]" '. $disabled .'>';
		// Add all the free version's colors
		foreach( $free_colors as $color_name => $hex ) {
			$html .= '<option value="' . $hex . '" '
				. selected( strtolower( $color ), $hex, false )
				. '>' . $color_name . '</option>';
		}
		// For the paid version, add the 'other' option
		if (SPP_Core::is_paid_version()) {
			$html .= '<option value="other" ' . $other_selected . '> Other</option>';
		}
        $html .= '</select>';

		// Color picker for paid version
		if (SPP_Core::is_paid_version()) {
			$html .= ' or ';
			$html .= '<input type="text" class="color-field" name="spp_player_defaults[link_color]" value="' . $color . '" />';
		}
	
        $html .= '</div>';

		echo $html;

	}

	public function field_default_url() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['url'] ) ? $settings['url'] : '';

        $html .= '<input type="text" name="spp_player_defaults[url]" value="' . $val . '" size="40" />';
        
		echo $html;

	}

	public function field_default_subscription() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['subscription'] ) ? $settings['subscription'] : '';

        $html .= '<input type="text" name="spp_player_defaults[subscription]" value="' . $val . '" size="40" />';
        
		echo $html;

	}

	public function field_default_show_name() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['show_name'] ) ? $settings['show_name'] : '';

        $html .= '<input type="text" name="spp_player_defaults[show_name]" value="' . $val . '" size="40" />';
        
		echo $html;

	}

	public function field_default_stp_image() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['stp_image'] ) ? $settings['stp_image'] : '';

        $html .= '<input type="text" name="spp_player_defaults[stp_image]" value="' . $val . '" size="40" />';
        
		echo $html;

	}

	public function field_default_poweredby() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $disabled = '';
        if ( !SPP_Core::is_paid_version() ) 
        	$disabled = 'disabled';

        $val = isset( $settings['poweredby'] ) ? $settings['poweredby'] : 'true';

        $html .= '<select name="spp_player_defaults[poweredby]" '. $disabled .'>';
        	$html .= '<option ' . selected( $val, 'true', false ) . ' value="true">On</option>';
        	$html .= '<option value="false" ' . selected( $val, 'false', false ) . ' >Off</option>';
        $html .= '</select>';

		if ( !SPP_Core::is_paid_version() ) 
        	$html .= '<BR><BR>Disabled features are available with paid version,<BR>visit <a href="https://smartpodcastplayer.com">smartpodcastplayer.com</a> to upgrade.';
        
		echo $html;

	}

	public function field_default_style() {
		
		$html = '';
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['style'] ) ? $settings['style'] : '';

        $html .= '<select name="spp_player_defaults[style]">';
        	$html .= '<option ' . selected( $val, 'light', false ) . ' value="light">Light</option>';
        	$html .= '<option value="dark" ' . selected( $val, 'dark', false ) . ' >Dark</option>';
        $html .= '</select>';
        
		echo $html;

	}

	public function field_default_sort_order() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_defaults' );

        $val = isset( $settings['sort_order'] ) ? $settings['sort_order'] : '';

        $disabled = '';
        if ( !SPP_Core::is_paid_version() ) 
        	$disabled = 'disabled';

        $html .= '<select name="spp_player_defaults[sort_order]" '. $disabled .'>';
        	$html .= '<option ' . selected( $val, 'newest', false ) . ' value="newest">Newest to Oldest</option>';
        	$html .= '<option value="oldest" ' . selected( $val, 'oldest', false ) . ' >Oldest to Newest</option>';
        $html .= '</select>';
        
		echo $html;

	}

	public function field_license_key() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_general' );

        if( isset( $settings[ 'license_key' ] ) || !empty( $settings[ 'license_key' ] ) )  {
        	// Facilitate fresh "live" license key check after key is entered
        	$optionName = 'external_updates-smart-podcast-player';
        	delete_site_option($optionName);
    	}

        $html .= '<input type="text" name="spp_player_general[license_key]" value="' . $settings['license_key'] . '" size="50" />';
        $html .= '<p class="description"><small>Your license key was delivered to you at the time of purchase, and in your email receipt. If you have any difficulty locating your license key, please email <a href="mailto:support@smartpodcastplayer.com">support@smartpodcastplayer.com</a>.</small></p>';

		echo $html;

	}

	public function field_soundcloud_api_key() {
		
		$html = '';  
        
        $settings = is_array( get_option( 'spp_player_soundcloud' ) ) ? get_option( 'spp_player_soundcloud' ) : array();
        $consumer_key = isset( $settings['consumer_key'] ) ? $settings['consumer_key'] : '';

        $html .= '<input type="text" name="spp_player_soundcloud[consumer_key]" value="' . $consumer_key . '" size="50" />';
        $html .= '<p class="description"><small>Visit your <a href="http://soundcloud.com/you/apps">SoundCloud Apps page</a> to create your app and retrieve your app\'s <strong>Consumer Key</strong>. The player will not work with SoundCloud tracks until you submit a valid API key.</small></p>';
		echo $html;
		
	}

	public function field_soundcloud_url() {
		$html = '';  
        
        $settings = is_array( get_option( 'spp_player_soundcloud' ) ) ? get_option( 'spp_player_soundcloud' ) : array();
        $url = isset( $settings['url'] ) ? $settings['url'] : '';

        $html .= '<input type="text" name="spp_player_soundcloud[url]" value="' . $url . '" />';
        $html .= '<p class="description"><small>Paste a link to your Soundcloud profile (ex. <a href="https://soundcloud.com/askpat">https://soundcloud.com/askpat</a>) to play all the tracks in your account, or a link to a single playlist (ex. <a href="https://soundcloud.com/askpat/sets/askpat">https://soundcloud.com/askpat/sets/askpat</a>.</small></p>';

		echo $html;
		
	}

	public function field_twitter_hashtag() {

		$html = '';  
        
        $settings = get_option( 'spp_player_social' );

        $html .= '#<input type="text" name="spp_player_social[twitter_hashtag]" value="' . $settings['twitter_hashtag'] . '" />';

		echo $html;

	}

	public function field_advanced_cache_timeout() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_advanced' );

        $val = isset( $settings['cache_timeout'] ) ? $settings['cache_timeout'] : '15';

        $html .= '<input type="text" name="spp_player_advanced[cache_timeout]" value="' . $val . '" /> minutes';
        
		echo $html;

	}

	public function field_advanced_downloader() {
		
		$html = '';  
        
        $settings = get_option( 'spp_player_advanced' );

        $val = isset( $settings['downloader'] ) ? $settings['downloader'] : 'fopen';

        $disabled = '';
        if ( !SPP_Core::is_paid_version() ) 
        	$disabled = 'disabled';

        $html .= '<select name="spp_player_advanced[downloader]" '. $disabled .'>';
        	$html .= '<option ' . selected( $val, 'smart', false ) . ' value="smart">Automatic (Recommended)</option>';
        	$html .= '<option ' . selected( $val, 'fopen', false ) . ' value="fopen">Stream (fopen)</option>';
        	$html .= '<option ' . selected( $val, 'local', false ) . ' value="local">Local File Cache</option>';
        $html .= '</select>';
        
		echo $html;

	}

}