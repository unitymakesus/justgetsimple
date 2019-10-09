<?php

/**
 * Signature form shortcode
 */
add_shortcode('signature-form', function($atts) {

  acf_form_head();

	ob_start();

  acf_form(array(
  		'post_id'		=> 'new_post',
      'post_title'	=> false,
      'post_content'	=> false,
  		'new_post'		=> array(
  			'post_type'		=> 'simple-signatures',
  			'post_status'	=> 'publish'
  		)
  	));

  return ob_get_clean();
});
