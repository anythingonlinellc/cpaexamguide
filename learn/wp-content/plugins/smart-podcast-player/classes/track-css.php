<?php

require_once( SPP_PLUGIN_BASE . 'classes/utils/color.php' );
require_once( SPP_PLUGIN_BASE . 'classes/css-element.php' );

class SPP_Track_CSS {

	protected $_color;
	protected $_elements = array();

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $color ) {

		$this->_defaults = (array) get_option( 'spp_player_defaults' );

		$this->_color = $color;
		$this->setup();
		$this->setup_rules();

	}

	public function render() {

		$output = '<!-- Smart Podcast Player Custom Styles for color: #' . $this->_color .  "-->\n";
		$output .= '<style>';

			foreach( $this->_elements as $item ) {
				$output .= $item->render();
			}

		$output .= '</style>';

		return $output;

	}

	public function setup() {

		$specifier = isset( $this->_defaults['bg_color'] ) && str_replace( '#', '', $this->_defaults['bg_color'] ) == $this->_color ? '' : '.stp-color-' . $this->_color;

		$this->_elements = array(
			'track'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track' ),
			'loaded'			 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-loaded' ),
			'loaded_container'	 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-loaded-container' ),
			'button'				=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-button-download' ),
			'button_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-button-download:hover' ),
			'play'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-play' ),
			'play_active'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-playing .spp-track .spp-controls .spp-play' ),
			'play_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-play:hover' ),
			'dload'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-dload' ),
			'dload_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-dload:hover' ),
			'dloada'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-dloada' ),
			'dloada_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-dloada:hover' ),
			'speed'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-speed' ),
			'speed_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-speed:hover' ),
			'speed_half'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-speeding-half .spp-track .spp-controls .spp-speed' ),
			'speed_onehalf'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-speeding-onehalf .spp-track .spp-controls .spp-speed' ),
			'speed_two'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-speeding-two .spp-track .spp-controls .spp-speed' ),
			'speed_three'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-speeding-three .spp-track .spp-controls .spp-speed' ),
			'share'				 	=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-share' ),
			'share_hover'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-controls .spp-share:hover' ),
			'track_title'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-track-title' ),
			'artist'				=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-artist' ),
			'position'				=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-progress .spp-position:after' ),
			'current_time'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-progress .spp-current-time' ),
			'duration'				=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . '.spp-has-download .spp-duration' ),
			'show_count'			=> new SPP_CSS_Element( 'body.spp .smart-track-player' . $specifier . ' .spp-track .spp-show-count' )
		);

	}

	public function setup_rules() {

		global $post;

		$hex = '#' . $this->_color;

		$hsl = SPP_Utils_Color::hex_to_hsl( $hex );
		$brightness = SPP_Utils_Color::get_brightness( $this->_color );

		// Custom rules
		$this->_elements['track']->add_rules( array( 'background' => $hex ) );

		$this->_elements['loaded_container']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.0 ) ) );
		//$this->_elements['loaded']->add_rules( array( 'background' => '#FFF', 'opacity' => '.4' ) );

		$this->_elements['button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.1 ), 'color' => '#FFF' ) );
		$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.15 ), 'color' => '#FFF' ) );

		$this->_elements['track_title']->add_rules( array( 'color' => '#FFF' ) );
		$this->_elements['duration']->add_rules( array( 'color' => '#FFF', 'opacity' => .8 ) );
		$this->_elements['artist']->add_rules( array( 'color' => '#FFF' ) );

		$this->_elements['play']->add_rules( array( 'background-position' => '-66px 0', 'opacity' => '.71' ) );
		$this->_elements['play_active']->add_rules( array( 'background-position' => '-14px 0 !important' ) );
		$this->_elements['play_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['dload']->add_rules( array( 'background-position' => '-94px -52px', 'opacity' => '.71' ) );
		$this->_elements['dload_hover']->add_rules( array( 'opacity' => '.9' ) );
		$this->_elements['dloada']->add_rules( array( 'background-position' => '-94px -52px', 'opacity' => '.71' ) );
		$this->_elements['dloada_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['speed']->add_rules( array( 'background-position' => '-12px -52px', 'opacity' => '.71' ) );
		$this->_elements['speed_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['share']->add_rules( array( 'background-position' => '-53px -52px', 'opacity' => '.71' ) );
		$this->_elements['share_hover']->add_rules( array( 'opacity' => '.9' ) );

		$this->_elements['position']->add_rules( array( 'opacity' => '.5', 'background' => '#FFF' ) );

		if( $brightness > .6 ) {
			
			$this->_elements['loaded_container']->add_rules( array( 'background' => $hex ) );
			$this->_elements['loaded']->add_rules( array( 'opacity' => '.1' ) );
			
			$this->_elements['position']->add_rules( array( 'background' => $hex ) );
			$this->_elements['current_time']->add_rules( array( 'opacity' => '.1' ) );
			
			$this->_elements['track_title']->add_rules( array( 'color' => '#000', 'opacity' => .7 ) );
			$this->_elements['artist']->add_rules( array( 'color' => '#000' ) );
			$this->_elements['duration']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .25 ) ) );

			$this->_elements['button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .925 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .3 ) ) );
			$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, .875 ), 'color' => SPP_Utils_Color::tint_hex( $hex, .3 ) ) );

			$this->_elements['play']->add_rules( array( 'background-position' => '-66px -80px', 'opacity' => '.71' ) );
			$this->_elements['play_active']->add_rules( array( 'background-position' => '-14px -80px !important', 'opacity' => '.71' ) );
			$this->_elements['play_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['dload']->add_rules( array( 'background-position' => '-94px -132px', 'opacity' => '.71' ) );
			$this->_elements['dload_hover']->add_rules( array( 'opacity' => '.9' ) );
			$this->_elements['dloada']->add_rules( array( 'background-position' => '-94px -132px', 'opacity' => '.71' ) );
			$this->_elements['dloada_hover']->add_rules( array( 'opacity' => '.9' ) );

			$this->_elements['speed']->add_rules( array( 'background-position' => '-12px -132px', 'opacity' => '.71' ) );
			$this->_elements['speed_hover']->add_rules( array( 'opacity' => '.9' ) );
			$this->_elements['speed_half']->add_rules( array( 'background-position' => '0 -196px !important' ) );
			$this->_elements['speed_onehalf']->add_rules( array( 'background-position' => '-35px -196px !important' ) );
			$this->_elements['speed_two']->add_rules( array( 'background-position' => '-70px -196px !important' ) );
			$this->_elements['speed_three']->add_rules( array( 'background-position' => '-105px -196px !important' ) );

			$this->_elements['share']->add_rules( array( 'background-position' => '-53px -132px', 'opacity' => '.71' ) );
			$this->_elements['share_hover']->add_rules( array( 'opacity' => '.9' ) );
		} 

		if( $brightness < .2 ) {

			$this->_elements['loaded_container']->add_rules( array( 'background' => $hex ) );
			$this->_elements['loaded']->add_rules( array( 'background' => '#FFF', 'opacity' => '.17' ) );

			$this->_elements['current_time']->add_rules( array( 'background' => '#FFF', 'opacity' => '.17' ) );

			$this->_elements['button']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.5 ), 'color' => '#FFF' ) );
			$this->_elements['button_hover']->add_rules( array( 'background' => SPP_Utils_Color::tint_hex( $hex, 1.7 ), 'color' => '#FFF' ) );

		}

	}

}
