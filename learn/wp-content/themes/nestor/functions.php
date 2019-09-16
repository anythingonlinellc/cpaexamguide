<?php

define("PE_THEME_NAME",'Nestor');

// bootstrap the framework
define("PE_THEME_PATH",dirname( __FILE__));
require("framework/php/boot.php");

function pe_theme_hide_admin_bar() {

	// hide the admin bar (known to cause issues with the theme when enabled)
	show_admin_bar(false);

}

// add_action( 'init', 'pe_theme_hide_admin_bar' );