<?php

namespace MailOptin\ActiveCampaignConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);
    }

    public function connection_settings($arg)
    {
        if (AbstractActiveCampaignConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = array(
            'section_title' => __('ActiveCampaign Connection', 'mailoptin') . " $status",
            'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
            'activecampaign_api_url' => array(
                'type' => 'text',
                'label' => __('Enter API URL', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sActiveCampaign account%s to get your API URL at "Developer" account settings.', 'mailoptin'),
                    '<a target="_blank" href="https://www.activecampaign.com/login/">',
                    '</a>'
                ),
            ),
            'activecampaign_api_key' => array(
                'type' => 'text',
                'obfuscate_val' => true,
                'label' => __('Enter API Key', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sActiveCampaign account%s to get your "API key" at "Developer" account settings.', 'mailoptin'),
                    '<a target="_blank" href="https://www.activecampaign.com/login/">',
                    '</a>'
                ),
            )
        );

        return array_merge($arg, $settingsArg);
    }

    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}