<?php

/*
Plugin Name: MailOptin - Lite
Plugin URI: https://mailoptin.io
Description: Best Lead Generation, Email Automation & Newsletter WordPress Plugin.
Version: 1.2.8.2
Author: MailOptin Team
Contributors: collizo4sky
Author URI: https://mailoptin.io
Text Domain: mailoptin
Domain Path: /languages
License: GPL2
*/

require 'vendor/autoload.php';

define('MAILOPTIN_SYSTEM_FILE_PATH', __FILE__);
define('MAILOPTIN_VERSION_NUMBER', '1.2.8.2', true);

add_action('plugins_loaded', 'mo_mailoptin_load_plugin_textdomain', 0);
function mo_mailoptin_load_plugin_textdomain()
{
    load_plugin_textdomain('mailoptin', false, plugin_basename(dirname(MAILOPTIN_SYSTEM_FILE_PATH)) . '/languages');
}

MailOptin\Core\Core::init();
MailOptin\Connections\Init::init();