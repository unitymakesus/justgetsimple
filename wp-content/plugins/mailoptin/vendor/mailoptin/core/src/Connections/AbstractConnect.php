<?php

namespace MailOptin\Core\Connections;

use MailOptin\Core\Admin\Customizer\OptinForm\AbstractCustomizer;
use MailOptin\Core\EmailCampaigns\TemplateTrait;
use MailOptin\Core\Repositories\EmailCampaignRepository;
use MailOptin\Core\Repositories\OptinCampaignsRepository;

abstract class AbstractConnect
{
    use TemplateTrait;

    const EMAIL_MARKETING_TYPE = 'emailmarketing';
    const SOCIAL_TYPE = 'social';
    const CRM_TYPE = 'crm';
    const OTHER_TYPE = 'other';
    const ANALYTICS_TYPE = 'analytics';

    public $extras = [];

    public function __construct()
    {
    }

    public function get_integration_data($data_key, $integration_data = [], $default = '')
    {
        $optin_campaign_id = isset($this->extras['optin_campaign_id']) ? absint($this->extras['optin_campaign_id']) : '';
        $defaults = (new AbstractCustomizer($optin_campaign_id))->customizer_defaults['integrations'];

        $data = $this->is_valid_data($default) ? $default : @$defaults[$data_key];
        $bucket = is_array($integration_data) && !empty($integration_data) ? $integration_data : $this->extras['integration_data'];

        if (isset($bucket[$data_key]) && $this->is_valid_data($bucket[$data_key])) {
            $data = $bucket[$data_key];
        }

        return $data;
    }

    public static function is_boolean($maybe_bool)
    {
        if (is_bool($maybe_bool)) {
            return true;
        }

        if (is_string($maybe_bool)) {
            $maybe_bool = strtolower($maybe_bool);

            $valid_boolean_values = array(
                'false',
                'true',
                '0',
                '1',
            );

            return in_array($maybe_bool, $valid_boolean_values, true);
        }

        if (is_int($maybe_bool)) {
            return in_array($maybe_bool, array(0, 1), true);
        }

        return false;
    }

    public function is_valid_data($value)
    {
        return $this->data_filter($value);
    }

    public function data_filter($value)
    {
        return self::is_boolean($value) || is_int($value) || !empty($value);
    }

    /**
     * Helper to check if ajax response is successful.
     *
     * @param $response
     * @return bool
     */
    public static function is_ajax_success($response)
    {
        return isset($response['success']) && $response['success'] === true;
    }

    /**
     * Helper to return success error.
     *
     * @return array
     */
    public static function ajax_success()
    {
        return ['success' => true];
    }

    /**
     * Check if HTTP status code is not successful.
     *
     * @param int $code
     * @return bool
     */
    public static function is_http_code_not_success($code)
    {
        $code = absint($code);
        return $code < 200 || $code > 299;
    }

    /**
     * Check if HTTP status code is successful.
     *
     * @param int $code
     * @return bool
     */
    public static function is_http_code_success($code)
    {
        $code = absint($code);
        return $code >= 200 && $code <= 299;
    }

    /**
     * Helper to return failed error.
     *
     * @param string $error
     *
     * @return array
     */
    public static function ajax_failure($error = '')
    {
        return ['success' => false, 'message' => $error];
    }

    /**
     * Save error log.
     *
     * @param string $message
     * @param int $campaign_log_id
     * @param int $email_campaign_id
     */
    public static function save_campaign_error_log($message, $campaign_log_id, $email_campaign_id)
    {
        if (!isset($message) || !isset($campaign_log_id) || !isset($email_campaign_id)) {
            return;
        }

        $email_campaign_name = EmailCampaignRepository::get_email_campaign_name($email_campaign_id);
        $filename = md5($email_campaign_name . $campaign_log_id);


        $error_log_folder = MAILOPTIN_CAMPAIGN_ERROR_LOG;

        // does bugs folder exist? if NO, create it.
        if (!file_exists($error_log_folder)) {
            mkdir($error_log_folder, 0755);
        }

        error_log($message . "\r\n\r\n", 3, "{$error_log_folder}{$filename}.log");
    }

    /**
     * Save email service/connect specific optin errors.
     *
     * @param string $message error message
     * @param string $filename log file name.
     * @param int|null $optin_campaign_id
     *
     * @return bool
     */
    public static function save_optin_error_log($message, $filename = 'error', $optin_campaign_id = null)
    {
        $error_log_folder = MAILOPTIN_OPTIN_ERROR_LOG;

        // does bugs folder exist? if NO, create it.
        if (!file_exists($error_log_folder)) {
            mkdir($error_log_folder, 0755);
        }

        $response = error_log($message . "\r\n", 3, "{$error_log_folder}{$filename}.log");

        self::send_optin_error_email($optin_campaign_id, $message);

        return $response;
    }

    public static function send_optin_error_email($optin_campaign_id, $error_message)
    {
        if (!isset($optin_campaign_id, $error_message)) return;

        $email = get_option('admin_email');

        sprintf(
            __("%s\n\n -- \n\nThis e-mail was sent by %s plugin on %s (%s)", 'mailoptin'), '[LEAD_DATA]', 'MailOptin', get_bloginfo('name'), site_url()
        );

        $optin_campaign_name = OptinCampaignsRepository::get_optin_campaign_name($optin_campaign_id);

        $subject = apply_filters('mo_optin_form_email_error_email_subject', sprintf(__('Warning! "%s" Optin Campaign Is Not Working', 'mailoptin'), $optin_campaign_name), $optin_campaign_id, $error_message);

        $message = apply_filters(
            'mo_optin_form_email_error_email_message',
            sprintf(
                __('The optin campaign "%s" is failing to convert leads due to the following error "%s". %6$s -- %6$sThis e-mail was sent by %s plugin on %s (%s)', 'mailoptin'),
                $optin_campaign_name,
                $error_message,
                'MailOptin',
                get_bloginfo('name'),
                site_url(),
                "\r\n\n"
            )
        );

        @wp_mail($email, $subject, $message);
    }

    /**
     * Split full name into first and last names.
     *
     * @param string $name
     *
     * @return array
     */
    public static function get_first_last_names($name)
    {
        $data = [];

        $names = explode(' ', $name);

        $data[] = isset($names[0]) ? trim($names[0]) : '';
        $data[] = isset($names[1]) ? trim($names[1]) : '';

        return $data;
    }

    /**
     * Get selected list ID of an email campaign.
     *
     * @param int $email_campaign_id
     *
     * @return int|string
     */
    public function get_email_campaign_list_id($email_campaign_id)
    {
        return EmailCampaignRepository::get_customizer_value($email_campaign_id, 'connection_email_list');
    }

    /**
     * Get campaign title of an email campaign.
     *
     * @param int $email_campaign_id
     *
     * @return string
     */
    public function get_email_campaign_campaign_title($email_campaign_id)
    {
        return EmailCampaignRepository::get_email_campaign_name($email_campaign_id);
    }
}