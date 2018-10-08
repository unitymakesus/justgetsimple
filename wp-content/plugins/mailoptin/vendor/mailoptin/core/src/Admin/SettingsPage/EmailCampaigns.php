<?php

namespace MailOptin\Core\Admin\SettingsPage;

// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

use MailOptin\Core\Repositories\EmailCampaignRepository;
use W3Guy\Custom_Settings_Page_Api;

class EmailCampaigns extends AbstractSettingsPage
{
    /**
     * @var Email_Campaign_List
     */
    protected $email_campaigns_instance;

    public function __construct()
    {
        add_action('plugins_loaded', function () {
            add_action('admin_menu', array($this, 'register_settings_page'));
        }, 20);

        add_filter('set-screen-option', array($this, 'set_screen'), 10, 3);

        add_filter('wp_cspa_active_tab_class', function ($active_tab_class, $tab_url, $current_page_url) {
            // hack to make email automation not active if other sub tab eg lead bank is active.
            if (strpos($current_page_url, MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE) !== false &&
                strpos($current_page_url, '&view') !== false &&
                $tab_url == MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE
            ) {
                $active_tab_class = null;
            }

            return $active_tab_class;
        }, 10, 3);

        add_action('post_submitbox_misc_actions', [$this, 'new_publish_post_exclude_metabox']);

        add_action('save_post', [$this, 'save_new_publish_post_exclude']);
    }

    public function register_settings_page()
    {
        $hook = add_submenu_page(
            MAILOPTIN_SETTINGS_SETTINGS_SLUG,
            __('Email Automations - MailOptin', 'mailoptin'),
            __('Email Automations', 'mailoptin'),
            'manage_options',
            MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_SLUG,
            array($this, 'settings_admin_page_callback')
        );

        add_action("load-$hook", array($this, 'screen_option'));

        do_action("mailoptin_register_email_campaign_settings_page", $hook);

    }

    /**
     * Save screen option.
     *
     * @param string $status
     * @param string $option
     * @param string $value
     *
     * @return mixed
     */
    public function set_screen($status, $option, $value)
    {
        return $value;
    }

    /**
     * Screen options
     */
    public function screen_option()
    {
        if (isset($_GET['page']) && $_GET['page'] == MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_SLUG && ! isset($_GET['view'])) {

            $option = 'per_page';
            $args   = array(
                'label'   => __('Email Automations', 'mailoptin'),
                'default' => 8,
                'option'  => 'email_campaign_per_page',
            );
            add_screen_option($option, $args);
            $this->email_campaigns_instance = Email_Campaign_List::get_instance();
        }
    }


    /**
     * Build the settings page structure. I.e tab, sidebar.
     */
    public function settings_admin_page_callback()
    {
        if ( ! empty($_GET['view']) && $_GET['view'] == 'add-new-email-automation') {
            AddEmailCampaign::get_instance()->settings_admin_page();
        } elseif ( ! empty($_GET['view']) && $_GET['view'] == MAILOPTIN_CAMPAIGN_LOG_SETTINGS_SLUG) {
            CampaignLog::get_instance()->settings_admin_page();
        } else {

            // Hook the OptinCampaign_List table to Custom_Settings_Page_Api main content filter.
            add_action('wp_cspa_main_content_area', array($this, 'wp_list_table'), 10, 2);
            add_action('wp_cspa_before_closing_header', [$this, 'add_new_email_campaign']);

            $instance = Custom_Settings_Page_Api::instance();
            $instance->option_name(MO_EMAIL_CAMPAIGNS_WP_OPTION_NAME);
            $instance->page_header(__('Email Automations', 'mailoptin'));
            $this->register_core_settings($instance, true);
            echo '<div class="mailoptin-data-listing">';
            $instance->build(true);
            echo '</div>';
        }
    }

    public function add_new_email_campaign()
    {
        $url = add_query_arg('view', 'add-new-email-automation', MAILOPTIN_EMAIL_CAMPAIGNS_SETTINGS_PAGE);
        echo "<a class=\"add-new-h2\" href=\"$url\">" . __('Add New', 'mailoptin') . '</a>';
    }


    /**
     * Callback to output content of OptinCampaign_List table.
     *
     * @param string $content
     * @param string $option_name settings Custom_Settings_Page_Api option name.
     *
     * @return string
     */
    public function wp_list_table($content, $option_name)
    {
        if ($option_name != MO_EMAIL_CAMPAIGNS_WP_OPTION_NAME) {
            return $content;
        }

        $this->email_campaigns_instance->prepare_items();

        $this->email_campaigns_instance->display();

        return ob_get_clean();
    }

    /**
     * @param \WP_Post $post
     */
    public function new_publish_post_exclude_metabox($post)
    {
        if (get_post_type($post) !== 'post') return;

        $npps = EmailCampaignRepository::get_by_email_campaign_type(
            EmailCampaignRepository::NEW_PUBLISH_POST
        );

        if (empty($npps)) return;

        $is_any_npp_active = false;
        foreach ($npps as $npp) {
            if (EmailCampaignRepository::is_campaign_active(absint($npp['id']))) {
                $is_any_npp_active = true;
                break;
            }
        }

        if ( ! $is_any_npp_active) return;

        ?>
        <div style="text-align: left;margin: 10px;">
            <?php
            printf(
                __('Disable %sMailOptin new post notification%s for this post.', 'mailoptin'),
                '<strong>',
                '</strong>'
            );

            wp_nonce_field('mo-disable-npp-nonce', 'mo-disable-npp-nonce');
            $val = get_post_meta($post->ID, '_mo_disable_npp', true);

            ?>
            <input type="hidden" name="mo-disable-npp" value="no">
            <input name="mo-disable-npp" id="mo-disable-npp" type="checkbox" class="tgl tgl-light" value="yes" <?php checked($val, 'yes'); ?>>
            <label for="mo-disable-npp" style="display:inline-block;" class="tgl-btn"></label>
        </div>
        <?php

    }

    public function save_new_publish_post_exclude($post_id)
    {
        // Check if our nonce is set.
        if ( ! isset($_POST['mo-disable-npp-nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($_POST['mo-disable-npp-nonce'], 'mo-disable-npp-nonce')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
            if ( ! current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {

            if ( ! current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        // Make sure that it is set.
        if ( ! isset($_POST['mo-disable-npp'])) {
            return;
        }

        // Sanitize user input.
        $val = sanitize_text_field($_POST['mo-disable-npp']);

        // Update the meta field in the database.
        update_post_meta($post_id, '_mo_disable_npp', $val);
    }

    /**
     * @return EmailCampaigns
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