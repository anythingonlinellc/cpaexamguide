<?php

class PeThemeViewLayoutModuleNestorServices extends PeThemeViewLayoutModuleContainer {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Services",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"title" => array(
					"label"       => __("Title",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Section title.",'Pixelentity Theme/Plugin'),
					"default"     => __("Our services",'Pixelentity Theme/Plugin')
				),
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
				"content" => array(
					"label"       => "Content",
					"type"        => "Editor",
					"noscript"    => true,
					"description" => __("Content",'Pixelentity Theme/Plugin'),
					"default"     => ""
				),
				"button_url" => array(
					"label"       => __("Button url",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Enter the destination url of the button",'Pixelentity Theme/Plugin'),
				),
				"button_text" => array(
					"label"       => __("Button text",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Enter the button text here",'Pixelentity Theme/Plugin'),
				),
				"button_target" => array(
					"label"       => __("Open link in new window",'Pixelentity Theme/Plugin'),
					"type"        => "Select",
					"description" => __("Should the url be opened in new window or not.",'Pixelentity Theme/Plugin'),
					"options"     => array(
						__("Yes",'Pixelentity Theme/Plugin') => "yes",
						__("No",'Pixelentity Theme/Plugin')  => "no",
					),
					"default"     => "no",
				),
				"image" => array(
					"label"       => __("Image",'Pixelentity Theme/Plugin'),
					"type"        => "Upload",
					"description" => __("Section image.",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
			);
	}

	public function name() {
		return __("Services",'Pixelentity Theme/Plugin');
	}

	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}

	public function create() {
		return "NestorService";
	}

	public function force() {
		return "NestorService";
	}
	
	public function allowed() {
		return "service";
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
													  'icon' => '',
													  'title' => '',
													  'content' => ''
													  ),
												$item["data"]
												);
				
				$item->content = empty($item->content) ? "" : do_shortcode(apply_filters("the_content",$item->content));
				$items[] = $item;
			}
		}

		// we also render (parent) shortcodes here to keep template file clean;
		$this->data->content = empty($this->data->content) ? "" : do_shortcode(apply_filters("the_content",$this->data->content));

		peTheme()->template->data($this->data,$items,$this->conf->bid);
	}

	public function template() {
		peTheme()->get_template_part("viewmodule",empty($this->data->layout) ? "services" : $this->data->layout);
	}

	public function tooltip() {
		return __("Use this block to add a Services section.",'Pixelentity Theme/Plugin');
	}

}

?>