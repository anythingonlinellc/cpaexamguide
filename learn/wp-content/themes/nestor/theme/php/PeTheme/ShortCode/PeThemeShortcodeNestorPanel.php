<?php

class PeThemeShortcodeNestorPanel extends PeThemeShortcode {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "pepanel";
		$this->group = __("UI",'Pixelentity Theme/Plugin');
		$this->name = __("Panel",'Pixelentity Theme/Plugin');
		$this->description = __("Add a panel.",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"icon" => array(
				"label"       => __("Icon",'Pixelentity Theme/Plugin'),
				"type"        => "Icon",
				"description" => __("Select the icon",'Pixelentity Theme/Plugin'),
			),
			"title" => array(
				"label"       => __("Title",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Title of the panel.",'Pixelentity Theme/Plugin'),
			),
			"content" => array(
				"label"       => __("Content",'Pixelentity Theme/Plugin'),
				"type"        => "TextArea",
				"description" => __("Content of the panel.",'Pixelentity Theme/Plugin'),
			),
		);
	}

	public function output( $atts, $content = null, $code = '' ) {

		extract( $atts );

		$icon = isset( $icon ) ? $icon : 'ion-compass';

		$title = isset( $title ) ? $title : '';
		$content = isset( $content ) ? $content : '';

		$html = <<< EOT
<div class="panels-2">
<div class="panels-item margin-bottom-30">
<i class="icon $icon text-color-theme"></i>
<h6>$title</h6>
<p>$content</p>
</div>
</div>
EOT;

        return trim( $html );

	}


}