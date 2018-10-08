<?php

namespace MailOptin\ConvertKitConnect;

class Subscription extends AbstractConvertKitConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;

    public function __construct($email, $name, $list_id, $extras)
    {
        $this->email = $email;
        $this->name = $name;
        $this->list_id = $list_id;
        $this->extras = $extras;

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {
            $name_split = self::get_first_last_names($this->name);

            $subscriber_sequences = array_map(
                'absint',
                $this->get_integration_data('ConvertKitConnect_subscriber_sequences')
            );

            $subscriber_tags = array_map(
                'absint',
                $this->get_integration_data('ConvertKitConnect_subscriber_tags')
            );

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $all_tags = Connect::get_instance()->tags();
                $gdpr_tag = array_search('GDPR: Email Consent', $all_tags);
                $subscriber_tags[] = $gdpr_tag;
            }

            $response = $this->convertkit_instance()->add_subscriber(
                $this->list_id,
                $this->email,
                $name_split[0],
                $name_split[1],
                $subscriber_sequences,
                $subscriber_tags
            );

            if ($response['status_code'] >= 200 && $response['status_code'] <= 299) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($response['body']->error . ': ' . $response['body']->message, 'convertkit');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'convertkit');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}