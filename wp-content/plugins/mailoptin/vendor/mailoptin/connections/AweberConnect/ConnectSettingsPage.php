<?php

namespace MailOptin\AweberConnect;

class ConnectSettingsPage extends AbstractAweberConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);

        add_filter('wp_cspa_santized_data', [$this, 'remove_access_token_persistence'], 10, 2);

        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_access_token_persistence']);
    }

    /**
     * Build the settings metabox for Aweber
     *
     * @param array $arg
     *
     * @return array
     */
    public function connection_settings($arg)
    {
        if (self::is_connected()) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
            $button_text = __('RE-AUTHORIZE', 'mailoptin');
            $button_color = 'mobtnGreen';
        } else {
            $status = sprintf('<span style="color:#FF0000">(%s)</span>', __('Not Connected', 'mailoptin'));
            $button_text = __('AUTHORIZE', 'mailoptin');
            $button_color = 'mobtnPurple';
        }

        $settingsArg[] = array(
            'section_title' => __('Aweber Connection', 'mailoptin') . " $status",
            'type' => self::EMAIL_MARKETING_TYPE,
            'aweber_auth' => array(
                'type' => 'arbitrary',
                'data' => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>',
                    add_query_arg('redirect_url', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE, MAILOPTIN_OAUTH_URL . '/aweber/'),
                    $button_color,
                    $button_text
                ),
                'description' => '<p class="description" style="text-align:center">' .
                    sprintf(__('Authorization is required to grant <strong>%s</strong> access to interact with your Aweber account.', 'mailoptin'), 'MailOptin') .
                    '</p>',
            ),
            'disable_submit_button' => true,
        );

        return array_merge($arg, $settingsArg);
    }

    /**
     * Prevent access token from veing overriden when settings page is saved.
     *
     * @param array $sanitized_data
     * @param string $option_name
     *
     * @return mixed
     */
    public function remove_access_token_persistence($sanitized_data, $option_name)
    {
        // remove the access token, token secret and account ID from being overridden on save of settings.
        if ($option_name == MAILOPTIN_CONNECTIONS_DB_OPTION_NAME) {
            unset($sanitized_data['aweber_access_token']);
            unset($sanitized_data['aweber_access_token_secret']);
            unset($sanitized_data['aweber_account_id']);
        }

        return $sanitized_data;
    }

    /**
     * Persist access token.
     *
     * @param string $option_name DB wp_option key for saving connection settings.
     */
    public function handle_access_token_persistence($option_name)
    {
        if (!empty($_GET['mo-save-oauth-provider']) && $_GET['mo-save-oauth-provider'] == 'aweber' && !empty($_GET['access_token'])) {
            $old_data = get_option($option_name, []);
            $new_data = array_map('rawurldecode', [
                'aweber_access_token' => $_GET['access_token'],
                'aweber_access_token_secret' => $_GET['access_token_secret'],
                'aweber_account_id' => $_GET['account_id']
            ]);

            update_option($option_name, array_merge($old_data, $new_data));

            $connection = Connect::$connectionName;

            // delete connection cache
            delete_transient("_mo_connection_cache_$connection");

            wp_redirect(MAILOPTIN_CONNECTIONS_SETTINGS_PAGE);
            exit;
        }
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