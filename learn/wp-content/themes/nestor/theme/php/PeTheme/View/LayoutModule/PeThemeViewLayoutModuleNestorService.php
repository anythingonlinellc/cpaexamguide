<?php

class PeThemeViewLayoutModuleNestorService extends PeThemeViewLayoutModuleText {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Service",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"icon" => array(
					"label"       => __("Icon",'Pixelentity Theme/Plugin'),
					"type"        => "Icon",
					"description" => __("Service Icon.",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
				"title" => array(
					"label"       => __("Title",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Service title.",'Pixelentity Theme/Plugin'),
					"default"     => __("Brand Strategy",'Pixelentity Theme/Plugin'),
				),
				"content" => array(
					"label"       => "Content",
					"type"        => "Editor",
					"noscript"    => true,
					"description" => __("Content",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
			);
		
	}

	public function name() {
		return __("Service",'Pixelentity Theme/Plugin');
	}

	public function group() {
		return "service";
	}

	public function render() {
		// do nothing here since the rendering happens in the parent template
	}

	public function tooltip() {
		return __("Use this block to add a new service.",'Pixelentity Theme/Plugin');
	}

}

?>