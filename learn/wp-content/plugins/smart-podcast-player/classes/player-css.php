<?php

require_once( SPP_PLUGIN_BASE . 'classes/utils/color.php' );
require_once( SPP_PLUGIN_BASE . 'classes/css-element.php' );

class SPP_Player_CSS {
	
	protected $_color;
	protected $_defaults;
	protected $_elements = array();

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $hex ) {

		$this->_defaults = (array) get_option( 'spp_player_defaults' );

		$this->_color = $hex;
		$this->setup();
		$this->setup_rules();

	}

	public function render() {

		$output = '<!-- Smart Podcast Player Custom Styles for color: #' . $this->_color . " -->\n";
		$output .= '<style>';

			foreach( $this->_elements as $item ) {
				$output .= $item->render();
			}

		$output .= '</style>';

		return $output;

	}

	public function setup() {

		// We only use the specifier if the color is not the default
		$specifier = isset( $this->_defaults['bg_color'] ) && str_replace( '#', '', $this->_defaults['bg_color'] ) == $this->_color ? '' : '.spp-color-' . $this->_color;

		$this->_elements = array(
			'container'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container' ),
			'track'							=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track' ),
			'artist'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-artist' ),
			'duration'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player .spp-duration' ),
			'position'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-progress .spp-position' ),
			'current_time'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-progress .spp-current-time' ),
			'loaded_container'				=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-loaded-container' ),
			'loaded'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-loaded' ),
			'track_details_links'			=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a' ),
			'track_details_links_visited'	=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a:visited' ),
			'track_details_links_hover'		=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a:hover' ),
			'button'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-download' ),
			'button_after'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-download:after' ),
			'button_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-download:hover' ),
			'button_dla'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-downloada' ),
			'button_dla_after'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-downloada:after' ),
			'button_dla_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-downloada:hover' ),
			'sub_button'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-subscribe' ),
			'sub_button_after'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-subscribe:after' ),
			'sub_button_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-button-subscribe:hover' ),
			'show_notes_button'				=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player .spp-show-notes-button' ),
			'show_notes_button_hover'		=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player .spp-show-notes-button:hover' ),
			'track_title'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-track-title' ),
			'show_count'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-show-count' ),
			'show_title'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-show-title' ),
			'play' 							=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-play' ),
			'play_active'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player.spp-playing .spp-track .spp-controls .spp-play' ),
			'play_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-play:hover' ),
			'next' 							=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-next' ),
			'next_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-next:hover' ),
			'previous'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-previous' ),
			'previous_hover'				=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-previous:hover' ),
			'speed' 							=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-speed' ),
			'speed_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-track .spp-controls .spp-speed:hover' ),
			'speed_half'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player.spp-speeding-half .spp-track .spp-controls .spp-speed' ),
			'speed_onehalf'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player.spp-speeding-onehalf .spp-track .spp-controls .spp-speed' ),
			'speed_two'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player.spp-speeding-two .spp-track .spp-controls .spp-speed' ),
			'speed_three'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-player-container .spp-player.spp-speeding-three .spp-track .spp-controls .spp-speed' ),
			// We have to add separate rules because FF doesn't like background-position-y
			'dark_play' 						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-play' ),
			'dark_play_active'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-player.spp-playing .spp-track .spp-controls .spp-play' ),
			'dark_play_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-play:hover' ),
			'dark_next' 						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-next' ),
			'dark_next_hover'					=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-next:hover' ),
			'dark_previous'						=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-previous' ),
			'dark_previous_hover'				=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . '.smart-podcast-player-dark .spp-player-container .spp-track .spp-controls .spp-previous:hover' ),
			'text'								=> new SPP_CSS_Element( 'body.spp .smart-podcast-player h1, body.spp .smart-podcast-player h2, body.spp .smart-podcast-player h3, body.spp .smart-podcast-player h4, body.spp .smart-podcast-player h5, body.spp .smart-podcast-player h6, body.spp .smart-podcast-player p, body.spp .smart-podcast-player li, body.spp .smart-podcast-player a, body.spp .smart-podcast-player blockquote, body.spp .smart-podcast-player span, body.spp .smart-podcast-player b, body.spp .smart-podcast-player strong, body.spp .smart-podcast-player em, body.spp .smart-podcast-player i, body.spp .smart-podcast-player table, body.spp .smart-podcast-player td, body.spp .smart-podcast-player th, body.spp .smart-podcast-player tr' )
		);

	}

	public function setup_rules() {

		global $post;

		$hex = '#' . $this->_color;

		$hsl = SPP_Utils_Color::hex_to_hsl( $hex );
		$brightness = SPP_Utils_Color::get_brightness( $this->_color );

		$this->_elements['track']->add_rules( array( 'background' => $hex ) );

		$this->_elements['loaded_container']->add_rules( array( 'background' => $hex ) );
		//$this->_elements['loaded']->add_rules( array( 'background' => '#FFF', 'opacity' => '.4' ) );

		$this->_elements['button']->add_rules( array( 'background' => $hex, 'color' => '#FFF' ) );
		$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.25 ), 'color' => '#FFF' ) );
		$this->_elements['button_dla']->add_rules( array( 'background' => $hex, 'color' => '#FFF' ) );
		$this->_elements['button_dla_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.25 ), 'color' => '#FFF' ) );
		
		$this->_elements['sub_button']->add_rules( array( 'background' => $hex, 'color' => '#FFF' ) );
		$this->_elements['sub_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.25 ), 'color' => '#FFF' ) );

		$this->_elements['show_notes_button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.4 ), 'color' => '#FFF' ) );
		$this->_elements['show_notes_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.8 ), 'color' => '#FFF' ) );

		$this->_elements['position']->add_rules( array( 'background' => '#FFFFFF' ) );

		$this->_elements['track_title']->add_rules( array( 'color' => '#FFF' ) );
		$this->_elements['duration']->add_rules( array( 'color' => '#FFF', 'opacity' => .8 ) );
		$this->_elements['artist']->add_rules( array( 'color' => '#FFF' ) );

		$this->_elements['show_count']->add_rules( array( 'color' => '#FFF' ) );
		$this->_elements['show_title']->add_rules( array( 'color' => '#FFF' ) );

		$this->_elements['next']->add_rules( array( 'background-position' => '-118px -17px', 'opacity' => '.71' ) );
		$this->_elements['next_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['previous']->add_rules( array( 'background-position' => '0 -17px', 'opacity' => '.71' ) );
		$this->_elements['previous_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['play']->add_rules( array( 'background-position' => '-66px 0', 'opacity' => '.71' ) );
		$this->_elements['play_active']->add_rules( array( 'background-position' => '-14px 0 !important', 'opacity' => '.71' ) );
		$this->_elements['play_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['speed']->add_rules( array( 'background-position' => '-12px -52px', 'opacity' => '.71' ) );
		$this->_elements['speed_hover']->add_rules( array( 'opacity' => '.9' ) );

		if( $brightness > .60 ) {

			$this->_elements['loaded_container']->add_rules( array( 'background' => $hex ) );
			$this->_elements['loaded']->add_rules( array( 'opacity' => '.1' ) );
			
			$this->_elements['position']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );
			$this->_elements['current_time']->add_rules( array( 'opacity' => '.1' ) );
			
			$this->_elements['track_title']->add_rules( array( 'color' => '#000', 'opacity' => .7 ) );
			$this->_elements['artist']->add_rules( array( 'color' => '#000' ) );
			$this->_elements['duration']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .25 ) ) );

			$this->_elements['show_count']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .25 ) ) );
			$this->_elements['show_title']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .25 ) ) );

			$this->_elements['button']->add_rules( array( 'background' => $hex, 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );
			$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .95 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );

			$this->_elements['button_dla']->add_rules( array( 'background' => $hex, 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );
			$this->_elements['button_dla_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .95 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );


			$this->_elements['sub_button']->add_rules( array( 'background' => $hex, 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );
			$this->_elements['sub_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .95 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .2 ) ) );

			$this->_elements['show_notes_button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .925 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .3 ) ) );
			$this->_elements['show_notes_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .875 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .3 ) ) );


			$this->_elements['next']->add_rules( array( 'background-position' => '-118px -97px', 'opacity' => '.71' ) );
			$this->_elements['next_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['previous']->add_rules( array( 'background-position' => '0 -97px', 'opacity' => '.71' ) );
			$this->_elements['previous_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['play']->add_rules( array( 'background-position' => '-66px -80px', 'opacity' => '.71' ) );
			$this->_elements['play_active']->add_rules( array( 'background-position' => '-14px -80px !important', 'opacity' => '.71' ) );
			$this->_elements['play_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['speed']->add_rules( array( 'background-position' => '-12px -132px', 'opacity' => '.71' ) );
			$this->_elements['speed_hover']->add_rules( array( 'opacity' => '.9' ) );
			$this->_elements['speed_half']->add_rules( array( 'background-position' => '0 -196px !important' ) );
			$this->_elements['speed_onehalf']->add_rules( array( 'background-position' => '-35px -196px !important' ) );
			$this->_elements['speed_two']->add_rules( array( 'background-position' => '-70px -196px !important' ) );
			$this->_elements['speed_three']->add_rules( array( 'background-position' => '-105px -196px !important' ) );

			$this->_elements['dark_next']->add_rules( array( 'background-position' => '-118px -97px', 'opacity' => '.71' ) );
			$this->_elements['dark_next_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['dark_previous']->add_rules( array( 'background-position' => '0 -97px', 'opacity' => '.71' ) );
			$this->_elements['dark_previous_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['dark_play']->add_rules( array( 'background-position' => '-66px -80px', 'opacity' => '.71' ) );
			$this->_elements['dark_play_active']->add_rules( array( 'background-position' => '-14px -80px', 'opacity' => '.71' ) );
			$this->_elements['dark_play_hover']->add_rules( array( 'opacity' => '.9' ) );

		} 

		if( $brightness < .2 ) {

			$this->_elements['loaded_container']->add_rules( array( 'background' => $hex ) );
			$this->_elements['loaded']->add_rules( array( 'background' => '#FFF', 'opacity' => '.17' ) );

			$this->_elements['current_time']->add_rules( array( 'background' => '#FFF', 'opacity' => '.17' ) );

			$this->_elements['show_notes_button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.5 ), 'color' => '#FFF' ) );
			$this->_elements['show_notes_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.7 ), 'color' => '#FFF' ) );

			$this->_elements['button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 2 ) ) );
			$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 3 ) ) );

			$this->_elements['button_dla']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 2 ) ) );
			$this->_elements['button_dla_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 3 ) ) );

			$this->_elements['sub_button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 2 ) ) );
			$this->_elements['sub_button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 3 ) ) );

		}

	}

}
