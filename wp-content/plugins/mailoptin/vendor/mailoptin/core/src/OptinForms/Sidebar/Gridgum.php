<?php

namespace MailOptin\Core\OptinForms\Sidebar;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Tinymce_Control;
use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Toggle_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\CustomizerSettings;
use MailOptin\Core\OptinForms\AbstractOptinTheme;

class Gridgum extends AbstractOptinTheme
{
    public $optin_form_name = 'Gridgum';

    public function __construct($optin_campaign_id)
    {
        $this->init_config_filters([

                // -- default for design sections -- //
                [
                    'name'        => 'mo_optin_form_background_color_default',
                    'value'       => '#ffffff',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_border_color_default',
                    'value'       => '#cccccc',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                // -- default for headline sections -- //
                [
                    'name'        => 'mo_optin_form_headline_default',
                    'value'       => __("Subscribe To Newsletter", 'mailoptin'),
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_headline_font_color_default',
                    'value'       => '#4b4646',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_headline_font_default',
                    'value'       => 'Open+Sans',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                // -- default for description sections -- //
                [
                    'name'        => 'mo_optin_form_description_font_default',
                    'value'       => 'Open+Sans',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_description_default',
                    'value'       => $this->_description_content(),
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_description_font_color_default',
                    'value'       => '#ffffff',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                // -- default for fields sections -- //
                [
                    'name'        => 'mo_optin_form_name_field_color_default',
                    'value'       => '#181818',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],


                [
                    'name'        => 'mo_optin_form_name_field_background_default',
                    'value'       => '#ffffff',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],
                [
                    'name'        => 'mo_optin_form_email_field_color_default',
                    'value'       => '#181818',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_email_field_background_default',
                    'value'       => '#ffffff',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_submit_button_color_default',
                    'value'       => '#ffffff',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_submit_button_background_default',
                    'value'       => '#0073b7',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_submit_button_font_default',
                    'value'       => 'Open+Sans',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_name_field_font_default',
                    'value'       => 'Palatino Linotype, Book Antiqua, serif',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_email_field_font_default',
                    'value'       => 'Palatino Linotype, Book Antiqua, serif',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                // -- default for note sections -- //
                [
                    'name'        => 'mo_optin_form_note_font_color_default',
                    'value'       => '#000000',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_note_default',
                    'value'       => __('We promise not to spam you. Unsubscribe at any time.', 'mailoptin'),
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ],

                [
                    'name'        => 'mo_optin_form_note_font_default',
                    'value'       => 'Open+Sans',
                    'optin_class' => 'Gridgum',
                    'optin_type'  => 'sidebar'
                ]
            ]
        );

        add_filter('mo_optin_customizer_disable_description_section', '__return_true');
        add_filter('mailoptin_tinymce_customizer_control_count', function ($count) {
            return $count - 1;
        });

        add_filter('mailoptin_customizer_optin_campaign_MailChimpConnect_user_input_field_color', function () {
            return '#000000';
        });

        add_action('customize_preview_init', function () {
            add_action('wp_footer', [$this, 'customizer_preview_js']);
        });

        parent::__construct($optin_campaign_id);
    }

    public function features_support()
    {
        return [$this->cta_button];
    }

    /**
     * @param mixed $settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_design_settings($settings, $CustomizerSettingsInstance)
    {
        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_design_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $controls;
    }

    /**
     * @param mixed $settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_headline_settings($settings, $CustomizerSettingsInstance)
    {
        $settings['mini_headline'] = array(
            'default'           => "DON'T MISS OUT!",
            'type'              => 'option',
            'transport'         => 'refresh',
            'sanitize_callback' => array($CustomizerSettingsInstance, '_remove_paragraph_from_headline'),
        );

        $settings['hide_mini_headline'] = array(
            'default'   => false,
            'type'      => 'option',
            'transport' => 'postMessage'
        );

        $settings['mini_headline_font_color'] = array(
            'default'   => '#46ca9b',
            'type'      => 'option',
            'transport' => 'postMessage'
        );

        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_headline_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        add_filter('mailoptin_tinymce_customizer_control_count', function ($count) {
            return ++$count;
        });

        $controls['mini_headline'] = new WP_Customize_Tinymce_Control(
            $wp_customize,
            $option_prefix . '[mini_headline]',
            apply_filters('mo_optin_form_customizer_mini_headline_args', array(
                    'label'         => __('Mini Headline', 'mailoptin'),
                    'section'       => $customizerClassInstance->headline_section_id,
                    'settings'      => $option_prefix . '[mini_headline]',
                    'editor_id'     => 'mini_headline',
                    'editor_height' => 50,
                    'priority'      => 4
                )
            )
        );

        $controls['hide_mini_headline'] = new WP_Customize_Toggle_Control(
            $wp_customize,
            $option_prefix . '[hide_mini_headline]',
            apply_filters('mo_optin_form_customizer_hide_mini_headline_args', array(
                    'label'    => __('Hide Mini Headline', 'mailoptin'),
                    'section'  => $customizerClassInstance->headline_section_id,
                    'settings' => $option_prefix . '[hide_mini_headline]',
                    'type'     => 'light',
                    'priority' => 2,
                )
            )
        );

        $controls['mini_headline_font_color'] = new \WP_Customize_Color_Control(
            $wp_customize,
            $option_prefix . '[mini_headline_font_color]',
            apply_filters('mo_optin_form_customizer_headline_mini_headline_font_color_args', array(
                    'label'    => __('Mini Headline Color', 'mailoptin'),
                    'section'  => $customizerClassInstance->headline_section_id,
                    'settings' => $option_prefix . '[mini_headline_font_color]',
                    'priority' => 3
                )
            )
        );

        return $controls;
    }

    /**
     * @param mixed $settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_description_settings($settings, $CustomizerSettingsInstance)
    {
        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_description_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $controls;
    }

    /**
     * @param mixed $settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_note_settings($settings, $CustomizerSettingsInstance)
    {
        return $settings;
    }

    /**
     * @param array $controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_note_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $controls;
    }


    /**
     * @param mixed $fields_settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_fields_settings($fields_settings, $CustomizerSettingsInstance)
    {
        return $fields_settings;
    }

    /**
     * @param array $fields_controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_fields_controls($fields_controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $fields_controls;
    }

    /**
     * @param mixed $configuration_settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_configuration_settings($configuration_settings, $CustomizerSettingsInstance)
    {
        return $configuration_settings;
    }


    /**
     * @param array $configuration_controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_configuration_controls($configuration_controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $configuration_controls;
    }

    /**
     * @param mixed $output_settings
     * @param CustomizerSettings $CustomizerSettingsInstance
     *
     * @return mixed
     */
    public function customizer_output_settings($output_settings, $CustomizerSettingsInstance)
    {
        return $output_settings;
    }


    /**
     * @param array $output_controls
     * @param \WP_Customize_Manager $wp_customize
     * @param string $option_prefix
     * @param \MailOptin\Core\Admin\Customizer\OptinForm\Customizer $customizerClassInstance
     *
     * @return array
     */
    public function customizer_output_controls($output_controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        return $output_controls;
    }

    /**
     * Default description content.
     *
     * @return string
     */
    private function _description_content()
    {
        return __('Be the first to get latest updates and exclusive content straight to your email inbox.', 'mailoptin');
    }

    /**
     * Fulfil interface contract.
     */
    public function optin_script()
    {
    }

    public function customizer_preview_js()
    {
        ?>
        <script type="text/javascript">
            (function ($) {
                $(function () {
                    wp.customize(mailoptin_optin_option_prefix + '[' + mailoptin_optin_campaign_id + '][mini_headline_font_color]', function (value) {
                        value.bind(function (to) {
                            $('.gridgum_header2').css('color', to);
                        });
                    });

                    wp.customize(mailoptin_optin_option_prefix + '[' + mailoptin_optin_campaign_id + '][hide_mini_headline]', function (value) {
                        value.bind(function (to) {
                            $('.gridgum_header2').toggle(!to);
                        });
                    });
                })
            })(jQuery)
        </script>
        <?php
    }

    /**
     * Template body.
     *
     * @return string
     */
    public function optin_form()
    {
        $mini_header = $this->get_customizer_value('mini_headline', __("Don't miss out!", 'mailoptin'));

        $mini_header_block = '<div class="gridgum_header2">' . $mini_header . '</div>';

        return <<<HTML
        [mo-optin-form-wrapper class="gridgum_container"]
                <div class="gridgum_body">
                    <div class="gridgum_body-inner">
                        $mini_header_block
                        [mo-optin-form-headline tag="div" class="gridgum_headline"]
                            <div class="gridgum_body-form">
                            [mo-optin-form-fields-wrapper]
                            [mo-optin-form-name-field class="gridgum_input_field"]
                            [mo-optin-form-email-field class="gridgum_input_field"]
                            [mo-mailchimp-interests]
                            [mo-optin-form-submit-button class="gridgum_submit_button"]
                            [/mo-optin-form-fields-wrapper]
    [mo-optin-form-cta-button class="gridgum_submit_button"]
                            </div>
                       [mo-optin-form-note class="gridgum_note"]
                       [mo-optin-form-error]
                    </div>
            </div>
[/mo-optin-form-wrapper]

HTML;
    }

    /**
     * Template CSS styling.
     *
     * @return string
     */
    public function optin_form_css()
    {
        $optin_css_id = $this->optin_css_id;

        $mini_headline_font_color = $this->get_customizer_value('mini_headline_font_color', '#46ca9b');

        $is_mini_hadline_display = '';
        if ($this->get_customizer_value('hide_mini_headline', false)) {
            $is_mini_hadline_display = 'display:none;';
        }

        return <<<CSS
div#$optin_css_id.gridgum_container * {
         padding: 0px;
         margin: 0px;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
     }

div#$optin_css_id.gridgum_container {
         background: #ffffff;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
         border: 3px solid #cccccc;
         margin: 10px auto;
         max-width: 400px;
     }

div#$optin_css_id.gridgum_container .mo-optin-error {
         display: none;
         color: #ff0000;
         text-align: center;
         padding: 5px;
         font-size: 14px;
     }

div#$optin_css_id.gridgum_container .gridgum_body {
         width: 100%;
         margin: 10px auto;
     }

div#$optin_css_id.gridgum_container .gridgum_body-inner {
         padding-left: 20px;
         padding-top: 20px;
         padding-right: 20px;
     }

div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_header2 {
         text-transform: uppercase;
         font-weight: 900;
         padding-bottom: 10px;
         font-size: 12px;
         color: $mini_headline_font_color;
         text-align: center;
         $is_mini_hadline_display
     }

div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_header2,
     div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_headline,
          div#$optin_css_id.gridgum_container .gridgum_body-form .gridgum_input_field,
               div#$optin_css_id.gridgum_container .gridgum_submit_button, .gridgum_note {
                        font-family: "Open Sans", sans-serif;
                    }

