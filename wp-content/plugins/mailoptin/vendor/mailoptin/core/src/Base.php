<?php

namespace MailOptin\Core;

if ( ! defined('ABSPATH')) {
    exit;
}

use MailOptin\Core\Admin\AdminNotices;
use MailOptin\Core\Admin\SettingsPage\ConversionExport;
use MailOptin\Core\Admin\SettingsPage\LiteLicenseActivation;
use MailOptin\Core\Admin\SettingsPage\LiteToPremiumUpgrade\DoUpgrade;
use MailOptin\Core\Admin\SettingsPage\PreviewCampaignLog;
use MailOptin\Core\Admin\SettingsPage\ProUpgrade;
use MailOptin\Core\Admin\SettingsPage\UsageTracking;
use MailOptin\Core\EmailCampaigns\NewPublishPost\NewPublishPost;
use MailOptin\Core\EmailCampaigns\PostsEmailDigest\PostsEmailDigest;
use MailOptin\Core\OptinForms\FrontEndOutput;
use MailOptin\Core\OptinForms\InPost;

define('MAILOPTIN_OAUTH_URL', 'https://auth.mailoptin.io');

define('MAILOPTIN_ROOT', plugin_dir_path(MAILOPTIN_SYSTEM_FILE_PATH));
define('MAILOPTIN_URL', plugin_dir_url(MAILOPTIN_SYSTEM_FILE_PATH));
define('MAILOPTIN_ASSETS', MAILOPTIN_ROOT . 'assets/');

if (strpos(__FILE__, 'mailoptin/vendor') !== false) {
    // production url path to assets folder.
    define('MAILOPTIN_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin/vendor'))) . '/assets/');
} else {
    // dev url path to assets folder.
    define('MAILOPTIN_ASSETS_URL', MAILOPTIN_URL . '../' . dirname(substr(__FILE__, strpos(__FILE__, 'mailoptin'))) . '/assets/');
}

define('EDD_MO_ITEM_NAME', 'MailOptin', true);
define('EDD_MO_ITEM_ID', '8', true);

define('MAILOPTIN_OPTIN_THEMES_ASSETS_URL', MAILOPTIN_ASSETS_URL . 'images/optin-themes');

define('MAILOPTIN_CONNECTIONS_DB_OPTION_NAME', 'mailoptin_connections');
define('MAILOPTIN_SETTINGS_DB_OPTION_NAME', 'mailoptin_settings');

define('MO_OPTIN_CAMPAIGN_WP_OPTION_NAME', 'mo_optin_campaign');
define('MO_OPTIN_TEMPLATE_WP_OPTION_NAME', 'mailoptin_email_templates');
define('MO_EMAIL_CAMPAIGNS_WP_OPTION_NAME', 'mo_email_campaigns');
define('MO_LITE_OPTIN_CONVERSION_LIMIT', 100);
define('MO_LITE_OPTIN_CAMPAIGN_LIMIT', 3);

define('MAILOPTIN_SRC', dirname(__FILE__) . '/');
define('MAILOPTIN_SETTINGS_PAGE_FOLDER', dirname(__FILE__) . '/Admin/SettingsPage/');


define('MAILOPTIN_CAMPAIGN_ERROR_LOG', WP_CONTENT_DIR . "/uploads/mailoptin-campaign-log/");
define('MAILOPTIN_OPTIN_ERROR_LOG', WP_CONTENT_DIR . "/uploads/mailoptin-optin-log/");

define('MAILOPTIN_SETTINGS_SETTINGS_SLUG', 'mailoptin-settings');
define('MAILOPTIN_CONNECTIONS_SETTINGS_SLUG', 'mailoptin-integrations');
define('MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_SLUG', 'mailoptin-email-automations');
define('MAILOPTIN_CAMPAIGN_LOG_SETTINGS_SLUG', 'campaign-log');
define('MAILOPTIN_OPTIN_CAMPAIGNS_SETTINGS_SLUG', 'mailoptin-optin-campaigns');
define('MAILOPTIN_LEAD_BANK_SETTINGS_SLUG', 'lead-bank');
define('MAILOPTIN_ADVANCE_ANALYTICS_SETTINGS_SLUG', 'mailoptin-statistics');
define('MAILOPTIN_LICENSE_SETTINGS_SLUG', 'mailoptin-license');

