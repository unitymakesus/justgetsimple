<?php

namespace MailOptin\MailerliteConnect;

class Subscription extends AbstractMailerliteConnect
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

            $lead_data = [
                'email' => $this->email,
                'name' => $this->name,
                'resubscribe' => true
            ];

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_constant_contact_acceptance_tag', 'GDPR');
                $get_fields = $this->mailerlite_instance()->fields()->get()->toArray();

                $found_flag = false;
                if (is_array($get_fields)) {
                    foreach ($get_fields as $item) {
                        if ($item->title == $gdpr_tag) {
                            $found_flag = true;
                            break;
                        }
                    }
                }

                if (!$found_flag) $this->mailerlite_instance()->fields()->create(['title' => $gdpr_tag]);

                $lead_data['fields'] = [strtolower($gdpr_tag) => 'true'];
            }

            $lead_data = array_filter($lead_data, [$this, 'data_filter']);

            $response = $this->mailerlite_instance()->groups()->addSubscriber($this->list_id, $lead_data);

            if (is_object($response) && is_int($response->id)) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($response->error->code . ': ' . $response->error->message, 'mailerlite');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            // if already subscribed, return success.
            if (strpos($e->getMessage(), 'Member Exists')) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'mailerlite');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}