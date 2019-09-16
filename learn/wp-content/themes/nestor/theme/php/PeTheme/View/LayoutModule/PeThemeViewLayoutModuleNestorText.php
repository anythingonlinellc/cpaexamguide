<?php

class PeThemeViewLayoutModuleNestorText extends PeThemeViewLayoutModuleText {

	public function fields() {
		$fields = parent::fields();
		unset($fields["layout"]);
		return $fields;
	}

	public function group() {
		return "column";
	}


	public function render() {
		$data = (object) shortcode_atts(
										array(
											  'content' => ''
											  ),
										$this->conf->data
										);

		echo do_shortcode(apply_filters("the_content",$data->content));
	}

}

?>
