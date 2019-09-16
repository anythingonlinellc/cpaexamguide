<?php

class PeThemeViewLayoutModuleNestorIcons extends PeThemeViewLayoutModule {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Icons",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"name" => array(
					"label"       => __("Link Name",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Used when linking to the section in a page (eg, from the menu).",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
				"title" => array(
					"label"       => __("Title",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Highlight title.",'Pixelentity Theme/Plugin'),
					"default"     => '',
				),
				"bgcolor" => array(
					"label"       => __("Background color",'Pixelentity Theme/Plugin'),
					"type"        => "Color",
					"description" => __("Background color of the section.",'Pixelentity Theme/Plugin'),
					"default"     => "#fff",
				),
				"bgimage" => array(
					"label"       => __("Background image",'Pixelentity Theme/Plugin'),
					"type"        => "Upload",
					"description" => __("Background image of the section.",'Pixelentity Theme/Plugin'),
					"default"     => '',
				),
				"typography" => array(
					"label"       => __("Typography color",'Pixelentity Theme/Plugin'),
					"type"        => "RadioUI",
					"description" => __("Choose between light and dark type. You will want to adjust this based on your background and overlay.",'Pixelentity Theme/Plugin'),
					"options"     => array(
						__( 'Dark' ,'Pixelentity Theme/Plugin')   => 'dark',
						__( 'Light' ,'Pixelentity Theme/Plugin')  => 'light',
					),
					"default"     => 'dark',
				),
				"padding_top" => array(
					"label"       => __("Section top padding",'Pixelentity Theme/Plugin'),
					"type"        => "RadioUI",
					"description" => __("Specify what form of top padding should the section use.",'Pixelentity Theme/Plugin'),
					"options"     => array(
						__( 'Normal' ,'Pixelentity Theme/Plugin') => 'normal',
						__( 'Large' ,'Pixelentity Theme/Plugin')  => 'large',
						__( 'None' ,'Pixelentity Theme/Plugin')   => 'none',
					),
					"default"     => 'normal',
				),
				"padding_bottom" => array(
					"label"       => __("Section bottom padding",'Pixelentity Theme/Plugin'),
					"type"        => "RadioUI",
					"description" => __("Specify what form of bottom padding should the section use.",'Pixelentity Theme/Plugin'),
					"options"     => array(
						__( 'Normal' ,'Pixelentity Theme/Plugin') => 'normal',
						__( 'Large' ,'Pixelentity Theme/Plugin')  => 'large',
						__( 'None' ,'Pixelentity Theme/Plugin')   => 'none',
					),
					"default"     => 'normal',
				),
				"icons" => array(
					"label"        => __("Icons",'Pixelentity Theme/Plugin'),
					"type"         => "Items",
					"description"  => __("Add one or more icon.",'Pixelentity Theme/Plugin'),
					"button_label" => __("Add Icon",'Pixelentity Theme/Plugin'),
					"sortable"     => true,
					"auto"         => __("ion-social-facebook-outline",'Pixelentity Theme/Plugin'),
					"unique"       => false,
					"editable"     => false,
					"legend"       => false,
					"fields"       => array(
						array(
							"name"    => "icon",
							"type"    => "icon",
							"width"   => 185,
							"default" => __("ion-social-facebook-outline",'Pixelentity Theme/Plugin'),
						),
						array(
							"name"    => "url",
							"type"    => "text",
							"width"   => 300, 
							"default" => "#",
						),
					),
				),
			);
	}

	public function name() {
		return __("Icons",'Pixelentity Theme/Plugin');
	}

	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}

	public function templateName() {
		return "icons";
	}

	public function group() {
		return "section";
	}

	public function setTemplateData() {
		$t =& peTheme();
		peTheme()->template->data($this->data,$this->conf->bid);
	}

	public function template() {
		peTheme()->get_template_part("viewmodule",$this->templateName());
	}

	public function tooltip() {
		return __("Add an Icons section.",'Pixelentity Theme/Plugin');
	}

}

?>