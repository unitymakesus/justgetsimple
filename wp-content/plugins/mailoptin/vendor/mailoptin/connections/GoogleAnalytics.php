<?php

namespace MailOptin\Connections;

// Exit if accessed directly
use MailOptin\Core\Admin\SettingsPage\AbstractSettingsPage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\OptinForms\AbstractOptinForm;
use MailOptin\Libsodium\LibsodiumSettingsPage;

if (!defined('ABSPATH')) {
    exit;
}

class GoogleAnalytics extends AbstractSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', [$this, 'ga_settings']);
    }

    public function ga_settings($arg)
    {
        if (!defined('MAILOPTIN_AGENCY_PLUGIN_TYPE')) {
            ?>
            <style type="text/css">
                .mailoptin-settings-wrap h2.nav-tab-wrapper a[href="#activate_ga"] {
                    background-color: #2980b9;
                    color: #fff;
                }

                .mailoptin-settings-wrap h2.nav-tab-wrapper a.nav-tab-active {
                    color: #2e4453;
                }
            </style>
            <?php

            $url = 'https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=googleanalytics';

            if (class_exists('MailOptin\Libsodium\LibsodiumSettingsPage')) {
                $license_key = LibsodiumSettingsPage::license_key();
                if (!empty($license_key)) {
                    $url = sprintf('https://my.mailoptin.io/?mo_plan_upgrade=%s&license_key=%s', 'agency', $license_key);
                }
            }

            $settingsArg[] = apply_filters('mailoptin_google_analytics_settings_page', [
                'section_title' => __('Google Analytics', 'mailoptin'),
                'type' => AbstractConnect::ANALYTICS_TYPE,
                'activate_ga' => [
                    'type' => 'arbitrary',
                    'data' => sprintf(
                        '<p style="text-align:center;font-size: 15px;" class="description">%s</p><div class="moBtncontainer"><a target="_blank" href="%s" style="padding:0;margin: 0 auto;" class="mobutton mobtnPush mobtnGreen">%s</a></div>',
                        __('Google Analytics integration is available only to Agency license holders.', 'mailoptin'),
                        $url,
                        __('Upgrade to Agency Now!', 'mailoptin')
                    )
                ],
                'disable_submit_button' => true
            ]);

            return array_merge($arg, $settingsArg);
        }

        return $arg;
    }

    /**
     * @return GoogleAnalytics
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}