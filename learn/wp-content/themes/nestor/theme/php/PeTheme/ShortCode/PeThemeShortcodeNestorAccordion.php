<?php

class PeThemeShortcodeNestorAccordion extends PeThemeShortcodeBS_Tabs {

	public function __construct($master) {
		parent::__construct($master);
		$this->trigger = "item";
		$this->group = __("LAYOUT",'Pixelentity Theme/Plugin');
		$this->name = __("Accordion",'Pixelentity Theme/Plugin');
		$this->description = __("Accordion",'Pixelentity Theme/Plugin');
		$this->fields = array(
			"size" => array(
				"label"       => __("Number of items",'Pixelentity Theme/Plugin'),
				"type"        => "Select",
				"single"      => true,
				"description" => __("Select the number of items in the accordion.",'Pixelentity Theme/Plugin'),
				"options"     => range(1,10),
			),
		);

	}

	public function parentTrigger() {
		add_shortcode("accordion",array(&$this,"container"));

		// add block level cleaning
		peTheme()->shortcode->blockLevel[] = "item";
		peTheme()->shortcode->blockLevel[] = "accordion";
	}

	protected function script() {
		$html = <<<EOT
<script type="text/javascript">
jQuery.pixelentity.shortcodes.$this->trigger = jQuery("#{$this->trigger}_size_").peShortcodeProperties({parent:"accordion",tag:"{$this->trigger}",title:"Title"});
</script>
EOT;
		echo $html;
	}

	public function container($atts,$content=null,$code="") {
		$this->instances++;
		$content = $this->parseContent($content);
		
		if (!is_array($this->items) || count($this->items) == 0) {
			return "";
		}

		$count = 1;
		$commonID = "accordion".$this->instances;
		$html = "";

		if (peTheme()->template->exists("shortcode","accordion")) {
			$items = $this->items;
			$conf = (object) compact("atts","content","items");
			ob_start();
			peTheme()->template->get_part($conf,"shortcode","accordion");
			$html = ob_get_clean();
		} else {

			while ($item = array_shift($this->items)) {
				$id = "{$commonID}_{$count}";
				$firstTab = ( 1 === $count ) ? 'in' : '';
				$html .= '<div class="panel panel-primary">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#' . $commonID . '" href="#' . $id . '">' . $item->title . '</a>
					</div>
					<div id="' . $id . '" class="panel-collapse collapse ' . $firstTab . '">
						<div class="panel-body">
							' . $item->body . '
						</div>
					</div>
				</div>';
                $count++;
			}

		}

		$html = sprintf('<div id="%s" class="accordion-shortcode panel-group">%s</div>',$commonID,$html);
		return apply_filters("pe_theme_shortcode_accordion",$html,$atts,$content,$commonID);
	}


}

?>
