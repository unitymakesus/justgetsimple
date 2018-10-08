<?php

namespace MailOptin\Core;

use Html2Text\Html2Text;
use MailOptin\Core\Logging\CampaignLogPersistence;
use MailOptin\Core\Logging\CampaignLogRepository;
use MailOptin\Core\PluginSettings\Settings;
use W3Guy\Custom_Settings_Page_Api;

function campaign_repository()
{
    return CampaignLogRepository::instance();
}

/**
 *
 * @return CampaignLogPersistence
 */
function persistence()
{
    return CampaignLogPersistence::instance();
}

/**
 * @param array $data
 *
 * @return Logging\CampaignLog
 */
function campaign($data)
{
    return (new Logging\CampaignLog($data));
}

/**
 * @return Custom_Settings_Page_Api
 */
function custom_settings_page_api()
{
    return Custom_Settings_Page_Api::instance();
}

/**
 * Convert HTML to plain text
 *
 * @param string $content
 *
 * @return string string
 */
function html_to_text($content)
{
    // #^\[.+\](.+Bwebversion.+)# removes the webversion link if found as the first plain-text content.
    // this is done to make the email preview n email clients not show the tags as
    return preg_replace('#^\[.+\](.+Bwebversion.+)#', '', Html2Text::convert($content));

}

/**
 * http://stackoverflow.com/questions/965235/how-can-i-truncate-a-string-to-the-first-20-words-in-php
 *
 * @param string $text
 * @param int $limit
 *
 * @return string
 */
function limit_text($text, $limit = 150)
{
    $limit = !is_int($limit) || 0 === $limit ? 150 : $limit;

    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . apply_filters('maioptin_limit_text_ellipsis', '. . .');
    }

    return $text;
}

/**
 * Array of web safe fonts.
 *
 * @return array
 */
function web_safe_font()
{
    return ['Helvetica', 'Helvetica Neue', 'Arial', 'Times New Roman', 'Lucida Sans', 'Verdana', 'Tahoma', 'Cambria', 'Trebuchet MS', 'Segoe UI'];
}

/**
 * Helper function to recursively sanitize POSTed data.
 *
 * @param $data
 *
 * @return string|array
 */
function sanitize_data($data)
{
    if (is_string($data)) {
        return sanitize_text_field($data);
    }
    $sanitized_data = array();
    foreach ($data as $key => $value) {
        if (is_array($data[$key])) {
            $sanitized_data[$key] = sanitize_data($data[$key]);
        } else {
            $sanitized_data[$key] = sanitize_text_field($data[$key]);
        }
    }

    return $sanitized_data;
}

/**
 * WordPress blog/website title.
 *
 * @return string
 */
function site_title()
{
    return get_bloginfo('name');
}

/**
 * Return currently viewed page url with query string.
 *
 * @return string
 */
function current_url_with_query_string()
{
    $protocol = 'http://';

    if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1))
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    ) {
        $protocol = 'https://';
    }

    $url = $protocol . $_SERVER['HTTP_HOST'];

    $url .= $_SERVER['REQUEST_URI'];

    return esc_url_raw($url);
}

/**
 * Return array of countries. Typically for consumption by select dropdown.
 *
 * @return array
 */
function countries_array()
{
    return apply_filters('mailoptin_countries_array', include(dirname(__FILE__) . '/countries.php'));
}

/**
 * Get country name from code.
 *
 * @param string $code
 *
 * @return mixed|string
 */
function country_code_to_name($code)
{
    if (empty($code)) return '';

    $countries_array = countries_array();

    return $countries_array[$code];
}

function plugin_settings()
{
    return Settings::instance();
}

function get_ip_address()
{
    $ip = '127.0.0.1';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Fix potential CSV returned from $_SERVER variables
    $ip_array = array_map('trim', explode(',', $ip));

    return $ip_array[0];
}

/**
 * Check if an admin settings page is MailOptin'
 *
 * @return bool
 */
function is_mailoptin_admin_page()
{
    $mo_admin_pages_slug = array(
        MAILOPTIN_OPTIN_CAMPAIGNS_SETTINGS_SLUG,
        MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_SLUG,
        MAILOPTIN_CAMPAIGN_LOG_SETTINGS_SLUG,
        MAILOPTIN_CONNECTIONS_SETTINGS_SLUG,
        MAILOPTIN_SETTINGS_SETTINGS_SLUG,
        MAILOPTIN_ADVANCE_ANALYTICS_SETTINGS_SLUG,
        'mailoptin-premium-upgrade'
    );

    return (isset($_GET['page']) && in_array($_GET['page'], $mo_admin_pages_slug));
}