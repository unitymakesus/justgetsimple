<?php

namespace MailOptin\MailerliteConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);
    }

    public function connection_settings($arg)
    {
        if (AbstractMailerliteConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = array(
            'section_title' => __('MailerLite Connection', 'mailoptin') . " $status",
            'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
            'mailerlite_api_key' => array(
                'type' => 'text',
                'obfuscate_val' => true,
                'label' => __('Enter API Key', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sMailerLite account%s to get your API Key.', 'mailoptin'),
                    '<a target="_blank" href="https://app.mailerlite.com/integrations/api/">',
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