<?php

namespace MailOptin\MailPoetConnect;

class Subscription extends AbstractMailPoetConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;

    public function __construct($email, $name, $list_id, $extras)
    {
        $this->email   = $email;
        $this->name    = $name;
        $this->list_id = $list_id;
        $this->extras  = $extras;

        parent::__construct();
    }

    /**
     * True double optin is not disabled.
     *
     * @return bool
     */
    public function is_double_optin()
    {
        $optin_campaign_id = absint($this->extras['optin_campaign_id']);

        $setting = $this->get_integration_data('MailPoetConnect_disable_send_confirmation_email');

        $val = $setting !== true;

        return apply_filters('mo_connections_mailpoet_is_double_optin', $val, $optin_campaign_id);
    }

    /**
     * True welcome message to new subscribers isn't disabled.
     *
     * @return bool
     */
    public function is_welcome_message()
    {
        $setting = $this->get_integration_data('MailPoetConnect_disable_schedule_welcome_email');

        return $setting !== true;
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {
            $name_split = self::get_first_last_names($this->name);

            $subscriber_data = array_filter(
                [
                    'email'      => $this->email,
                    'first_name' => $name_split[0],
                    'last_name'  => $name_split[1]
                ],
                [$this, 'data_filter']
            );

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_mailpoet_acceptance_tag', 'GDPR');

                $custom_fields = \MailPoet\API\API::MP('v1')->getSubscriberFields();

                $gdpr_field_id = false;
                foreach ($custom_fields as $field) {
                    if ($field['name'] == $gdpr_tag) {
                        $gdpr_field_id = $field['id'];
                        break;
                    }
                }

                if ( ! $gdpr_field_id) {
                    $result        = (new \MailPoet\API\JSON\v1\CustomFields())->save(['type' => 'text', 'name' => $gdpr_tag])->getData();
                    $gdpr_field_id = 'cf_' . $result['data']['id'];
                }

                $subscriber_data[$gdpr_field_id] = 'true';
            }

            $options = array(
                'send_confirmation_email' => $this->is_double_optin(),
                'schedule_welcome_email'  => $this->is_welcome_message()
            );

            $list_id = absint($this->list_id);

            if (\MailPoet\Models\Subscriber::findOne($this->email)) {
                \MailPoet\API\API::MP('v1')->subscribeToList($this->email, $list_id, $options);

            } else {
                \MailPoet\API\API::MP('v1')->addSubscriber($subscriber_data, [$list_id], $options);
            }

            return parent::ajax_success();

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'mailpoet');

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}