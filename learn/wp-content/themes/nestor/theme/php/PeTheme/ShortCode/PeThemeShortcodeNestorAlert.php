<?php

class PeThemeShortcodeNestorAlert extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pealert";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Alert",'Pixelentity Theme/Plugin');
		$this->description = __("Add an alert",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"text" => array(
				"label"       => __("Text",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Text displayed inside an alert.",'Pixelentity Theme/Plugin'),
			),
			"color_scheme" => array(
				"label"       => __("Color scheme",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"description" => __("Select a color scheme.",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__("Success",'Pixelentity Theme/Plugin') => "success",
					__("Info",'Pixelentity Theme/Plugin')    => "info",
					__("Warning",'Pixelentity Theme/Plugin') => "warning",
					__("Danger",'Pixelentity Theme/Plugin')  => "danger",
				),
				"default"     =>"info",
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$text = isset( $text ) ? $text : '';

		$color_scheme = isset( $color_scheme ) ? $color_scheme : 'primary';

		$html = <<< EOT
<div class="alert alert-$color_scheme">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	$text
</div>
EOT;

        return trim( $html );

	}


}