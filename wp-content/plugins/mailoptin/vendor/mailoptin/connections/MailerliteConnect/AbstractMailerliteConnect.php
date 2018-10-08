<?php

namespace MailOptin\MailerliteConnect;

use MailerLiteApi\MailerLite;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractMailerliteConnect extends AbstractConnect
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
     * Is MailerLite successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['mailerlite_api_key']);
    }

    /**
     * Return instance of mailerlite API class.
     *
     * @throws \Exception
     *
     * @return MailerLite
     */
    public function mailerlite_instance()
    {
        $api_key = $this->connections_settings->mailerlite_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('MailerLite API key not found.', 'mailoptin'));
        }

        return new MailerLite($api_key);
    }
}