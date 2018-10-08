<?php

namespace MailOptin\GetResponseConnect;

use MailOptin\Core\Connections\AbstractConnect;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);

        add_action('mailoptin_after_connections_settings_page', [$this, 'toggle_js_Script']);
    }

    public function toggle_js_Script()
    {
        ?>
        <script type="text/javascript">
            jQuery(function ($) {
                function is_checked() {
                    return $('#getresponse_is_360').is(':checked');
                }

                $('#getresponse360_registered_domain_row').toggle(is_checked());
                $('#getresponse360_country_row').toggle(is_checked());

                $('#getresponse_is_360').change(function () {
                    $('#getresponse360_registered_domain_row').toggle(this.checked);
                    $('#getresponse360_country_row').toggle(this.checked);
                })
            });
        </script>
        <?php
    }

    public function connection_settings($arg)
    {
        if (AbstractGetResponseConnect::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = array(
            'section_title' => __('GetResponse Connection', 'mailoptin') . " $status",
            'type' => AbstractConnect::EMAIL_MARKETING_TYPE,
            'getresponse_api_key' => array(
                'type' => 'text',
                'obfuscate_val' => true,
                'label' => __('Enter API Key', 'mailoptin'),
                'description' => sprintf(
                    __('Log in to your %sGetResponse account%s to get your API Key.', 'mailoptin'),
                    '<a target="_blank" href="https://app.getresponse.com/manage_api.html">',
                    '</a>'
                ),
            ),
            'getresponse_is_360' => array(
                'type' => 'checkbox',
                'label' => __('GetResponse360 Account', 'mailoptin'),
                'description' => __('Check this only if you are a GetResponse360 customer.', 'mailoptin'),
            ),
            'getresponse360_registered_domain' => array(
                'type' => 'text',
                'label' => __('GetResponse360 Registered Domain', 'mailoptin'),
                'description' => __('Enter your GetResponse360 account registered domain.', 'mailoptin')
            ),
            'getresponse360_country' => array(
                'type' => 'select',
                'label' => __('GetResponse360 Country', 'mailoptin'),
                'options' => [
                    'none' => __('Select...', 'mailoptin'),
                    'poland' => __('Poland', 'mailoptin'),
                    'others' => __('Others', 'mailoptin'),
                ],
                'description' => __('Select country your GetResponse360 account is associated with.', 'mailoptin'),
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