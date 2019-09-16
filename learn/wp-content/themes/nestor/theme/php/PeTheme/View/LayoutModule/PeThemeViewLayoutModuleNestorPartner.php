<?php

class PeThemeViewLayoutModuleNestorPartner extends PeThemeViewLayoutModuleText {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Partner",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"image" => array(
					"label"       => __("Image",'Pixelentity Theme/Plugin'),
					"type"        => "Upload",
					"description" => __("Team Member Image.",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
				"link" => array(
					"label"       => __("Link",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Optional link for the partner logo.",'Pixelentity Theme/Plugin'),
					"default"     => '',
				),
			);
		
	}

	public function name() {
		return __("Partner",'Pixelentity Theme/Plugin');
	}

	public function group() {
		return "partner";
	}

	public function render() {
		// do nothing here since the rendering happens in the parent template
	}

	public function tooltip() {
		return __("Use this block to add a new partner.",'Pixelentity Theme/Plugin');
	}

}

?>