<?php

/**
 * Define Required plugins
 * @return null
 */
function sp_require_plugins() {
  $requireds = array();

	if ( !is_plugin_active('advanced-custom-fields-pro/acf.php') ) {
    $requireds[] = array(
      'link' => 'https://www.advancedcustomfields.com/pro/',
      'name' => 'Advanced Custom Fields Pro'
    );
  }

  if ( !empty($requireds) ) {
    foreach ($requireds as $req) {
  		?>
  		<div class="notice notice-error"><p>
  			<?php printf(
  				__('<b>%s Plugin</b>: <a target="_blank" href="%s">%s</a> must be installed and activated.', 'sp'),
  	      'Simple Petition Deactivated',
          $req['link'],
          $req['name']
  			); ?>
  		</p></div>
  		<?php
    }
    deactivate_plugins( plugin_basename( __FILE__ ) );
  }
}


/**
 * Check if required plugins are activated
 * @return null
 */
function sp_check_required_plugins() {
  add_action( 'admin_notices', 'sp_require_plugins' );
}
