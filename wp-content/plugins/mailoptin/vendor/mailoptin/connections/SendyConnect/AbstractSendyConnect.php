<?php

namespace MailOptin\SendyConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractSendyConnect extends AbstractConnect
{
    /** @var \MailOptin\Core\PluginSettings\Settings */
    protected $plugin_settings;

    /** @var \MailOptin\Core\PluginSettings\Connections */
    protected $connections_settings;

    public function __construct()
    {
        $this->plugin_settings = Settings::instance();
        $this->connections_settings = Connections::instance();

        parent::__construct();
    }

    /**
     * Is successfully connected?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['sendy_api_key']) &&
            !empty($db_options['sendy_installation_url']);
    }

    /**
     * Return basic sendy API config settings
     */
    public function api_config()
    {
        $reply_to = $this->plugin_settings->reply_to();

        $api_key = $this->connections_settings->sendy_api_key();
        $installation_url = $this->connections_settings->sendy_installation_url();

        return array(
            'api_key' => $api_key,
            'installation_url' => $installation_url,
            'reply_to' => $reply_to
        );
    }
}