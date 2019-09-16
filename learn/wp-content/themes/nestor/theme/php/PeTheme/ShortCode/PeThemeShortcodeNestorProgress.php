<?php

class PeThemeShortcodeNestorProgress extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "peprogress";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Progress Bar",'Pixelentity Theme/Plugin');
		$this->description = __("Add a progress bar",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"text" => array(
				"label"       => __("Text",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Text displayed inside progress bar.",'Pixelentity Theme/Plugin'),
			),
			"width" => array(
				"label"       => __("Width",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Width of the progress bar (number only).",'Pixelentity Theme/Plugin'),
			),
			"color_scheme" => array(
				"label"       => __("Color scheme",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"description" => __("Select a color scheme.",'Pixelentity Theme/Plugin'),
				"options"     => array(
					__( 'Aqua' ,'Pixelentity Theme/Plugin')    => "aqua",
					__( 'Blue' ,'Pixelentity Theme/Plugin')    => "blue",
					__( 'Brown' ,'Pixelentity Theme/Plugin')   => "brown",
					__( 'Danger' ,'Pixelentity Theme/Plugin')  => "danger",
					__( 'Default' ,'Pixelentity Theme/Plugin') => "default",
					__( 'Emerald' ,'Pixelentity Theme/Plugin') => "emerald",
					__( 'Green' ,'Pixelentity Theme/Plugin')   => "green",
					__( 'Info' ,'Pixelentity Theme/Plugin')    => "info",
					__( 'Orange' ,'Pixelentity Theme/Plugin')  => "orange",
					__( 'Red' ,'Pixelentity Theme/Plugin')     => "red",
					__( 'Success' ,'Pixelentity Theme/Plugin') => "success",
					__( 'Violet' ,'Pixelentity Theme/Plugin')  => "violet",
					__( 'Warning' ,'Pixelentity Theme/Plugin') => "warning",
					__( 'Yellow' ,'Pixelentity Theme/Plugin')  => "yellow",
					__( 'Primary' ,'Pixelentity Theme/Plugin') => "primary",
				),
				"default"     =>"primary",
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$text = isset( $text ) ? $text : '';

		$width = isset( $width ) ? absint( $width ) : '100';

		$color_scheme = isset( $color_scheme ) ? $color_scheme : 'primary';

		$html = <<< EOT
<div class="progress">
	<div class="progress-bar progress-bar-{$color_scheme}" role="progressbar" aria-valuenow="$width" aria-valuemin="0" aria-valuemax="100" style="width: $width%;">
		<span>$text</span>
	</div>
</div>
EOT;

        return trim( $html );

	}


}