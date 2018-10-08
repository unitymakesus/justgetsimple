<?php

namespace MailOptin\RegisteredUsersConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\ControlsHelpers;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Select_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Connections\ConnectionInterface;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class Connect extends AbstractConnect implements ConnectionInterface
{
    /**
     * @var WP_Mail_BG_Process
     */
    public $bg_process_instance;

    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'RegisteredUsersConnect';

    public function __construct()
    {
        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));
        add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'integration_customizer_settings'), 10, 2);
        add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'integration_customizer_controls'), 10, 4);

        add_filter('mailoptin_email_campaign_tab_toggle_general_config', function($val) {
            $val[] = 'RegisteredUsersConnect_user_role';

            return $val;
        });

        add_action('plugins_loaded', array($this, 'init'));

        parent::__construct();
    }

    public function init()
    {
        $this->bg_process_instance = new WP_Mail_BG_Process();
    }

    public static function features_support($connection_service = '')
    {
        return ['email_campaign'];
    }

    /**
     * Register Sendy Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('Registered Users', 'mailoptin');

        return $connections;
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function integration_customizer_settings($settings)
    {
        $settings['RegisteredUsersConnect_user_role'] = array(
            'default' => apply_filters('mailoptin_customizer_optin_campaign_RegisteredUsersConnect_user_role', ''),
            'type' => 'option',
            'transport' => 'postMessage',
        );

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
    public function integration_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        // always prefix with the name of the connect/connection service.
        $controls['RegisteredUsersConnect_user_role'] = new WP_Customize_Chosen_Select_Control(
            $wp_customize,
            $option_prefix . '[RegisteredUsersConnect_user_role]',
            apply_filters('mo_optin_form_customizer_RegisteredUsersConnect_user_role_args', array(
                    'label' => __('Restrict to User Role'),
                    'section' => $customizerClassInstance->campaign_settings_section_id,
                    'settings' => $option_prefix . '[RegisteredUsersConnect_user_role]',
                    'description' => __('Select user role(s) that newsletter will only be delivered to. Leave empty to send to all roles.', 'mailoptin'),
                    'choices' => ControlsHelpers::get_roles(),
                    'priority' => 62
                )
            )
        );

        return $controls;
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
        $user_roles_restriction = EmailCampaignRepository::get_customizer_value($email_campaign_id, 'RegisteredUsersConnect_user_role', []);

        $args = ['fields' => ['user_login', 'user_email']];
        if (!empty($user_roles_restriction)) {
            $args['role__in'] = $user_roles_restriction;
        }
        // array of username and email object.
        $users_data = get_users($args);

        // campaign log and email campaign IDs to each $users_data
        $users_data = array_reduce($users_data, function ($carry, $user_data) use ($email_campaign_id, $campaign_log_id, $content_html, $content_text) {
            $user_data->email_campaign_id = $email_campaign_id;
            $user_data->campaign_log_id = $campaign_log_id;
            $user_data->content_html = $content_html;
            $user_data->content_text = $content_text;
            $carry[] = $user_data;

            return $carry;
        });

        foreach ($users_data as $user_data) {
            $this->bg_process_instance->push_to_queue($user_data);
        }

        $this->bg_process_instance->save()->dispatch();

        return ['success' => true];
    }


    /**
     * Fulfill interface contract.
     *
     * {@inheritdoc}
     */
    public function replace_placeholder_tags($content, $type = 'html')
    {
        return $this->replace_footer_placeholder_tags($content);
    }

    /**
     * Fulfill interface contract.
     */
    public function get_email_list()
    {
    }

    /**
     * Fulfill interface contract.
     */
    public function subscribe($email, $name, $list_id, $extras = null)
    {
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