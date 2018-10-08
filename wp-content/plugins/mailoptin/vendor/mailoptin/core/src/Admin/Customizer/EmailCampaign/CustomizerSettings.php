<?php

namespace MailOptin\Core\Admin\Customizer\EmailCampaign;

class CustomizerSettings extends AbstractCustomizer
{
    /** @var \WP_Customize_Manager */
    private $wp_customize;

    /** @var Customizer */
    private $customizerClassInstance;

    /** @var string DB option name prefix */
    private $option_prefix;

    /**
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param Customizer $customizerClassInstance
     */
    public function __construct($wp_customize, $option_prefix, $customizerClassInstance)
    {
        $this->wp_customize            = $wp_customize;
        $this->customizerClassInstance = $customizerClassInstance;
        $this->option_prefix           = $option_prefix;

        parent::__construct($customizerClassInstance->email_campaign_id);
    }

    /**
     * Customize setting for email campaign setup.
     */
    public function campaign_settings()
    {
        $email_campaign_settings_args = apply_filters("mailoptin_email_campaign_customizer_page_settings", array(
                'settings_controls_tab_toggle'   => array(
                    'default'   => 'general',
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'email_campaign_title'           => array(
                    'default'   => $this->customizer_defaults['email_campaign_title'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'email_campaign_subject'         => array(
                    'default'   => $this->customizer_defaults['email_campaign_subject'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'item_number'                    => array(
                    'default'           => $this->customizer_defaults['item_number'],
                    'type'              => 'option',
                    'transport'         => 'refresh',
                    'sanitize_callback' => 'absint',
                ),
                'post_content_length'            => array(
                    'default'           => $this->customizer_defaults['post_content_length'],
                    'type'              => 'option',
                    'transport'         => 'refresh',
                    'sanitize_callback' => 'absint',
                ),
                'post_categories'                => array(
                    'default'   => $this->customizer_defaults['post_categories'],
                    'type'      => 'option',
                    'transport' => 'postMessage'
                ),
                'post_tags'                      => array(
                    'default'   => $this->customizer_defaults['post_tags'],
                    'type'      => 'option',
                    'transport' => 'postMessage'
                ),
                'recipient_header'             => array(
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'connection_service'             => array(
                    'default'   => $this->customizer_defaults['connection_service'],
                    'type'      => 'option',
                    'transport' => 'refresh',
                ),
                'connection_email_list'          => array(
                    'default'   => $this->customizer_defaults['connection_email_list'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_header'                => array(
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'send_immediately'               => array(
                    'default'   => $this->customizer_defaults['send_immediately'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_type'                  => array(
                    'default'   => $this->customizer_defaults['schedule_type'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_digit'                 => array(
                    'default'           => $this->customizer_defaults['schedule_digit'],
                    'type'              => 'option',
                    'transport'         => 'postMessage',
                    'sanitize_callback' => 'absint',
                ),
                'schedule_interval'              => array(
                    'default'   => $this->customizer_defaults['schedule_interval'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_time'                  => array(
                    'default'   => $this->customizer_defaults['schedule_time'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_day'                   => array(
                    'default'   => $this->customizer_defaults['schedule_day'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'schedule_month_date'            => array(
                    'default'   => $this->customizer_defaults['schedule_month_date'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'email_campaign_settings_notice' => array(
                    'default'   => false,
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'email_campaign_settings_notice2' => array(
                    'default'   => false,
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'ajax_nonce'                     => array(
                    'default'   => wp_create_nonce('customizer-fetch-email-list'),
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
            )
        );

        foreach ($email_campaign_settings_args as $id => $args) {
            $this->wp_customize->add_setting(new EC_Customizer_Setting(
                    $this->wp_customize,
                    $this->option_prefix . '[' . $id . ']',
                    $args
                )
            );
        }
    }


    /**
     * Customize setting for all template page controls.
     */
    public function page_settings()
    {
        $page_settings_args = apply_filters("mailoptin_email_campaign_customizer_page_settings", array(
                'page_background_color'     => array(
                    'default'           => $this->customizer_defaults['page_background_color'],
                    'type'              => 'option',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'transport'         => 'postMessage',
                ),
                'custom_css_upgrade_notice' => array(
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
            )
        );

        foreach ($page_settings_args as $id => $args) {
            $this->wp_customize->add_setting($this->option_prefix . '[' . $id . ']', $args);
        }
    }

    /**
     * Customize setting for all template header controls.
     */
    public function header_settings()
    {
        $header_settings_args = apply_filters("mailoptin_email_campaign_customizer_header_settings", array(
                'header_controls_tab_toggle'    => array(
                    'default'   => 'general',
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'header_removal'                => array(
                    'default'   => $this->customizer_defaults['header_removal'],
                    'type'      => 'option',
                    'transport' => 'refresh',
                ),
                'header_logo'                   => array(
                    'default' => $this->customizer_defaults['header_logo'],
                    'type'    => 'option',
                ),
                'header_background_color'       => array(
                    'default'   => $this->customizer_defaults['header_background_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'header_text_color'             => array(
                    'default'   => $this->customizer_defaults['header_text_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'header_text'                   => array(
                    'default'   => $this->customizer_defaults['header_text'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'header_web_version_link_label' => array(
                    'default'   => $this->customizer_defaults['header_web_version_link_label'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'header_web_version_link_color' => array(
                    'default'   => $this->customizer_defaults['header_web_version_link_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
            )
        );

        foreach ($header_settings_args as $id => $args) {
            $this->wp_customize->add_setting($this->option_prefix . '[' . $id . ']', $args);
        }
    }


    /**
     * Customize setting for all template content controls.
     */
    public function content_settings()
    {
        $content_settings_args = apply_filters("mailoptin_email_campaign_customizer_content_settings", array(
                'content_controls_tab_toggle'              => array(
                    'default'   => 'general',
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_before_main_content'              => array(
                    'default'   => $this->customizer_defaults['content_before_main_content'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'default_image_url'                        => array(
                    'default'   => $this->customizer_defaults['default_image_url'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_background_color'                 => array(
                    'default'   => $this->customizer_defaults['content_background_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_text_color'                       => array(
                    'default'   => $this->customizer_defaults['content_text_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_alignment'                        => array(
                    'default'   => $this->customizer_defaults['content_alignment'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_remove_feature_image'             => array(
                    'default'   => $this->customizer_defaults['content_remove_feature_image'],
                    'type'      => 'option',
                    'transport' => 'refresh',
                ),
                'content_remove_ellipsis_button'           => array(
                    'default'   => $this->customizer_defaults['content_remove_ellipsis_button'],
                    'type'      => 'option',
                    'transport' => 'refresh',
                ),
                'content_ellipsis_button_alignment'        => array(
                    'default'   => $this->customizer_defaults['content_ellipsis_button_alignment'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_ellipsis_button_background_color' => array(
                    'default'   => $this->customizer_defaults['content_ellipsis_button_background_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_ellipsis_button_text_color'       => array(
                    'default'   => $this->customizer_defaults['content_ellipsis_button_text_color'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_title_font_size'                  => array(
                    'default'   => $this->customizer_defaults['content_title_font_size'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_body_font_size'                   => array(
                    'default'   => $this->customizer_defaults['content_body_font_size'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
                'content_ellipsis_button_label'            => array(
                    'default'   => $this->customizer_defaults['content_ellipsis_button_label'],
                    'type'      => 'option',
                    'transport' => 'postMessage',
                ),
            )
        );

        foreach ($content_settings_args as $id => $args) {
            $this->wp_customize->add_setting($this->option_prefix . '[' . $id . ']', $args);
        }
    }


    /**
     * Customize setting for all template footer controls.
     */
    public function footer_settings()
    {
        $footer_settings_args = apply_filters("mailoptin_email_campaign_customizer_footer_settings", array(
            'footer_controls_tab_toggle'    => array(
                'default'   => 'general',
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_removal'                => array(
                'default'   => $this->customizer_defaults['footer_removal'],
                'type'      => 'option',
                'transport' => 'refresh',
            ),
            'footer_background_color'       => array(
                'default'   => $this->customizer_defaults['footer_background_color'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_text_color'             => array(
                'default'   => $this->customizer_defaults['footer_text_color'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_font_size'              => array(
                'default'   => apply_filters('footer_font_size', '12'),
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_copyright_line'         => array(
                'default'   => $this->customizer_defaults['footer_copyright_line'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_unsubscribe_line'       => array(
                'default'   => $this->customizer_defaults['footer_unsubscribe_line'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_unsubscribe_link_label' => array(
                'default'   => $this->customizer_defaults['footer_unsubscribe_link_label'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_unsubscribe_link_color' => array(
                'default'   => $this->customizer_defaults['footer_unsubscribe_link_color'],
                'type'      => 'option',
                'transport' => 'postMessage',
            ),
            'footer_description'            => array(
                'default'   => $this->customizer_defaults['footer_description'],
                'type'      => 'option',
                'transport' => 'refresh',
            )
        ));

        foreach ($footer_settings_args as $id => $args) {
            $this->wp_customize->add_setting($this->option_prefix . '[' . $id . ']', $args);
        }
    }
}