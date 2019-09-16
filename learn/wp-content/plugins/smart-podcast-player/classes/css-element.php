<?php

class SPP_CSS_Element {

	protected $_rules = array();
	protected $_selector;

	public function __construct( $selector, $rules = array() ) {
		$this->_selector = $selector;
		$this->_rules = $rules;
	}

	/**
	 * Add a CSS rule to the element
	 * 
	 * @param string $key   Name of the rule (such as text-align or position)
	 * @param string $value Any valid css value for said rule (no checking or pre-processing happens here, it's just added)
	 */
	public function add_rule( $key, $value ) {
		$this->_rules[ $key ] = $value;
	}

	/**
	 * Add an array of rules, following the 'key' => 'value' model of SPP_CSS_Element::add_rule
	 * @param array $rules
	 */
	public function add_rules( array $rules ) {
		foreach( $rules as $attr => $val ) {
			$this->add_rule( $attr, $val );
		}
	}

	/**
	 * Useful when you're using a cloned object and you need to update the selector
	 * Wrapper of str_replace for the selector
	 * 
	 * @param  string $old Portion of the selector you want to replace.
	 * @param  string $new What you want to replace $old with
	 * @return void
	 */
	public function filter_selector( $old, $new ) {
		$this->_selector = str_replace($old, $new, $this->_selector );
	}

	public function get_selector() {
		return $this->_selector;
	}

	public function get_rules() {
		return $this->_rules;
	}

	/**
	 * Output the CSS rules for the selector
	 * @return string CSS
	 */
	public function render() {
		
		if( empty( $this->_rules ) ) 
			return;

		$output = "\n\t" . $this->_selector . "{";

		foreach( $this->_rules as $attr => $value ) {
			$output .= "\n\t\t" . $attr . ': ' . $value . ";";
		}

		$output .= "\n\t}\n";

		return $output;

	}

}
