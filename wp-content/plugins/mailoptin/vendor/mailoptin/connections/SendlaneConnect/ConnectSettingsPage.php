<?php

namespace MailOptin\SendlaneConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);
    }

    public function connection_settings($arg)
    {
        if (AbstractSendlaneConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }
        $settingsArg[] = array(
            'section_title'     => __('Sendlane Connection', 'mailoptin') . " $status",
            'type'              => AbstractConnect::EMAIL_MARKETING_TYPE,
            'sendlane_api_key'  => array(
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Key', 'mailoptin'),
                'description'   => sprintf(
                    __('Log in to your %sSendlane account%s, go to "Account Settings" to get your API key.', 'mailoptin'),
                    '<a target="_blank" href="https://sendlane.com/users/login">',
                    '</a>'
                ),
            ),
            'sendlane_hash_key' => array(
                'type'        => 'text',
                'obfuscate_val' => true,
                'label'       => __('Enter Hash Key', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sSendlane account%s, go to "Account Settings" to get your hash key.', 'mailoptin'),
                    '<a target="_blank" href="https://sendlane.com/users/login">',
                    '</a>'
                ),
            ),
            'sendlane_domain' => array(
                'type'        => 'text',
                'label'       => __('Enter Sendlane Domain', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sSendlane account%s, go to "Account Settings" to get your sendlane domain.', 'mailoptin'),
                    '<a target="_blank" href="https://sendlane.com/users/login">',
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