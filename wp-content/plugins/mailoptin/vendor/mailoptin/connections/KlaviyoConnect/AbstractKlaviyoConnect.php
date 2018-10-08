<?php

namespace MailOptin\KlaviyoConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractKlaviyoConnect extends AbstractConnect
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
     * Is Klaviyo successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['klaviyo_api_key']);
    }

    /**
     * Return instance of klaviyo API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function klaviyo_instance()
    {
        $api_key = $this->connections_settings->klaviyo_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('Klaviyo API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}