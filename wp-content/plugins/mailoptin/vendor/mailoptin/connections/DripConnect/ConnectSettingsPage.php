<?php

namespace MailOptin\DripConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);
    }

    public function connection_settings($arg)
    {
        if (AbstractDripConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }
        $settingsArg[] = array(
            'section_title' => __('Drip Connection', 'mailoptin') . " $status",
            'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
            'drip_api_token' => array(
                'type' => 'text',
                'obfuscate_val' => true,
                'label' => __('Enter API Token', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sDrip account%s to get your api token.', 'mailoptin'),
                    '<a target="_blank" href="https://www.getdrip.com/user/edit">',
                    '</a>'
                ),
            ),
            'drip_account_id' => array(
                'type' => 'text',
                'label' => __('Enter Account ID', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sDrip account%s to get your "account id" at "General Info" settings.', 'mailoptin'),
                    '<a target="_blank" href="https://www.getdrip.com/signin">',
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