div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_headline {
         padding-bottom: 10px;
         color: #4b4646;
         font-size: 20px;
         text-align: center;
         text-transform: capitalize;
         display: block;
         border: 0;
         line-height: normal;
     }

div#$optin_css_id.gridgum_container .gridgum_body-form input.gridgum_input_field {
         width: 100%;
         padding: 10px 0px;
         margin: 0;
         margin-bottom: 20px;
         border: 0px;
         border-bottom: 2px solid #ccc;
         font-weight: normal;
         color: #181818;
         font-size: 15px;
         background-color: #ffffff;
     }

div#$optin_css_id.gridgum_container .gridgum_body-form input.gridgum_input_field:focus,
     div#$optin_css_id.gridgum_container .gridgum_body-form input.gridgum_submit_button:focus {
              outline: 0;
          }

div#$optin_css_id.gridgum_container input[type="submit"].gridgum_submit_button {
         padding: 10px;
         font-size: 15px;
         border-radius: 3px;
         border: 0px;
         background: #46ca9b;
         text-transform: uppercase;
         color: #fff;
         font-weight: 600;
         width: 100%;
     }

div#$optin_css_id.gridgum_container .gridgum_note {
         padding-top: 10px;
         color: #777;
         text-align: center;
         font-size: 12px;
         font-style: italic;
         display: block;
         border: 0;
         line-height: normal;
     }

@media (min-width: 700px) {

    div#$optin_css_id.gridgum_container .gridgum_content-overlay .gridgum_header2, .gridgum_content-overlay .gridgum_description {
             color: #fff;
             display: block;
             border: 0;
             line-height: normal;
         }
}

@media (min-width: 980px) {
    div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_header2 {
             font-size: 15px;
             text-align: center;
         }
    div#$optin_css_id.gridgum_container .gridgum_body-inner .gridgum_headline {
             font-size: 22px;
         }
}
CSS;

    }
}