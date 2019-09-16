<?php

class PeThemeViewLayoutModuleNestorStatistic extends PeThemeViewLayoutModuleText {

	public function messages() {
		return
			array(
				  "title" => "",
				  "type" => __("Statistic",'Pixelentity Theme/Plugin')
				  );
	}

	public function fields() {
		return
			array(
				'number' => array(
					"label"       => __( 'Number' ,'Pixelentity Theme/Plugin'),
					"type"        => "text",
					"description" => __( 'A number representing your statistic.' ,'Pixelentity Theme/Plugin'),
					"default"     => __( '120' ,'Pixelentity Theme/Plugin'),
				),
				'detail' => array(
					"label"       => __( 'Text' ,'Pixelentity Theme/Plugin'),
					"type"        => "text",
					"description" => __( 'A text describing this statistic.' ,'Pixelentity Theme/Plugin'),
					"default"     => __( 'Employees' ,'Pixelentity Theme/Plugin'),
				),
				'color' => array(
					"label"       => __( 'Color' ,'Pixelentity Theme/Plugin'),
					"type"        => "Color",
					"description" => __( 'Color of the number.' ,'Pixelentity Theme/Plugin'),
					"default"     => '#2b2b2b',
				),
			);
		
	}

	public function name() {
		return __("Statistic",'Pixelentity Theme/Plugin');
	}

	public function group() {
		return "statistic";
	}

	public function render() {
		// do nothing here since the rendering happens in the parent template
	}

	public function tooltip() {
		return __("Use this block to add a new statistic.",'Pixelentity Theme/Plugin');
	}

}

?>