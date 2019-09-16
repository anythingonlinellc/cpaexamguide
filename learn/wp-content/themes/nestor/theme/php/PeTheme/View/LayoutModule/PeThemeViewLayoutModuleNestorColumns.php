<?php

class PeThemeViewLayoutModuleNestorColumns extends PeThemeViewLayoutModuleNestorContainer {

	public static $translate = 
		array(
			  "1/1" => "col-sm-12",
			  "1/2" => "col-sm-6",
			  "1/3" => "col-sm-4",
			  "1/4" => "col-sm-3",
			  "1/6" => "col-sm-2",
			  "2/4" => "col-sm-6",
			  "2/3" => "col-sm-8",
			  "3/4" => "col-sm-9",
			  "5/6" => "col-sm-10",
			  "last" => ""
			  );

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Columns",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				"columns" => array(
					"label"       => __("Layout",'Pixelentity Theme/Plugin'),
					"description" => __("Select the columns layout",'Pixelentity Theme/Plugin'),
					"type"        => "Select",
					"groups"      => true,
					"options"     => apply_filters('pe_theme_shortcode_columns_options',PeGlobal::$config["columns"]),
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
			);
	}

	public function name() {
		return __("Columns",'Pixelentity Theme/Plugin');
	}

	public function create() {
		return "NestorText";
	}

	public function force() {
		return "NestorText";
	}

	public function allowed() {
		return "column";
	}

	public function group() {
		return "section";
	}
	
	public function type() {
		return __("Section",'Pixelentity Theme/Plugin');
	}


	public function template() {
		
		if (empty($this->conf->items) || !is_array($this->conf->items)) {
			return;
		}

		$translate = PeThemeViewLayoutModuleNestorColumns::$translate;

		if (empty($this->data->columns)) {
			$cols = count($this->conf->items);
		} else {
			$layout = explode(" ",strtr($this->data->columns,$translate));
			$cols = count($layout);
		}

		$style = '';

		if ( ! empty( $this->data->bgcolor ) ) $style .= 'background-color: ' . $this->data->bgcolor . ';';
		if ( ! empty( $this->data->bgimage ) ) $style .= 'background-image: url(\'' . $this->data->bgimage . '\');';
		if ( ! empty( $style ) ) $style = 'style="' . $style . '"';
		$typography = ( 'light' === $this->data->typography ) ? 'dark' : '';

		$idx = 0;
		$last = count($this->conf->items)-1;

		$open = '<div class="container"><div class="row">';
		$close = '</div></div>';

		printf('<section class="padding-top-' . $this->data->padding_top . ' padding-bottom-' . $this->data->padding_bottom . ' ' . $typography . ' section-type-column" %s id="section-%s">', $style, empty( $this->data->name ) ? $this->conf->bid : $this->data->name );
		foreach ($this->conf->items as $item) {
			if (($idx % $cols) === 0) echo $open;
			printf('<div class="%s columns">',empty($layout[$idx % $cols]) ? '' : $layout[$idx % $cols],$idx,$cols);
			$this->outputModule($item);
			echo "</div>";
			if ($idx === $last || ($idx % $cols) === ($cols-1)) echo $close;
			$idx++;
		}
		print '</section>';

	}

	public function tooltip() {
		return __("Use this block to add columns of content to your layout.",'Pixelentity Theme/Plugin');
	}

}

?>