<?php

namespace MailOptin\MailPoetConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Custom_Content;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Toggle_Control;
use MailOptin\Core\Admin\Customizer\OptinForm\Customizer;
use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractMailPoetConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'MailPoetConnect';

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
        return ['optin_campaign'];
    }

    /**
     * Register MailPoet Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('MailPoet', 'mailoptin');

        return $connections;
    }

    /**
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
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
            // an array with list id as key and name as value.
            $lists_array = array();

            if (class_exists('\MailPoet\API\API')) {
                $response = \MailPoet\API\API::MP('v1')->getLists();

                if (is_array($response) && ! empty($response)) {
                    foreach ($response as $list) {
                        // remove trash list.
                        if ( ! empty($list['deleted_at'])) continue;
                        // only lists of type "default" can be manually subscribed to.
                        // Other list types are automatically generated and don't/shouldn't accept subscribers.
                        if($list['type'] != 'default') continue;
                        $lists_array[$list['id']] = $list['name'];
                    }
                }
            }

            return $lists_array;

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'mailpoet');
        }
    }

    /**
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
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['MailPoetConnect_disable_send_confirmation_email'] = apply_filters('mailoptin_customizer_optin_campaign_MailPoetConnect_disable_send_confirmation_email', false);

        $settings['MailPoetConnect_disable_schedule_welcome_email'] = apply_filters('mailoptin_customizer_optin_campaign_MailPoetConnect_upgrade_notice', false);

        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param Customizer $customizerClassInstance
     *
     * @return mixed
     */
    public function integration_customizer_controls($controls)
    {
        if (defined('MAILOPTIN_DETACH_LIBSODIUM') === true) {

            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'MailPoetConnect_disable_send_confirmation_email',
                'label'       => __('Disable Double Optin', 'mailoptin'),
                'description' => __("Double optin (A.K.A signup confirmation or confirmation email) requires users to confirm their email address before they are added or subscribed (recommended).", 'mailoptin'),
            ];

            $controls[] = [
                'field'       => 'toggle',
                'name'        => 'MailPoetConnect_disable_schedule_welcome_email',
                'label'       => __('Disable Welcome Email', 'mailoptin'),
                'description' => sprintf(
                    __('Welcome Emails is a mail (or series of emails) already created to be delivered to new subscribers.')
                )
            ];

        } else {

            $content = sprintf(
                __("Upgrade to %sMailOptin Premium%s to disable MailPoet %sdouble optin%s and welcome email.", 'mailoptin'),
                '<a target="_blank" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=mailpoet_connection">',
                '</a>',
                '<strong>',
                '</strong>'
            );

            $controls[] = [
                'name'    => 'MailPoetConnect_upgrade_notice',
                'field'   => 'custom_content',
                'content' => $content
            ];
        }

        return $controls;
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