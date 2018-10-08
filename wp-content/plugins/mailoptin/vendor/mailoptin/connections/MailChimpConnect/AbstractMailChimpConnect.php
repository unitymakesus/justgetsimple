<?php

namespace MailOptin\MailChimpConnect;

use Mailchimp\http\MailchimpCurlHttpClient;
use Mailchimp\MailchimpCampaigns;
use Mailchimp\MailchimpLists;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractMailChimpConnect extends AbstractConnect
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
     * Is MailChimp successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return ! empty($db_options['mailchimp_api_key']);
    }

    /**
     * Return instance of MailChimp list class.
     *
     * @throws \Exception
     *
     * @return MailchimpLists
     */
    public function mc_list_instance()
    {
        $api_key = $this->connections_settings->mailchimp_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('MailChimp API key not found.', 'mailoptin'));
        }

        $instance = new MailchimpLists($api_key);
        $instance->setClient(new MailchimpCurlHttpClient(['timeout' => 10]));

        return $instance;
    }

    /**
     * Return instance of MailChimp campaign class.
     *
     * @throws \Exception
     *
     * @return MailchimpCampaigns
     */
    public function mc_campaign_instance()
    {
        $api_key = $this->connections_settings->mailchimp_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('MailChimp API key not found.', 'mailoptin'));
        }

        return new MailchimpCampaigns($api_key);
    }
}