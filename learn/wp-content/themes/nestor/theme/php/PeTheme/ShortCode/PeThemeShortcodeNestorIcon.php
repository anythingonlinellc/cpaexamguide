<?php

class PeThemeShortcodeNestorIcon extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "peicon";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Icon",'Pixelentity Theme/Plugin');
		$this->description = __("Add an icon",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"size" => array(
				"label"       => __("Size",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"options"     => array(
					__( '16px' ,'Pixelentity Theme/Plugin') => 'size-16',
					__( '32px' ,'Pixelentity Theme/Plugin') => 'size-32',
					__( '48px' ,'Pixelentity Theme/Plugin') => 'size-48',
					__( '64px' ,'Pixelentity Theme/Plugin') => 'size-64',
					__( '128px' ,'Pixelentity Theme/Plugin') => 'size-128',
				),
				"description" => __("Choose between several available icon sizes.",'Pixelentity Theme/Plugin'),
				"default"     => 'size-32',
			),
			"icon" => array(
				"label"       => __("Icon",'Pixelentity Theme/Plugin'),
				"type"        => "Icon",
				"description" => __("Select the icon",'Pixelentity Theme/Plugin'),
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$icon = isset( $icon ) ? $icon : 'ion-compass';

		$size = isset( $size ) ? $size : 'size-32';

		$html = <<< EOT
<i class="icon $icon $size text-color-theme"></i>
EOT;

        return trim( $html );

	}


}