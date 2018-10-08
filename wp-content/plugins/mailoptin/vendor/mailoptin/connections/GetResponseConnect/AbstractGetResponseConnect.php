<?php

namespace MailOptin\GetResponseConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractGetResponseConnect extends AbstractConnect
{
    /** @var \MailOptin\Core\PluginSettings\Settings */
    protected $plugin_settings;

    /** @var \MailOptin\Core\PluginSettings\Connections */
    protected $connections_settings;

    public function __construct()
    {
        $this->plugin_settings = Settings::instance();
        $this->connections_settings = Connections::instance();

        parent::__construct();
    }

    /**
     * Is GetResponse successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['getresponse_api_key']);
    }

    /**
     * Return instance of getresponse API class.
     *
     * @throws \Exception
     *
     * @return GetResponseAPI3
     */
    public function getresponse_instance()
    {
        $api_key = $this->connections_settings->getresponse_api_key();
        $getresponse_is_360 = $this->connections_settings->getresponse_is_360();
        $getresponse360_registered_domain = $this->connections_settings->getresponse360_registered_domain();
        $getresponse360_country = $this->connections_settings->getresponse360_country();

        if (empty($api_key)) {
            throw new \Exception(__('GetResponse API key not found.', 'mailoptin'));
        }

        $getresponse = new GetResponseAPI3($api_key);

        if ($getresponse_is_360 == 'true' && isset($getresponse360_country) && $getresponse360_country != 'none') {
            $getresponse->enterprise_domain = $getresponse360_registered_domain;

            $getresponse->api_url = 'https://api3.getresponse360.com/v3'; //default

            if ($getresponse360_country == 'poland') {
                $getresponse->api_url = 'https://api3.getresponse360.pl/v3'; //for PL domains
            }
        }

        return $getresponse;
    }
}