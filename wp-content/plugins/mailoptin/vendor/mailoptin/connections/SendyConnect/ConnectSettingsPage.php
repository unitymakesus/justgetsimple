<?php

namespace MailOptin\SendyConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));
        add_filter('mailoptin_newsletters_settings_page', array($this, 'newsletter_settings'));
        add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'campaign_customizer_settings'));
        add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'campaign_customizer_controls'), 10, 4);

        add_filter('mailoptin_email_campaign_tab_toggle_general_config', function($val) {
            $val[] = 'SendyConnect_query_string';

            return $val;
        });
    }

    public function campaign_customizer_settings($settings)
    {
        $settings['SendyConnect_query_string'] = array(
            'default' => apply_filters('mailoptin_customizer_email_campaign_SendyConnect_query_string', ''),
            'type' => 'option',
            'transport' => 'postMessage',
        );

        return $settings;
    }

    public function campaign_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        // always prefix with the name of the connect/connection service.
        $controls['SendyConnect_query_string'] = apply_filters('mailoptin_customizer_settings_campaign_SendyConnect_query_string_args',
            array(
                'type' => 'text',
                'label' => __('Sendy Campaign Query String', 'mailoptin'),
                'section' => $customizerClassInstance->campaign_settings_section_id,
                'settings' => $option_prefix . '[SendyConnect_query_string]',
                'description' => __("Query string to append to all links in newsletter sent to Sendy email service. E.g Google Analytics tracking. Don't include '?' in your query string.", 'mailoptin'),
                'priority' => 199
            )
        );

        return $controls;
    }

    public function connection_settings($arg)
    {
        if (AbstractSendyConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = array(
            'section_title' => __('Sendy Connection', 'mailoptin') . " $status",
            'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
            'sendy_api_key' => array(
                'type' => 'text',
                'obfuscate_val' => true,
                'label' => __('API Key', 'mailoptin'),
                'description' => sprintf(__('Enter your Sendy API key.', 'mailoptin')),
            ),
            'sendy_installation_url' => array(
                'type' => 'text',
                'label' => __('Installation URL', 'mailoptin'),
                'description' => __('Enter your Sendy installation URL.', 'mailoptin'),
            ),
            'sendy_email_list' => array(
                'type' => 'repeatable',
                'label' => __('Email List', 'mailoptin'),
                'fields' => array(
                    array(
                        'type' => 'text',
                        'name' => 'list_name',
                        'label' => __('List Name', 'mailoptin'),
                        'placeholder' => __('List Name', 'mailoptin'),
                        'class' => 'all-options',

                    ),
                    array(
                        'type' => 'text',
                        'name' => 'list_id',
                        'label' => __('List ID', 'mailoptin'),
                        'placeholder' => __('List ID', 'mailoptin'),
                        'class' => 'all-options',
                    ),
                ),
                'description' => __(
                    'Enter the names and IDs of your Sendy email list',
                    'mailoptin'
                )
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