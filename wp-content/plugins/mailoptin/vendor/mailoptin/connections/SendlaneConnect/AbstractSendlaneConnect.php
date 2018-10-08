<?php

namespace MailOptin\SendlaneConnect;

use DrewM\Drip\Drip;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractSendlaneConnect extends AbstractConnect
{
    /** @var \MailOptin\Core\PluginSettings\Settings */
    protected $plugin_settings;

    /** @var \MailOptin\Core\PluginSettings\Connections */
    protected $connections_settings;

    public function __construct()
    {
        $this->plugin_settings      = Settings::instance();
        $this->connections_settings = Connections::instance();

        parent::__construct();
    }

    /**
     * Is Drip successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['sendlane_api_key']) && ! empty($db_options['sendlane_hash_key']) && ! empty($db_options['sendlane_domain']);
    }

    /**
     * Return instance of drip API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function sendlane_instance()
    {
        $sendlane_api_key  = $this->connections_settings->sendlane_api_key();
        $sendlane_hash_key = $this->connections_settings->sendlane_hash_key();
        $sendlane_domain = $this->connections_settings->sendlane_domain();

        if (empty($sendlane_api_key)) {
            throw new \Exception(__('Sendlane API Key not found.', 'mailoptin'));
        }

        if (empty($sendlane_hash_key)) {
            throw new \Exception(__('Sendlane Hash Key not found.', 'mailoptin'));
        }

        if (empty($sendlane_domain)) {
            throw new \Exception(__('Sendlane Domain not found.', 'mailoptin'));
        }

        return new APIClass($sendlane_api_key, $sendlane_hash_key,$sendlane_domain);
    }
}