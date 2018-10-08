<?php

namespace MailOptin\Core\OptinForms\Sidebar;

use MailOptin\Core\Admin\Customizer\EmailCampaign\CustomizerSettings;
use MailOptin\Core\OptinForms\AbstractOptinTheme;

class Lupin extends AbstractOptinTheme
{
    public $optin_form_name = 'Lupin';

    public function __construct($optin_campaign_id)
    {
        $this->init_config_filters([
                // -- default for design sections -- //
                [
                    'name' => 'mo_optin_form_background_color_default',
                    'value' => '#1998d6',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_email_field_placeholder_default',
                    'value' => __("Enter your email here...", 'mailoptin'),
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                // -- default for headline sections -- //
                [
                    'name' => 'mo_optin_form_headline_default',
                    'value' => __("Education Blogging Ideas!", 'mailoptin'),
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_headline_font_color_default',
                    'value' => '#ffffff',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_headline_font_default',
                    'value' => 'Open+Sans',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                // -- default for description sections -- //
                [
                    'name' => 'mo_optin_form_description_font_default',
                    'value' => 'Open+Sans',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_description_default',
                    'value' => $this->_description_content(),
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_description_font_color_default',
                    'value' => '#ffffff',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                // -- default for fields sections -- //
                [
                    'name' => 'mo_optin_form_name_field_color_default',
                    'value' => '#555555',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_email_field_color_default',
                    'value' => '#555555',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_submit_button_color_default',
                    'value' => '#ffffff',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_submit_button_background_default',
                    'value' => '#FEC32D',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_submit_button_font_default',
                    'value' => 'Open+Sans',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_name_field_font_default',
                    'value' => 'Palatino Linotype, Book Antiqua, serif',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_email_field_font_default',
                    'value' => 'Palatino Linotype, Book Antiqua, serif',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                // -- default for note sections -- //
                [
                    'name' => 'mo_optin_form_note_font_color_default',
                    'value' => '#ffffff',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_note_default',
                    'value' => '<em>' . __('We promise not to spam you. You can unsubscribe at any time.', 'mailoptin') . '</em>',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ],

                [
                    'name' => 'mo_optin_form_note_font_default',
                    'value' => 'Open+Sans',
                    'optin_class' => 'Lupin',
                    'optin_type' => 'sidebar'
                ]
            ]
        );

        add_filter('mailoptin_customizer_optin_campaign_MailChimpConnect_user_input_field_color', function () {
            return '#ffffff';
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
        return __('Receive top education news, lesson ideas, teaching tips and more!', 'mailoptin');
    }

    /**
     * Fulfil interface contract.
     */
    public function optin_script()
    {
    }

    /**
     * Template body.
     *
     * @return string
     */
    public function optin_form()
    {
        return <<<HTML
[mo-optin-form-wrapper class="lupinSidebar-container"]
    [mo-optin-form-headline class="lupinSidebar-heading"]
    [mo-optin-form-description class="lupinSidebar-description"]
    <div class="lupinSidebar-form">
    [mo-optin-form-error]
    [mo-optin-form-fields-wrapper]
    [mo-optin-form-name-field class="lupinSidebar-input"]
    [mo-optin-form-email-field class="lupinSidebar-input"]
    [mo-mailchimp-interests]
    [mo-optin-form-submit-button class="lupinSidebar-submit"]
    [/mo-optin-form-fields-wrapper]
    [mo-optin-form-cta-button]
    </div>
    [mo-optin-form-note class="lupinSidebar-note"]
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
        return <<<CSS
div#$optin_css_id.lupinSidebar-container {
         background: #1998D6;
         -webkit-border-radius: 5px;
         -moz-border-radius: 5px;
         border-radius: 5px;
         border: 3px solid #1998d6;
         max-width: 350px;
         padding: 10px 20px 10px;
         font-family: 'Open Sans', arial, sans-serif;
         color: #fff;
         text-align: center;
         margin: 0 auto;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
     }

div#$optin_css_id.lupinSidebar-container h2.lupinSidebar-heading {
         font-family: 'Open Sans', arial, sans-serif;
         font-size: 20px;
         color: #fff;
         line-height: 50px;
     }

div#$optin_css_id.lupinSidebar-container .lupinSidebar-description {
         font-family: 'Open Sans', arial, sans-serif;
         margin-top: 12px;
         color: #fff;
     }

div#$optin_css_id.lupinSidebar-container .lupinSidebar-form {
         max-width: 280px;
         margin: 30px auto 0;
     }

div#$optin_css_id.lupinSidebar-container input.lupinSidebar-input {
         display: block;
         width: 100%;
         margin-top: 5px;
         -webkit-appearance: none;
         border: 0;
         -webkit-border-radius: 3px;
         -moz-border-radius: 3px;
         border-radius: 3px;
         font-family: 'Open Sans', arial, sans-serif;
         padding: 12px 0;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
         font-size: 16px;
         line-height: 16px;
         text-align: center;
         color: #555;
         background: #ffffff;
         outline: none;
     }

div#$optin_css_id.lupinSidebar-container input.lupinSidebar-submit, div#$optin_css_id.lupinSidebar-container input[type="submit"].mo-optin-form-cta-button {
         display: block;
         width: 100%;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
         margin-top: 8px;
         -webkit-appearance: none;
         border: 0;
         background: #FEC32D;
         -webkit-border-radius: 3px;
         -moz-border-radius: 3px;
         border-radius: 3px;
         font-family: 'Open Sans', arial, sans-serif;
         padding: 12px;
         font-size: 16px;
         line-height: 16px;
         text-align: center;
         color: #fff;
         outline: none;
         text-transform: uppercase;
         cursor: pointer;
         font-weight: 600;
     }

div#$optin_css_id.lupinSidebar-container .lupinSidebar-note {
         font-family: 'Open Sans', arial, sans-serif;
         font-size: 12px;
         line-height: 1.5;
         text-align: center;
         color: #fff;
         margin-top: 10px
}

div#$optin_css_id.lupinSidebar-container .mo-optin-error {
         display: none;
         background: #FF0000;
         color: #ffffff;
         text-align: center;
         padding: .2em;
         margin: 0 auto -5px;
         width: 100%;
         font-size: 16px;
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
         border: 1px solid #FF0000;
}
CSS;

    }
}