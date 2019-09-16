<?php

class PeThemeShortcodeNestorButton extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pbutton";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Button",'Pixelentity Theme/Plugin');
		$this->description = __("Add a button",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"url" => array(
				"label"       => __("Url",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Enter the destination url of the button",'Pixelentity Theme/Plugin'),
			),
			"text" => array(
				"label"       => __("Text",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Enter the button text here",'Pixelentity Theme/Plugin'),
			),
			"new_window" => array(
				"label"       => __("Open in new window",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"description" => __("Should the url be opened in new window or not.",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__("Yes",'Pixelentity Theme/Plugin') => "yes",
					__("No",'Pixelentity Theme/Plugin')  => "no",
				),
				"default"     =>"no",
			),
			"color" => array(
				"label"       => __("Color",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"options"     => array(
					__( 'Default' ,'Pixelentity Theme/Plugin') => "btn-default",
					__( 'Success' ,'Pixelentity Theme/Plugin') => "btn-success",
					__( 'Info' ,'Pixelentity Theme/Plugin')    => "btn-info",
					__( 'Warning' ,'Pixelentity Theme/Plugin') => "btn-warning",
					__( 'Danger' ,'Pixelentity Theme/Plugin')  => "btn-danger",
					__( 'Aqua' ,'Pixelentity Theme/Plugin')    => "btn-aqua",
					__( 'Blue' ,'Pixelentity Theme/Plugin')    => "btn-blue",
					__( 'Brown' ,'Pixelentity Theme/Plugin')   => "btn-brown",
					__( 'Emerald' ,'Pixelentity Theme/Plugin') => "btn-emerald",
					__( 'Green' ,'Pixelentity Theme/Plugin')   => "btn-green",
					__( 'Orange' ,'Pixelentity Theme/Plugin')  => "btn-orange",
					__( 'Primary' ,'Pixelentity Theme/Plugin') => "btn-primary",
					__( 'Red' ,'Pixelentity Theme/Plugin')     => "btn-red",
					__( 'Violet' ,'Pixelentity Theme/Plugin')  => "btn-violet",
					__( 'Yellow' ,'Pixelentity Theme/Plugin')  => "btn-yellow",
				),
				"description" => __('Select button color scheme.','Pixelentity Theme/Plugin'),
				"default"     => "btn-default",
			),
			"size" => array(
				"label"       => __("Size",'Pixelentity Theme/Plugin'),
				"type"        => "RadioUI",
				"options"     => array(
					__( 'Normal' ,'Pixelentity Theme/Plugin') => 'btn-normal',
					__( 'Mini' ,'Pixelentity Theme/Plugin')   => 'btn-xs',
					__( 'Small' ,'Pixelentity Theme/Plugin')  => 'btn-sm',
					__( 'Large' ,'Pixelentity Theme/Plugin')  => 'btn-lg',
				),
				"description" => __('Select a button color scheme.','Pixelentity Theme/Plugin'),
				"default"     => "btn-normal",
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		if ( ! isset( $url ) ) $url = "#";

		$target = isset( $new_window ) && 'yes' === $new_window ? '_blank' : '_self';

		$color = isset( $color ) ? $color : 'btn-default';

		$size = isset( $size ) ? $size : 'btn-normal';

		$html = <<< EOT
<a href="$url" class="btn $color $size" target="$target">$text</a>
EOT;

        return trim( $html );

	}


}