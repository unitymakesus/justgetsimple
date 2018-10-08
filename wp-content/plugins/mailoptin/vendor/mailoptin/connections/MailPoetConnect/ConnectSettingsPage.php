<?php

namespace MailOptin\MailPoetConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);
    }

    public function connection_settings($arg)
    {
        if (AbstractMailPoetConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        if (class_exists('MailPoet\Config\Initializer')) {
            $settingsArg[] = array(
                'section_title' => __('MailPoet Connection', 'mailoptin') . " $status",
                'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
                'mailpoet_activate' => array(
                    'type' => 'checkbox',
                    'label' => __('Enable Connection', 'mailoptin'),
                    'description' => __('Check to activate or enable MailOptin connection with MailPoet.', 'mailoptin'),
                ),
            );
        } else {
            $settingsArg[] = array(
                'section_title' => __('MailPoet Connection', 'mailoptin') . " $status",
                'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
                'mailpoet_not_found' => array(
                    'type' => 'arbitrary',
                    'description' => sprintf(
                        __('%sThis connection is disabled because you currently do not have MailPoet installed and activated.%s', 'mailoptin'),
                        '<p style="text-align: center">',
                        '</p>'
                    ),
                ),
                'disable_submit_button' => true,
            );
        }

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