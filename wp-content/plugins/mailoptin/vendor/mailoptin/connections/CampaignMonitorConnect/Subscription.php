<?php

namespace MailOptin\CampaignMonitorConnect;

class Subscription extends AbstractCampaignMonitorConnect
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
     * @param $field_name
     * @param $field_type
     * @return void
     */
    public function create_custom_fields($field_name, $field_type)
    {
        try {
            $headers = ['Content-Type' => 'application/json'];

            $payload = [
                "FieldName" => $field_name,
                "DataType" => $field_type,
                "VisibleInPreferenceCenter" => true
            ];

            $this->campaignmonitorInstance()->apiRequest("lists/{$this->list_id}/customfields.json", 'POST', $payload, $headers);
        } catch (\Exception $e) {
            // 255 error means custom field already exist so bail.
            if (strpos($e->getMessage(), '255') !== false) return;
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'campaignmonitor');
        }
    }

    /**
     * @return mixed
     */
    public function subscribe()
    {
        try {
            $custom_fields = [];
            $consent = 'Unchanged';

            $this->create_custom_fields('Note', 'Text');

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                $consent = 'Yes';
            }

            $response = $this->campaignmonitorInstance()->addSubscriberEmailName($this->list_id, $this->email, $this->name, $custom_fields, $consent);

            if ($response) {
                return parent::ajax_success();
            }

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'campaignmonitor');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}