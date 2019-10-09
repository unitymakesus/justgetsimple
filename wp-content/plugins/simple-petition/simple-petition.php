<?php
/**
  * Plugin Name: Simple Petition
  * Description: Collect signatures!
  * Version: 1.0
  * Author: Unity Digital Agency
  *
  */

define( 'SP_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'SP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
// define( 'SP_ADMIN_URL', admin_url("options-general.php?page=simple-petition"));

require SP_PLUGIN_DIR . 'app/required-plugins.php';
require SP_PLUGIN_DIR . 'app/custom-post-type.php';
require SP_PLUGIN_DIR . 'app/shortcodes.php';

// add_action( 'admin_enqueue_scripts', 'sp_admin_scripts' );

add_action( 'admin_init', 'sp_check_required_plugins' );
// add_action( 'admin_init', 'sp_settings_init' );

// add_action( 'admin_menu', 'sp_options_page' );
