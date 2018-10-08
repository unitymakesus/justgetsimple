<?php

namespace MailOptin\SendinblueConnect;

use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\PluginSettings\Connections;
use MailOptin\Core\PluginSettings\Settings;

class AbstractSendinblueConnect extends AbstractConnect
{
    /** @var \MailOptin\Core\PluginSettings\Settings */
    protected $plugin_settings;

    /** @var \MailOptin\Core\PluginSettings\Connections */
    protected $connections_settings;

    protected $api_key;

    public function __construct()
    {
        $this->plugin_settings = Settings::instance();
        $this->connections_settings = Connections::instance();
        $this->api_key = $this->connections_settings->sendinblue_api_key();

        parent::__construct();
    }

    /**
     * Is Constant Contact successfully connected to?
     *
     * @return bool
     */
    public static function is_connected()
    {
        $db_options = get_option(MAILOPTIN_CONNECTIONS_DB_OPTION_NAME);

        return !empty($db_options['sendinblue_api_key']);
    }


    /**
     * Return instance of convertkit API class.
     *
     * @throws \Exception
     *
     * @return APIClass
     */
    public function sendinblue_instance()
    {
        $api_key = $this->connections_settings->sendinblue_api_key();

        if (empty($api_key)) {
            throw new \Exception(__('SendinBlue API Key not found.', 'mailoptin'));
        }

        return new APIClass($api_key);
    }
}