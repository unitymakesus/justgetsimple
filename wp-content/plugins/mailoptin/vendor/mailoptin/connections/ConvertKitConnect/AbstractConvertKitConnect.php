<?php

namespace MailOptin\ConvertKitConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractConvertKitConnect extends AbstractConnect
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
     * Is ConvertKit successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['convertkit_api_key']);
    }

    /**
     * Return instance of convertkit API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function convertkit_instance()
    {
        $api_key = $this->connections_settings->convertkit_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('ConvertKit API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}