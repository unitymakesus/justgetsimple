<?php
/**
 *  UABB FAQ module file
 *
 *  @package UABB FAQ
 */

/**
 * Function that initializes UABB FAQ Module
 *
 * @class UABBFAQModule
 */
class UABBFAQModule extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the FAQ module.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'FAQ', 'uabb' ),
				'description'     => __( 'FAQ', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-faq/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-faq/',
				'partial_refresh' => true,
				'icon'            => 'faq.svg',
			)
		);

		$this->add_css( 'font-awesome-5' );
	}

	/**
	 * Function to get the icon for the Table of Contents
	 *
	 * @since 1.25.0
	 * @method get_icons
	 * @param string $icon gets the icon for the module.
	 */
	public function get_icon( $icon = '' ) {

		if ( '' !== $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-faq/icon/' . $icon ) ) {
			return fl_builder_filesystem()->file_get_contents( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-faq/icon/' . $icon );
		}
		return '';
	}

	/**
	 * Function that renders FAQ's Content
	 *
	 * @since 1.25.0
	 * @param object $settings gets an object for the settings.
	 */
	public function get_faq_content( $settings ) {
			global $wp_embed;

			$html = wpautop( $wp_embed->autoembed( $settings->faq_answer ) );

			return $html;
	}

	/**
	 * Function that renders FAQ's Icon
	 *
	 * @since 1.25.0
	 * @param object $pos gets an object for the icon's settings.
	 */
	public function render_icon( $pos ) {

		if ( esc_attr( $this->settings->icon_position ) === $pos && ( '' !== esc_attr( $this->settings->open_icon ) || '' !== esc_attr( $this->settings->close_icon ) ) ) {

				$output  = '<div class="uabb-faq-icon-wrap">';
				$output .= '<i class="uabb-faq-button-icon ' . esc_attr( $this->settings->close_icon ) . '"></i>';
				$output .= '</div>';
			return $output;
		}
		return '';
	}
	/**
	 * Function that renders FAQ's Icon
	 *
	 * @since 1.25.0
	 */
	public function render_schema() {

		$object_data = array();

			$json_data = array(
				'@context' => 'https://schema.org',
				'@type'    => 'FAQPage',
			);

			foreach ( $this->settings->faq_items as $items ) {
				$new_data = array(
					'@type'          => 'Question',
					'name'           => $items->faq_question,
					'acceptedAnswer' =>
					array(
						'@type' => 'Answer',
						'text'  => $items->faq_answer,
					),
				);
				array_push( $object_data, $new_data );
			}

			$json_data['mainEntity'] = $object_data;

			$encoded_data = wp_json_encode( $json_data );

			return $encoded_data;
	}
}

/*
 * Condition to verify Beaver Builder version.
 * And accordingly render the required form settings file.
 *
 */

if ( UABB_Compatibility::$version_bb_check ) {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-faq/uabb-faq-bb-2-2-compatibility.php';
} else {
	require_once BB_ULTIMATE_ADDON_DIR . 'modules/uabb-faq/uabb-faq-bb-less-than-2-2-compatibility.php';
}
