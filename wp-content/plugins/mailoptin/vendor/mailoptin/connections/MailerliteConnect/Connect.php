<?php

namespace MailOptin\MailerliteConnect;

use MailOptin\Core\Connections\ConnectionInterface;

class Connect extends AbstractMailerliteConnect implements ConnectionInterface
{
    /**
     * @var string key of connection service. its important all connection name ends with "Connect"
     */
    public static $connectionName = 'MailerliteConnect';

    public function __construct()
    {
        ConnectSettingsPage::get_instance();

        add_filter('mailoptin_registered_connections', array($this, 'register_connection'));

        parent::__construct();
    }

    public static function features_support($connection_service = '')
    {
        return ['optin_campaign', 'email_campaign'];
    }

    /**
     * Register MailerLite Connection.
     *
     * @param array $connections
     *
     * @return array
     */
    public function register_connection($connections)
    {
        $connections[self::$connectionName] = __('MailerLite', 'mailoptin');

        return $connections;
    }

    /**
     * Replace placeholder tags with actual MailerLite tags.
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
            '{$url}',
            '{$unsubscribe}'
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
            $groupsApi = $this->mailerlite_instance()->groups();

            $allGroups = $groupsApi->get()->toArray(); // returns array of groups

            // an array with list id as key and name as value.
            $lists_array = array();

            if (!empty($allGroups)) {
                foreach ($allGroups as $list) {
                    $lists_array[$list->id] = $list->name;
                }
            }

            return $lists_array;


        } catch (\Exception $e) {
            self::save_optin_error_log($e->getMessage(), 'mailerlite');
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