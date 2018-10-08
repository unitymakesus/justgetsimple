<?php

namespace MailOptin\DripConnect;

use DrewM\Drip\Dataset;
use MailOptin\Core\Repositories\OptinCampaignsRepository;

class Subscription extends AbstractDripConnect
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
     * True if optin type is double optin is not disabled.
     *
     * @return bool
     */
    public function is_double_optin()
    {
        $optin_campaign_id = absint($this->extras['optin_campaign_id']);

        $setting = $this->get_integration_data('DripConnect_enable_double_optin');

        $val = $setting === true;

        return apply_filters('mo_connections_drip_is_double_optin', $val, $optin_campaign_id);
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {
            $name_split = self::get_first_last_names($this->name);

            $lead_tags = $this->get_integration_data('DripConnect_lead_tags');

            $custom_field_data = array_filter(
                ['name' => $this->name, 'first_name' => $name_split[0], 'last_name' => $name_split[1]],
                function ($value) {
                    return !empty($value);
                }
            );

            $lead_data = [
                'email' => $this->email,
                'custom_fields' => $custom_field_data,
                'ip_address' => \MailOptin\Core\get_ip_address(),
                'double_optin' => $this->is_double_optin()
            ];

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $lead_data['eu_consent'] = 'granted';
                $lead_data['eu_consent_message'] = OptinCampaignsRepository::get_merged_customizer_value($this->extras['optin_campaign_id'], 'note');
            }

            if (!empty($lead_tags)) {
                $lead_data['tags'] = array_map('trim', explode(',', $lead_tags));
            }

            $lead_data = array_filter($lead_data, [$this, 'data_filter']);

            $data = new Dataset('subscribers', $lead_data);

            $response = $this->drip_instance()->post("campaigns/{$this->list_id}/subscribers", $data);

            if ($response->status >= 200 && $response->status <= 299) {
                return parent::ajax_success();
            }

            if (isset($response->error, $response->message)) {
                if (strpos($response->message, 'already subscribed') !== false) {
                    return parent::ajax_success();
                }
            }

            self::save_optin_error_log($response->error . ': ' . $response->message, 'drip');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'drip');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}