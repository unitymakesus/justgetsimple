<?php

namespace MailOptin\Core\OptinForms;

use MailOptin\Core\Admin\Customizer\OptinForm\OptinFormFactory;
use MailOptin\Core\Repositories\OptinCampaignsRepository as Repository;


class InPost
{
    use PageTargetingRuleTrait, UserTargetingRuleTrait;

    public function __construct()
    {
        add_filter('the_content', [$this, 'insert_optin']);
    }

    public function insert_optin($content)
    {
        // needed to prevent the optin from showing on post excerpt (on homepage / post listing)
        if (is_front_page() || !is_singular()) return $content;

        $optin_ids = get_transient('mo_get_optin_ids_inpost_display');

        if ($optin_ids === false) {
            $optin_ids = Repository::get_inpost_optin_ids();
            set_transient('mo_get_optin_ids_inpost_display', $optin_ids, HOUR_IN_SECONDS);
        }

        foreach ($optin_ids as $id) {

            $id = absint($id);

            do_action('mailoptin_before_inpost_optin_display_determinant', $id, $optin_ids);

            // if it is a split test variant, skip
            if (Repository::is_split_test_variant($id)) continue;

            // if optin is not enabled, pass.
            if (!Repository::is_activated($id)) continue;

            $id = Repository::choose_split_test_variant($id);

            $optin_position = Repository::get_merged_customizer_value($id, 'inpost_form_optin_position');

            // if optin global exit/interaction and success cookie result fails, move to next.
            if (!Repository::global_cookie_check_result($id)) continue;

            if (!$this->user_targeting_rule_checker($id)) {
                continue;
            }

            if (!$this->page_level_targeting_rule_checker($id)) {
                continue;
            }

            $optin_form = OptinFormFactory::build($id);

            if ('before_content' == $optin_position) {
                $content = $optin_form . $content;
            } else {
                $content .= $optin_form;
            }

            do_action('mailoptin_after_inpost_optin_display_determinant', $id, $optin_ids);
        }


        return $content;
    }

    /**
     * @return InPost
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

}