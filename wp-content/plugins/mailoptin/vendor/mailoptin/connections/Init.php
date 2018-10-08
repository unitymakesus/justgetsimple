<?php

namespace MailOptin\Connections;

class Init
{
    public static function init()
    {
        \MailOptin\RegisteredUsersConnect\Connect::get_instance(); // should always come first before any connect.
        \MailOptin\MailChimpConnect\Connect::get_instance();
        \MailOptin\AweberConnect\Connect::get_instance();
        \MailOptin\CampaignMonitorConnect\Connect::get_instance();
        \MailOptin\SendyConnect\Connect::get_instance();
        \MailOptin\DripConnect\Connect::get_instance();
        \MailOptin\SendlaneConnect\Connect::get_instance();
        \MailOptin\ConvertKitConnect\Connect::get_instance();
        \MailOptin\ActiveCampaignConnect\Connect::get_instance();
        \MailOptin\CtctConnect\Connect::get_instance();
        \MailOptin\MailerliteConnect\Connect::get_instance();
        \MailOptin\EmailOctopusConnect\Connect::get_instance();
        \MailOptin\SendinblueConnect\Connect::get_instance();
        \MailOptin\MailPoetConnect\Connect::get_instance();
        \MailOptin\GetResponseConnect\Connect::get_instance();
        \MailOptin\KlaviyoConnect\Connect::get_instance();
        \MailOptin\ConvertFoxConnect\Connect::get_instance();
        \MailOptin\ElementorConnect\Connect::get_instance();
        GoogleAnalytics::get_instance();
    }
}