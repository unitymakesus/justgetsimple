<?php

namespace MailOptin\AweberConnect;

class Subscription extends AbstractAweberConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;
    protected $aweber;

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

            $lead_tags = $this->get_integration_data('AweberConnect_lead_tags');

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_aweber_acceptance_tag', 'gdpr');
                $lead_tags = "{$gdpr_tag}," . $lead_tags;
            }

            $payload = [
                'email' => $this->email,
                'name' => $this->name,
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ];

            if (!empty($lead_tags)) {
                $payload['tags'] = json_encode(array_map('trim', explode(',', $lead_tags)));
            }

            $payload = array_filter($payload, [$this, 'data_filter']);

            // save an instance of the Aweber Auth. Necessary to prevent re-instantiation and so we can
            // capture request status code below.
            $this->aweber = $this->aweber_instance();

            $this->aweber->addSubscriber(
                $this->account_id,
                $this->list_id,
                $payload
            );

            if (201 === $this->aweber->httpClient->getResponseHttpCode()) {
                return parent::ajax_success();
            }

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {

            $httpStatusCode = $this->aweber->httpClient->getResponseHttpCode();
            $httpResponseBody = $this->aweber->httpClient->getResponseBody();

            if (400 === $httpStatusCode && strpos($httpResponseBody, 'already subscribed')) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'aweber');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}