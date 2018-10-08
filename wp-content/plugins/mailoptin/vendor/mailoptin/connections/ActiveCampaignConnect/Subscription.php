<?php

namespace MailOptin\ActiveCampaignConnect;

class Subscription extends AbstractActiveCampaignConnect
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

            $lead_tags = $this->get_integration_data('ActiveCampaignConnect_lead_tags');

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_activecampaign_acceptance_tag', 'gdpr');
                $lead_tags = "{$gdpr_tag}," . $lead_tags;
            }

            $subscription_form = $this->get_integration_data('ActiveCampaignConnect_form');


            $list_id = $this->list_id;

            $contact = array(
                "email" => $this->email,
                "first_name" => $name_split[0],
                "last_name" => $name_split[1],
                "p[{$list_id}]" => $list_id,
                "ip4" => \MailOptin\Core\get_ip_address(),
                "status[{$list_id}]" => 1, // "Active" status
                "instantresponders[{$list_id}]" => 1,
                'form' => absint($subscription_form),
                'tags' => $lead_tags
            );

            $contact = array_filter($contact, [$this, 'data_filter']);

            $response = $this->activecampaign_instance()->api("contact/sync", $contact);

            if (is_object($response) && $response->http_code === 200 && 1 === $response->result_code) {
                return parent::ajax_success();
            }

            self::save_optin_error_log($response->result_message, 'activecampaign');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'activecampaign');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}