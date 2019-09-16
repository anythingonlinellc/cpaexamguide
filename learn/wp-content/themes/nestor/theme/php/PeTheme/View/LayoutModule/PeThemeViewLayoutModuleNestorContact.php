<?php

class PeThemeViewLayoutModuleNEstorContact extends PeThemeViewLayoutModule {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Contact",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		
		$defaultInfo = '<h2>Contact us.</h2><p>Want to make your website awesome? Just get in touch we don\'t bite.</p>';

		$contactMbox = array(
			"type"     => "",
			"title"    => __("Contact Options",'Pixelentity Theme/Plugin'),
			"priority" => "core",
			"where"    => array(
				"page" => "page_contact",
			),
			"content" => array(
				"name" => array(
					"label"       => __("Link Name",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Used when linking to the section in a page (eg, from the menu).",'Pixelentity Theme/Plugin'),
					"default"     => "",
				),
				"title" => array(
					"label"       => __("Title",'Pixelentity Theme/Plugin'),
					"type"        => "Text",
					"description" => __("Feature title.",'Pixelentity Theme/Plugin'),
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
				"msgOK" => array(
					"label"       => __("Mail Sent Message",'Pixelentity Theme/Plugin'),
					"type"        => "TextArea",
					"description" => __("Message shown when form message has been sent without errors",'Pixelentity Theme/Plugin'),
					"default"     => '<strong>Yay!</strong> Message sent.',
				),
				"msgKO" => array(
					"label"       => __("Form Error Message",'Pixelentity Theme/Plugin'),
					"type"        => "TextArea",
					"description" => __("Message shown when form message encountered errors",'Pixelentity Theme/Plugin'),
					"default"     => '<strong>Error!</strong> Please validate your fields.',
				),
			),
		);

		$gmap = PeGlobal::$const->gmap->metabox;

		$contactMbox["content"] = array_merge( $contactMbox["content"], $gmap["content"] );

		$contactMbox["content"] = array_merge( $contactMbox["content"], array(
			"pin_title" => array(
				"label"       => __("Pin title",'Pixelentity Theme/Plugin'),
				"type"        => "Text",
				"description" => __("Title of the text displayed when map pin is clicked.",'Pixelentity Theme/Plugin'),
				"default"     => '',
			),
			"pin_description" => array(
				"label"       => __("Pin description",'Pixelentity Theme/Plugin'),
				"type"        => "TextArea",
				"description" => __("Text displayed when map pin is clicked.",'Pixelentity Theme/Plugin'),
				"default"     => '',
			),
		) );

		$fields = $contactMbox["content"];
		/*
		$fields["lightbox"] =
			array(
				  "label"=>__("Use Lightbox",'Pixelentity Theme/Plugin'),
				  "type"=>"RadioUI",
				  "description" => __("Enable/Disable lightbox usage on whole portfolio.",'Pixelentity Theme/Plugin'),
				  "options" => PeGlobal::$const->data->yesno,
				  "default"=>"no"
				  );
		*/
		return $fields;
	}

	public function name() {
		return __("Contact",'Pixelentity Theme/Plugin');
	}

	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}

	public function templateName() {
		return "contact";
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
		return __("Add a Contact section.",'Pixelentity Theme/Plugin');
	}

}

?>