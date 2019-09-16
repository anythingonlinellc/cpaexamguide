<?php

class PeThemeViewLayoutModuleNestorPartners extends PeThemeViewLayoutModuleContainer {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Partners",'Pixelentity Theme/Plugin')
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
			);
	}

	public function name() {
		return __("Partners",'Pixelentity Theme/Plugin');
	}

	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}

	public function create() {
		return "NestorPartner";
	}

	public function force() {
		return "NestorPartner";
	}
	
	public function allowed() {
		return "partner";
	}

	public function group() {
		return "section";
	}

	public function setTemplateData() {
		// override setTemplateData so to also pass the item array to the template file
		// this way the markup for the child blocks can also be generated in the container/parent template
		// We're not interested in builder related settings so we rebuild the array
		// to only include the data we going to use.
		
		$items = array();
		if (!empty($this->conf->items)) {
			foreach($this->conf->items as $item) {
				$item = (object) shortcode_atts(
												array(
													  'link'  => '',
													  'image' => '',
													  ),
												$item["data"]
												);
				
				$items[] = $item;
			}
		}

		peTheme()->template->data($this->data,$items,$this->conf->bid);
	}

	public function template() {
		peTheme()->get_template_part("viewmodule",empty($this->data->layout) ? "partners" : $this->data->layout);
	}

	public function tooltip() {
		return __("Use this block to add a Partners block.",'Pixelentity Theme/Plugin');
	}

}

?>