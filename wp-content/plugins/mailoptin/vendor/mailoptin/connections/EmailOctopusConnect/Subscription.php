<?php

namespace MailOptin\EmailOctopusConnect;

class Subscription extends AbstractEmailOctopusConnect
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
            $custom_field = [];
            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_emailoctopus_acceptance_tag', 'GDPR');
                $field_key = sanitize_key($gdpr_tag);
                $this->emailoctopus_instance()->create_list_field(
                    $this->list_id,
                    $gdpr_tag,
                    $field_key
                );

                $custom_field = [$field_key => 'true'];
            }

            $response = $this->emailoctopus_instance()->add_subscriber(
                $this->list_id,
                $this->email,
                $name_split[0],
                $name_split[1],
                $custom_field
            );

            if ($response['status_code'] >= 200 && $response['status_code'] <= 299) {
                return parent::ajax_success();
            }

            $error_code = $response['body']->error->code;
            $error_message = $response['body']->error->message;

            if ($error_code == 'MEMBER_EXISTS_WITH_EMAIL_ADDRESS') return parent::ajax_success();

            self::save_optin_error_log($error_code . ': ' . $error_message, 'emailoctopus');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'emailoctopus');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}