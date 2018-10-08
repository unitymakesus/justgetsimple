<?php

namespace MailOptin\MailChimpConnect;

class Subscription extends AbstractMailChimpConnect
{
    public $email;
    public $name;
    public $list_id;
    public $extras;
    protected $optin_campaign_id;

    public function __construct($email, $name, $list_id, $extras)
    {
        $this->email = $email;
        $this->name = $name;
        $this->list_id = $list_id;
        $this->extras = $extras;

        $this->optin_campaign_id = absint($this->extras['optin_campaign_id']);

        parent::__construct();
    }

    /**
     * True if double optin is not disabled.
     *
     * @return bool
     */
    public function is_double_optin()
    {
        $setting = $this->get_integration_data('MailChimpConnect_disable_double_optin');

        $val = $setting !== true;

        return apply_filters('mo_connections_mailchimp_is_double_optin', $val, $this->optin_campaign_id);
    }

    /**
     * Return array of selected interests.
     *
     * @return array
     */
    public function interests()
    {
        $segmentation_type = $this->get_integration_data('MailChimpConnect_group_segment_type');

        if ($segmentation_type == 'automatic') {
            $interests = array_keys($this->get_integration_data('MailChimpConnect_interests'));
        } else {
            $interests = isset($this->extras['mo-mailchimp-interests']) ? $this->extras['mo-mailchimp-interests'] : [];
        }

        if (empty($interests)) return [];

        $interests = array_map('sanitize_text_field', $interests);

        $interests = array_fill_keys($interests, true);

        return $interests;
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {
            $name_split = self::get_first_last_names($this->name);

            $optin_status = $this->is_double_optin() ? 'pending' : 'subscribed';

            $parameters = [
                'merge_fields' => array_filter(
                    [
                        'FNAME' => $name_split[0],
                        'LNAME' => $name_split[1]
                    ],
                    'is_string'
                ),
                'interests' => $this->interests(),
                'status_if_new' => $optin_status,
                'ip_signup' => \MailOptin\Core\get_ip_address()
            ];

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $gdpr_tag = apply_filters('mo_connections_mailchimp_acceptance_tag', 'GDPR');
                $field_key = strtoupper(sanitize_key('MO' . $gdpr_tag));
                try {
                    $this->mc_list_instance()->addMergeField(
                        $this->list_id,
                        $gdpr_tag,
                        'text',
                        ['tag' => $field_key]
                    );
                } catch (\Exception $e) {
                    // do nothing.
                }

                $parameters['merge_fields'][$field_key] = 'true';
            }

            $parameters = array_filter($parameters, [$this, 'data_filter']);

            $response = $this->mc_list_instance()->addOrUpdateMember($this->list_id, $this->email, $parameters);

            if (is_object($response) && in_array($response->status, ['subscribed', 'pending'])) {
                return parent::ajax_success();
            }

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'mailchimp');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}