define('MAILOPTIN_SETTINGS_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_SETTINGS_SETTINGS_SLUG));
define('MAILOPTIN_CONNECTIONS_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_CONNECTIONS_SETTINGS_SLUG));
define('MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_SLUG));
define('MAILOPTIN_CAMPAIGN_LOG_SETTINGS_PAGE', add_query_arg('view', MAILOPTIN_CAMPAIGN_LOG_SETTINGS_SLUG, MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE));
define('MAILOPTIN_OPTIN_CAMPAIGNS_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_OPTIN_CAMPAIGNS_SETTINGS_SLUG));
define('MAILOPTIN_LEAD_BANK_SETTINGS_PAGE', add_query_arg('view', MAILOPTIN_LEAD_BANK_SETTINGS_SLUG, MAILOPTIN_OPTIN_CAMPAIGNS_SETTINGS_PAGE));
define('MAILOPTIN_ADVANCE_ANALYTICS_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_ADVANCE_ANALYTICS_SETTINGS_SLUG));
define('MAILOPTIN_LICENSE_SETTINGS_PAGE', admin_url('admin.php?page=' . MAILOPTIN_LICENSE_SETTINGS_SLUG));

class Base
{
    public function __construct()
    {
        // Install plugin.
        register_activation_hook(MAILOPTIN_SYSTEM_FILE_PATH, array('MailOptin\Core\RegisterActivation\Base', 'run_install'));
        add_action('wpmu_new_blog', ['MailOptin\Core\RegisterActivation', 'multisite_new_blog_install']);

        RegisterScripts::get_instance();
        AjaxHandler::get_instance();
        Cron::get_instance();

        $this->admin_hooks();
        Admin\Customizer\EmailCampaign\Customizer::instance();
        Admin\Customizer\OptinForm\Customizer::instance();
        PreviewCampaignLog::get_instance();
        UsageTracking::get_instance();

        NewPublishPost::get_instance();
        PostsEmailDigest::get_instance();

        FrontEndOutput::get_instance();
        InPost::get_instance();

        add_action('widgets_init', ['MailOptin\Core\OptinForms\SidebarWidgets', 'widget_registration']);

        add_action('plugins_loaded', [$this, 'register_metadata_table']);

        add_action('plugins_loaded', [$this, 'db_updates']);

        add_filter('wpmu_drop_tables', array($this, 'wpmu_drop_tables'));
    }


    public function db_updates()
    {
        if ( ! is_admin()) {
            return;
        }

        DBUpdates::get_instance()->maybe_update();
    }

    public function admin_hooks()
    {
        if ( ! is_admin()) {
            return;
        }

        Admin\SettingsPage\Settings::get_instance();
        Admin\SettingsPage\Connections::get_instance();
        Admin\SettingsPage\EmailCampaigns::get_instance();
        Admin\SettingsPage\CampaignLog::get_instance();
        Admin\SettingsPage\OptinCampaigns::get_instance();
        Admin\SettingsPage\AdvanceAnalytics::get_instance();
        AdminNotices::get_instance();
        ConversionExport::get_instance();
        ProUpgrade::get_instance();
        DoUpgrade::get_instance();

        do_action('mailoptin_admin_hooks');
    }

    public function wpmu_drop_tables($tables)
    {
        global $wpdb;

        $db_prefix = $wpdb->prefix;

        $tables[] = $db_prefix . Core::optin_campaign_meta_table_name;
        $tables[] = $db_prefix . Core::campaign_log_meta_table_name;
        $tables[] = $db_prefix . Core::campaign_log_table_name;
        $tables[] = $db_prefix . Core::optin_campaigns_table_name;
        $tables[] = $db_prefix . Core::conversions_table_name;
        $tables[] = $db_prefix . Core::email_campaigns_table_name;
        $tables[] = $db_prefix . Core::email_campaign_meta_table_name;

        $tables = apply_filters('mo_drop_mu_database_tables', $tables, $db_prefix);

        return $tables;
    }

    /**
     * Register meta data table(s)
     */
    function register_metadata_table()
    {
        global $wpdb;
        $wpdb->optin_campaignmeta = $wpdb->prefix . Core::optin_campaign_meta_table_name;
        $wpdb->email_campaignmeta = $wpdb->prefix . Core::email_campaign_meta_table_name;
        $wpdb->campaign_logmeta   = $wpdb->prefix . Core::campaign_log_meta_table_name;
    }

    /**
     * Singleton.
     *
     * @return Base
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