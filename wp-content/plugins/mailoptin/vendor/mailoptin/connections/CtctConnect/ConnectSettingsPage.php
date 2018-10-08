<?php

namespace MailOptin\CtctConnect;

class ConnectSettingsPage extends AbstractCtctConnect
{
    public function __construct()
    {
        parent::__construct();

        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'), 10, 99);

        add_filter('wp_cspa_santized_data', [$this, 'remove_access_token_persistence'], 10, 2);

        add_action('mailoptin_before_connections_settings_page', [$this, 'handle_access_token_persistence']);
    }

    /**
     * Build the settings metabox for constact contact
     *
     * @param array $arg
     *
     * @return array Because AWeber requires external authentication, you will need to register our application with AWeber before you can proceed.
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
            'section_title' => __('ConstantContact Connection', 'mailoptin') . " $status",
            'type' => self::EMAIL_MARKETING_TYPE,
            'ctct_auth' => array(
                'type' => 'arbitrary',
                'data' => sprintf(
                    '<div class="moBtncontainer"><a href="%s" class="mobutton mobtnPush %s">%s</a></div>',
                    add_query_arg('redirect_url', MAILOPTIN_CONNECTIONS_SETTINGS_PAGE, MAILOPTIN_OAUTH_URL . '/constantcontact/'),
                    $button_color,
                    $button_text
                ),
                'description' => '<p class="description" style="text-align:center">' .
                    sprintf(__('Authorization is required to grant <strong>%s</strong> access to interact with your Constant Contact account.', 'mailoptin'), 'MailOptin') .
                    '</p>',
            ),
            'disable_submit_button' => true
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
        // remove the access token from being overridden on save of settings.
        if ($option_name == MAILOPTIN_CONNECTIONS_DB_OPTION_NAME) {
            unset($sanitized_data['ctct_access_token']);
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
        if (!empty($_GET['mo-save-oauth-provider']) && $_GET['mo-save-oauth-provider'] == 'constantcontact' && !empty($_GET['access_token'])) {
            $old_data = get_option($option_name, []);
            $new_data = array_map('rawurldecode', [
                'ctct_access_token' => $_GET['access_token']
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