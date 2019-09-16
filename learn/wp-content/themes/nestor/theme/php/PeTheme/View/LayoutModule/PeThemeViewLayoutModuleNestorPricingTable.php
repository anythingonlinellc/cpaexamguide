<?php

class PeThemeViewLayoutModuleNestorPricingTable extends PeThemeViewLayoutModuleText {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Pricing Table",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"featured" => array(
					"label"       => __("Featured",'Pixelentity Theme/Plugin'),
					"type"        => "Select",
					"options"     => array(
						__("Normal",'Pixelentity Theme/Plugin')   => '',
						__("Featured",'Pixelentity Theme/Plugin') => 'btn-primary',
						__("Premium",'Pixelentity Theme/Plugin')  => 'btn-orange',
					),
					"description" => __("Specify whether this pricing table should be featured.",'Pixelentity Theme/Plugin'),
					"default"     => '',
				),
				"plan" => array(
					"label"       => __("Plan",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Pricing plan title.",'Pixelentity Theme/Plugin'),
					"default"     => __("Lite",'Pixelentity Theme/Plugin'),
				),
				"price" => array(
					"label"       => __("Price",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Plan price.",'Pixelentity Theme/Plugin'),
					"default"     => '9.99',
				),
				"unit" => array(
					"label"       => __("Unit",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Price unit.",'Pixelentity Theme/Plugin'),
					"default"     => __("$",'Pixelentity Theme/Plugin'),
				),
				"features" => array(
					"label"        => __("Features",'Pixelentity Theme/Plugin'),
					"type"         => "Items",
					"description"  => __("Add one or more feature describing this plan.",'Pixelentity Theme/Plugin'),
					"button_label" => __("Add Feature",'Pixelentity Theme/Plugin'),
					"sortable"     => true,
					"auto"         => __("1 user",'Pixelentity Theme/Plugin'),
					"unique"       => false,
					"editable"     => false,
					"legend"       => false,
					"fields"       => array(
						array(
							"label"   => "Text",
							"name"    => "text",
							"type"    => "text",
							"width"   => 500, 
							"default" => __("1 user",'Pixelentity Theme/Plugin'),
						),
					),
				),
				"button_text" => array(
					"label"       => __("Button text",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Text of the button displayed at the bottom of the table.",'Pixelentity Theme/Plugin'),
					"default"     => __("Buy now!",'Pixelentity Theme/Plugin'),
				),
				"button_link" => array(
					"label"       => __("Button link",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Link of the button displayed at the bottom of the table.",'Pixelentity Theme/Plugin'),
					"default"     => '#',
				),
			);
		
	}

	public function name() {
		return __("Pricing Table",'Pixelentity Theme/Plugin');
	}

	public function group() {
		return "pricingtable";
	}

	public function render() {
		// do nothing here since the rendering happens in the parent template
	}

	public function tooltip() {
		return __("Use this block to add a new pricing table.",'Pixelentity Theme/Plugin');
	}

}

?>