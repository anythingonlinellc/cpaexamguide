<?php

class PeThemeShortcodeNestorWell extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pewell";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Well",'Pixelentity Theme/Plugin');
		$this->description = __("Add a well",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"text" => array(
				"label"       => __("Text",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Text displayed inside well.",'Pixelentity Theme/Plugin'),
			),
			"size" => array(
				"label"       => __("Well size",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"description" => __("Select a well size.",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__("Normal",'Pixelentity Theme/Plugin') => "normal",
					__("Small",'Pixelentity Theme/Plugin')  => "sm",
					__("Large",'Pixelentity Theme/Plugin')  => "lg",
				),
				"default"     =>"normal",
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$text = isset( $text ) ? $text : '';

		$size = isset( $size ) ? $size : 'normal';

		$html = <<< EOT
<div class="well well-$size">
	$text
</div>
EOT;

        return trim( $html );

	}


}