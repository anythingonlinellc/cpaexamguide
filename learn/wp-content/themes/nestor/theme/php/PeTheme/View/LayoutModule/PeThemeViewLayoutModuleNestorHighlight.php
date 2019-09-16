<?php

class PeThemeViewLayoutModuleNestorHighlight extends PeThemeViewLayoutModule {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Highlight",'Pixelentity Theme/Plugin')
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
				"subtitle" => array(
					"label"       => __("Subtitle",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Highlight subtitle.",'Pixelentity Theme/Plugin'),
					"default"     => '',
				),
				"height" => array(
					"label"       => __("Section height",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Height of this section.",'Pixelentity Theme/Plugin'),
					"default"     => '500',
				),
				"alignment" => array(
					"label"       => __("Text alignment",'Pixelentity Theme/Plugin'),
					"type"        => "RadioUI",
					"description" => __("Text alignment of this section.",'Pixelentity Theme/Plugin'),
					"options"     => array(
						__( 'Left' ,'Pixelentity Theme/Plugin')   => 'left',
						__( 'Center' ,'Pixelentity Theme/Plugin') => 'center',
						__( 'Right' ,'Pixelentity Theme/Plugin')  => 'right',
					),
					"default"     => 'left',
				),
			);
	}

	public function name() {
		return __("Highlight",'Pixelentity Theme/Plugin');
	}

	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}

	public function templateName() {
		return "highlight";
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
		return __("Add a Highlight section.",'Pixelentity Theme/Plugin');
	}

}

?>