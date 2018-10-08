<?php

namespace MailOptin\EmailOctopusConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractEmailOctopusConnect extends AbstractConnect
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
     * Is EmailOctopus successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['emailoctopus_api_key']);
    }

    /**
     * Return instance of emailoctopus API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function emailoctopus_instance()
    {
        $api_key = $this->connections_settings->emailoctopus_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('EmailOctopus API key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}