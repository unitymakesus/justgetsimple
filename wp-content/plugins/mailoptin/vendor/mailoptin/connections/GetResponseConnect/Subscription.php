<?php

namespace MailOptin\GetResponseConnect;

class Subscription extends AbstractGetResponseConnect
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

            $lead_data = array(
                'name' => $this->name,
                'email' => $this->email,
                'dayOfCycle' => 0,
                'campaign' => array('campaignId' => $this->list_id),
                'ipAddress' => \MailOptin\Core\get_ip_address()
            );

            if (isset($this->extras['mo-acceptance']) && $this->extras['mo-acceptance'] == 'yes') {
                // tag has to be mall letters
                $gdpr_tag = strtolower(apply_filters('mo_connections_getresponse_acceptance_tag', 'gdpr'));
                $result = (array)$this->getresponse_instance()->getTags(['fields' => 'name']);
                if (!empty($result)) {
                    foreach ($result as $tag) {
                        if ($tag->name == $gdpr_tag) {
                            $gdpr_tag_id = $tag->tagId;
                            break;
                        }
                    }
                }

                if (!isset($gdpr_tag_id)) {
                    $result = $this->getresponse_instance()->setTags(['name' => $gdpr_tag]);
                    $gdpr_tag_id = $result->tagId;
                }

                $lead_data['tags'] = [
                    ['tagId' => $gdpr_tag_id]
                ];
            }
            // it's important to remove empty param as GR will throw an error if say name field is empty.
            $lead_data = array_filter($lead_data, [$this, 'data_filter']);

            $response = (array)$this->getresponse_instance()->addContact($lead_data);

            // if user/email already exist.
            if (isset($response['code']) && $response['code'] === 1008) return parent::ajax_success();

            // return empty (array) response on success.
            if (empty($response)) return parent::ajax_success();

            self::save_optin_error_log(json_encode($response), 'getresponse');

            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));

        } catch (\Exception $e) {
            self::save_optin_error_log($e->getCode() . ': ' . $e->getMessage(), 'getresponse');
            return parent::ajax_failure(__('There was an error saving your contact. Please try again.', 'mailoptin'));
        }
    }
}