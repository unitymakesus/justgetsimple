<?php

namespace MailOptin\ActiveCampaignConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractActiveCampaignConnect extends AbstractConnect
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
     * Is ActiveCampaign successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['activecampaign_api_url']) && !empty($db_options['activecampaign_api_key']);
    }

    /**
     * Return instance of ActiveCampaign API class.
     *
     * @throws \Exception
     *
     * @return \ActiveCampaign
     */
    public function activecampaign_instance()
    {
        $api_url = $this->connections_settings->activecampaign_api_url();
        $api_key = $this->connections_settings->activecampaign_api_key();

        if (empty($api_url)) {
            throw new \Exception(__('ActiveCampaign API URL not found.', 'mailoptin'));
        }

        if (empty($api_key)) {
            throw new \Exception(__('ActiveCampaign API key not found.', 'mailoptin'));
        }

        return new \ActiveCampaign($api_url, $api_key);
    }
}