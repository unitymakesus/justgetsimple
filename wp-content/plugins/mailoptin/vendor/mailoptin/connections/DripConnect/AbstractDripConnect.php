<?php

namespace MailOptin\DripConnect;

use DrewM\Drip\Drip;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractDripConnect extends AbstractConnect
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
     * Is Drip successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['drip_api_token']) && !empty($db_options['drip_account_id']);
    }

    /**
     * Return instance of drip API class.
     *
     * @throws \Exception
     *
     * @return Drip
     */
    public function drip_instance()
    {
        $api_token = $this->connections_settings->drip_api_token();
        $account_id = $this->connections_settings->drip_account_id();

        if (empty($api_token)) {
            throw new \Exception(__('Drip API Token not found.', 'mailoptin'));
        }

        if (empty($account_id)) {
            throw new \Exception(__('Drip Account ID not found.', 'mailoptin'));
        }

        return new Drip($api_token, $account_id);
    }
}