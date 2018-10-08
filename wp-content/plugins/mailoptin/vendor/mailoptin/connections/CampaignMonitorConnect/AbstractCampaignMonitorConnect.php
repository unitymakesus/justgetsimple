<?php

namespace MailOptin\CampaignMonitorConnect;

use Authifly\Provider\CampaignMonitor;
use Authifly\Storage\OAuthCredentialStorage;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Core;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;
use MailOptin\Core\Repositories\AbstractCampaignLogMeta;

class AbstractCampaignMonitorConnect extends AbstractConnect
{
    /** @var \MailOptin\Core\PluginSettings\Settings */
    protected $plugin_settings;

    /** @var \MailOptin\Core\PluginSettings\Connections */
    protected $connections_settings;

    protected $client_id;

    protected $access_token;

    protected $refresh_token;

    protected $expires_at;

    public function __construct()
    {
        $this->plugin_settings = Settings::instance();
        $this->connections_settings = Connections::instance();

        $this->client_id = $this->connections_settings->campaignmonitor_client_id();
        $this->access_token = $this->connections_settings->campaignmonitor_access_token();
        $this->refresh_token = $this->connections_settings->campaignmonitor_refresh_token();
        $this->expires_at = $this->connections_settings->campaignmonitor_expires_at();

        parent::__construct();
    }

    /**
     * Is Campaign Monitor successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['campaignmonitor_access_token']);
    }

    /**
     * Return instance of CampaignMonitor class.
     *
     * @throws \Exception
     *
     * @return CampaignMonitor
     */
    public function campaignmonitorInstance()
    {
        $access_token = $this->connections_settings->campaignmonitor_access_token();

        if (empty($access_token)) {
            throw new \Exception(__('CampaignMonitor access token not found.', 'mailoptin'));
        }

        $config = [
            // secret key and callback not needed but authifly requires they have a value hence the MAILOPTIN_OAUTH_URL constant and "__"
            'callback' => MAILOPTIN_OAUTH_URL,
            'keys' => ['id' => '108102', 'secret' => '__'],
            'scope' => 'ManageLists,ImportSubscribers,CreateCampaigns,SendCampaigns,ViewReports',
        ];

        $instance = new CampaignMonitor($config, null,
            new OAuthCredentialStorage([
                'campaignmonitor.access_token' => $this->access_token,
                'campaignmonitor.refresh_token' => $this->refresh_token,
                'campaignmonitor.expires_at' => $this->expires_at,
            ]));

        // check if access token has expired and fetch/refresh with a new one.
        if ($instance->hasAccessTokenExpired()) {

            $instance->refreshAccessToken();

            $option_name = MAILOPTIN_CONNECTIONS_DB_OPTION_NAME;
            $old_data = get_option($option_name, []);
            $new_data = [
                'campaignmonitor_access_token' => $instance->getStorage()->get('campaignmonitor.access_token'),
                'campaignmonitor_refresh_token' => $instance->getStorage()->get('campaignmonitor.refresh_token'),
                'campaignmonitor_expires_at' => $instance->getStorage()->get('campaignmonitor.expires_at')
            ];

            update_option($option_name, array_merge($old_data, $new_data));
        }

        return $instance;
    }

    /**
     * Convert campaign log ID to UUID.
     *
     * @param int $id
     *
     * @return string
     */
    protected function campaignlog_id_to_uuid($id)
    {
        $uuid = wp_generate_password(12, false);

        AbstractCampaignLogMeta::add_campaignlog_meta($id, 'campaignmonitor_email_fetcher', $uuid);

        return $uuid;
    }

    /**
     * Convert UUID back to campaign log ID.
     *
     * @param string $uuid
     *
     * @return null|string
     */
    protected function uuid_to_campaignlog_id($uuid)
    {
        global $wpdb;
        $table = $wpdb->prefix . Core::campaign_log_meta_table_name;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT campaign_log_id from $table WHERE meta_key = 'campaignmonitor_email_fetcher' AND meta_value = %s",
                $uuid
            )
        );
    }
}