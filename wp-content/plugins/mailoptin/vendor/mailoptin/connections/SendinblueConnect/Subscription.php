<?php

namespace MailOptin\SendinblueConnect;

class Subscription extends AbstractSendinblueConnect
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

    public function subscribe()
    {
        $name_split = self::get_first_last_names($this->name);

        try {

            $lead_data = [
                'email'   => $this->email,
                'listIds' => [absint($this->list_id)]
            ];

            if ( ! empty($name_split[0])) {
                $lead_data['attributes']['FIRSTNAME'] = $name_split[0];
            }

            if ( ! empty($name_split[1])) {
                $lead_data['attributes']['LASTNAME'] = $name_split[1];
            }

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                // in capital letter pls.
                $gdpr_tag = strtoupper(apply_filters('mo_connections_sendinblue_acceptance_tag', 'GDPR'));
                $this->sendinblue_instance()->make_request("contacts/attributes/normal/$gdpr_tag", ['type' => 'text'], 'post');

                $lead_data['attributes'][$gdpr_tag] = 'true';
            }

            $response = $this->sendinblue_instance()->make_request('contacts', $lead_data, 'post');

            if ($response['status_code'] >= 200 && $response['status_code'] <= 299 && isset($response['body']->id)) {
                return parent::ajax_success();
            }

            if (isset($response['body']->code, $response['body']->message)) {
                if ('duplicate_parameter' == $response['body']->code) {
                    return parent::ajax_success();
                }
            }

            self::save_optin_error_log($response['body']->code . ': ' . $response['body']->message, 'sendinblue');

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'sendinblue');

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}