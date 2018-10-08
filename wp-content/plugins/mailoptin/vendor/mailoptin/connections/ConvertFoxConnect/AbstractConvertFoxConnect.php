<?php

namespace MailOptin\ConvertFoxConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractConvertFoxConnect extends AbstractConnect
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
     * Is ConvertFox successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['convertfox_api_key']);
    }

    /**
     * Return instance of convertfox API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function convertfox_instance()
    {
        $api_key = $this->connections_settings->convertfox_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('ConvertFox API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}