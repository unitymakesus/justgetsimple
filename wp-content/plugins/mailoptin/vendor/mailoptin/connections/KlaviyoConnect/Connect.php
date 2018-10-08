<?php

namespace MailOptin\KlaviyoConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Toggle_Control;
use MailOptin\Core\Admin\Customizer\OptinForm\Customizer;
use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractKlaviyoConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'KlaviyoConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        add_filter('mo_optin_form_integrations_default', array($this, 'integration_customizer_settings'));
        add_filter('mo_optin_integrations_controls_after', array($this, 'integration_customizer_controls'));

        parent::__construct();
    }

    public static function features_support($connection_service = '')
    {
        return ['optin_campaign', 'email_campaign'];
    }

    /**
     * Register Klaviyo Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Klaviyo', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['KlaviyoConnect_disable_double_optin'] = apply_filters('mailoptin_customizer_optin_campaign_KlaviyoConnect_disable_double_optin', false);

        return $settings;
    }

    /**
     * @param array $controls
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {

            $controls[] = [
                'field' => 'toggle',
                'name' => 'KlaviyoConnect_disable_double_optin',
                'label' => __('Disable Double Optin', 'mailoptin'),
                'description' => __("Double optin requires users to confirm their email address before they are added or subscribed (recommended).", 'mailoptin'),
            ];

        } else {

            $content = sprintf(
                __("%sMailOptin Premium%s allows you disable double optin and save leads data such as referrer, conversion page, user agent, IP address and optin campaign to Klaviyo", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=klaviyo_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            $controls[] = [
                'name' => 'KlaviyoConnect_upgrade_notice',
                'field' => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
    }

    /**
     * Replace placeholder tags with actual Klaviyo merge tags.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        $search = [
            '{{webversion}}',
            '{{unsubscribe}}'
        ];

        $replace = [
            '{% web_view_link %}',
            '{% unsubscribe_link %}'
        ];

        $content = str_replace($search, $replace, $content);

        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * {@inherit_doc}
     *
     * Return array of email list
     *
     * @return mixed
     */
    public function get_email_list()
    {
        try {
            $response = $this->klaviyo_instance()->get_lists();

            // an array with list id as key and name as value.
            $lists_array = array();

            if (self::is_http_code_success($response['status_code'])) {

                $lists = $response['body']->data;

                if (!empty($lists)) {
                    foreach ($lists as $list) {
                        $lists_array[$list->id] = $list->name;
                    }
                }
                return $lists_array;
            }

            self::save_optin_error_log($response['body']->status . ': ' . $response['body']->message, 'klaviyo');

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'klaviyo');
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @param int $email_campaign_id
     * @param int $campaign_log_id
     * @param string $subject
     * @param string $content_html
     * @param string $content_text
     *
     * @throws \Exception
     *
     * @return array
     */
    public function send_newsletter($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text)
    {
        return (new SendCampaign($email_campaign_id, $campaign_log_id, $subject, $content_html, $content_text))->send();
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $list_id ID of email list to add subscriber to
     * @param mixed|null $extras
     *
     * @return mixed
     */
    public function subscribe($email, $name, $list_id, $extras = null)
    {
        return (new Subscription($email, $name, $list_id, $extras))->subscribe();
    }

    /**
     * Singleton poop.
     *
     * @return Connect|null
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