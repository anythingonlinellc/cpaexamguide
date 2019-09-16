<?php

class PeThemeShortcodeNestorLabel extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pelabel";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Label",'Pixelentity Theme/Plugin');
		$this->description = __("Add a label",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"text" => array(
				"label"       => __("Text",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Enter the label text here",'Pixelentity Theme/Plugin'),
			),
			"color" => array(
				"label"       => __("Color",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"options"     => array(
					__( 'Default' ,'Pixelentity Theme/Plugin') => "label-default",
					__( 'Success' ,'Pixelentity Theme/Plugin') => "label-success",
					__( 'Info' ,'Pixelentity Theme/Plugin')    => "label-info",
					__( 'Warning' ,'Pixelentity Theme/Plugin') => "label-warning",
					__( 'Danger' ,'Pixelentity Theme/Plugin')  => "label-danger",
					__( 'Aqua' ,'Pixelentity Theme/Plugin')    => "label-aqua",
					__( 'Blue' ,'Pixelentity Theme/Plugin')    => "label-blue",
					__( 'Brown' ,'Pixelentity Theme/Plugin')   => "label-brown",
					__( 'Emerald' ,'Pixelentity Theme/Plugin') => "label-emerald",
					__( 'Green' ,'Pixelentity Theme/Plugin')   => "label-green",
					__( 'Orange' ,'Pixelentity Theme/Plugin')  => "label-orange",
					__( 'Red' ,'Pixelentity Theme/Plugin')     => "label-red",
					__( 'Violet' ,'Pixelentity Theme/Plugin')  => "label-violet",
					__( 'Yellow' ,'Pixelentity Theme/Plugin')  => "label-yellow",
				),
				"description" => __('Select label color scheme.','Pixelentity Theme/Plugin'),
				"default"     => "label-default",
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$color = isset( $color ) ? $color : 'label-default';

		$html = <<< EOT
<span class="label $color">$text</span>
EOT;

        return trim( $html );

	}


}