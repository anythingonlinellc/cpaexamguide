<?php

require_once( SPP_PLUGIN_BASE . 'classes/utils/color.php' );
require_once( SPP_PLUGIN_BASE . 'classes/css-element.php' );

class SPP_Player_Link_CSS {
	
	protected $_color, $_defaults;
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

		$output = '<!-- Smart Podcast Player Custom Link Styles for color: #' . $this->_color . " -->\n";
		$output .= '<style>';

			foreach( $this->_elements as $item ) {
				$output .= $item->render();
			}

		$output .= '</style>';

		return $output;

	}

	public function setup() {

		// We only use the specifier if the color is not the default
		$specifier = isset( $this->_defaults['bg_color'] ) && str_replace( '#', '', $this->_defaults['bg_color'] ) == $this->_color ? '' : '.smart-podcast-player.spp-link-color-' . $this->_color;

		$this->_elements = array(
			'track_details_links'			=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a' ),
			'track_details_links_visited'	=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a:visited' ),
			'track_details_links_hover'		=> new SPP_CSS_Element( 'body.spp .smart-podcast-player' . $specifier . ' .spp-track-details-container .spp-track-details .spp-track-details-body a:hover' ),
		);

	}

	public function setup_rules() {

		global $post;

		$hex = '#' . $this->_color;

		$hsl = SPP_Utils_Color::hex_to_hsl( $hex );
		$brightness = $hsl[2];

		$this->_elements['track_details_links']->add_rules( array( 'color' => $hex ) );
		$this->_elements['track_details_links_hover']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .8 ) ) );
		$this->_elements['track_details_links_visited']->add_rules( array( 'color' => SPP_Utils_Color::tint_hex( $hex, .9 ) ) );

		if( $brightness > .80 ) {
			
		} 

		if( $brightness < .2 ) {

		}
		
	}

}
