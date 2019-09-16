<?php

class PeThemeViewLayoutModuleNestorPanel extends PeThemeViewLayoutModuleText {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Panel",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"icon" => array(
					"label"       => __("Icon",'Pixelentity Theme/Plugin'),
					"type"        => "Icon",
					"description" => __("Panel Icon.",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
				"title" => array(
					"label"       => __("Title",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Panel title.",'Pixelentity Theme/Plugin'),
					"default"     => __("Brand Strategy",'Pixelentity Theme/Plugin'),
				),
				"content" => array(
					"label"       => "Content",
					"type"        => "TextArea",
					"noscript"    => true,
					"description" => __("Content",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
			);
		
	}

	public function name() {
		return __("Panel",'Pixelentity Theme/Plugin');
	}

	public function group() {
		return "panel";
	}

	public function render() {
		// do nothing here since the rendering happens in the parent template
	}

	public function tooltip() {
		return __("Use this block to add a new panel.",'Pixelentity Theme/Plugin');
	}

}

?>