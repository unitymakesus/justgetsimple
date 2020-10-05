<?PHP
/*
Plugin Name: Gravity Forms to Pipe Drive CRM
Plugin URI: http://helpforwp.com/downloads/gravity-forms-to-pipe-drive-crm/
Description: An extension for Gravity Forms to send form entries automatically to Pipe Drive CRM
Version: 3.0
Author: HelpForWP.com
Author URI: http://helpforwp.com

------------------------------------------------------------------------
Copyright 2013 The DMA

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/
global $_wpgf2pdcrm_plugin_name, $_wpgf2pdcrm_version, $_wpgf2pdcrm_home_url, $_wpgf2pdcrm_plugin_author, $_wpgf2pdcrm_messager, $_wpgf2pdcrm_sell_page;

$_wpgf2pdcrm_plugin_name = 'Gravity Forms to Pipe Drive CRM';
$_wpgf2pdcrm_version = '3.0';
$_wpgf2pdcrm_home_url = 'http://helpforwp.com';
$_wpgf2pdcrm_plugin_author = 'HelpForWP';
$_wpgf2pdcrm_menu_url = admin_url('admin.php?page=gf2pdcrm');
$_wpgf2pdcrm_sell_page = 'https://helpforwp.com/downloads/gravity-forms-to-pipe-drive-crm/';
if( !class_exists( 'EDD_SL_Plugin_Updater_4_GravityForm2PipeDriveCRM' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

$_wpgf2pdcrm_license_key = trim( get_option( 'wpgf2pdcrm_license_key' ) );
// setup the updater
$_wpgf2pdcrm_updater = new EDD_SL_Plugin_Updater_4_GravityForm2PipeDriveCRM( $_wpgf2pdcrm_home_url, __FILE__, array( 
		'version' 	=> $_wpgf2pdcrm_version, 				// current version number
		'license' 	=> $_wpgf2pdcrm_license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => $_wpgf2pdcrm_plugin_name, 	// name of this plugin
		'author' 	=> $_wpgf2pdcrm_plugin_author  // author of this plugin
	)
);

//for new version message and expiring version message shown on dashboard
if( !class_exists( 'EddSLUpdateExpiredMessagerV4forGravityForm2PipeDriveCRM' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/edd-sl-update-expired-messager.php');
}
$init_arg = array();
$init_arg['plugin_name'] = $_wpgf2pdcrm_plugin_name;
$init_arg['plugin_download_id'] = 12755;
$init_arg['plugin_folder'] = 'gravity-forms-to-pipe-drive-crm';
$init_arg['plugin_file'] = basename(__FILE__);
$init_arg['plugin_version'] = $_wpgf2pdcrm_version;
$init_arg['plugin_home_url'] = $_wpgf2pdcrm_home_url;
$init_arg['plugin_sell_page_url'] = $_wpgf2pdcrm_sell_page;
$init_arg['plugin_author'] = $_wpgf2pdcrm_plugin_author;
$init_arg['plugin_setting_page_url'] = $_wpgf2pdcrm_menu_url;
$init_arg['plugin_license_key_opiton_name'] = 'wpgf2pdcrm_license_key';
$init_arg['plugin_license_status_option_name'] = 'wpgf2pdcrm_license_key_status';
$_wpgf2pdcrm_messager = new EddSLUpdateExpiredMessagerV4forGravityForm2PipeDriveCRM( $init_arg );

class WPGravityFormsToPipeDriveCRM {
	
	var $_wpgf2pdcrm_plugin_page_url = '';
	var $_wpgf2pdcrm_plugin_version = '';
	
	var $_wpgf2pdcrm_token_option_name = '_wpgf2pdcrm_token_';
	var $_wpgf2pdcrm_debug_enalbe_opiton = '_wpgf2pdcrm_debug_enable_opiton_';
    var $_wpgf2pdcrm_plugin_options_opiton = '_wpgf2pdcrm_plugin_opitons_';
	var $_wpgf2pdcrm_deal_custom_field_option_name = '_wpgf2pdcrm_deal_custom_field_data_';
	var $_wpgf2pdcrm_organisation_custom_field_option_name = '_wpgf2pdcrm_organisation_custom_field_data_';
	var $_wpgf2pdcrm_people_custom_field_option_name = '_wpgf2pdcrm_people_custom_field_data_';
    var $_wpgf2pdcrm_product_custom_field_option_name = '_wpgf2pdcrm_product_custom_field_data_';
	var $_wpgf2pdcrm_pipelines_n_stages_option_name = '_wpgf2pdcrm_pipleline_n_stages_data_';
	var $_wpgf2pdcrm_users_option_name = '_wpgf2pdcrm_pipleline_users_data_';
    
    var $_wpgf2pdcrm_deal_fields_options_cache_option_name = '_wpgf2pdcrm_deal_fields_options_data_';
    var $_wpgf2pdcrm_organisation_fields_options_cache_option_name = '_wpgf2pdcrm_organisation_fields_options_data_';
    var $_wpgf2pdcrm_people_fields_options_cache_option_name = '_wpgf2pdcrm_people_fields_options_data_';
    var $_wpgf2pdcrm_product_fields_options_cache_option_name = '_wpgf2pdcrm_product_fields_options_data_';
	
	var $_wpgf2pdcrm_organisations_list_option_name = '_wpgf2pdcrm_organisations_list_data_';
	var $_wpgf2pdcrm_persons_list_option_name = '_wpgf2pdcrm_persons_list_data_';
    var $_wpgf2pdcrm_products_list_option_name = '_wpgf2pdcrm_products_list_data_';
	
	var $_wpgf2pdcrm_activity_types_cache_option = '_wpgf2pdcrm_activity_types_data_';
    
    var $_wpgf2pdcrm_leads_labels_option_name = '_wpgf2pdcrm_leads_labels_data_';
	
	var $_wpgf2pdcrm_upgrade_to_2_0_option_name = '_wpgf2pdcrm_upgrade_to_2_0_option_name_';
	var $_wpgf2pdcrm_upgrade_to_new_custom_fields_name_option_name = '_wpgf2pdcrm_upgrade_to_new_custom_fields_name_option_name_';
	var $_wpgf2pdcrm_custom_fields_name_mapping_option = '_wpgf2pdcrm_custom_fields_name_mapping_';
	
	var $_wpgf2pdcrm_deal_title_custom_text_id = '_wpgf2pdcrm_deal_title_custom_text_id_';
	var $_wpgf2pdcrm_deal_title_custom_text_array = '_wpgf2pdcrm_deal_title_custom_text_array_';
	var $_wpgf2pdcrm_deal_title_custom_text_key_prefix = 'custom_text_';
	
	var $_wpgf2pdcrm_active_deactive_error_message_option = '_wpgf2pdcrm_activation_deactivation_error_';
    
    var $_wpgf2pdcrm_enable_cache_organisations_option = '_wpgf2pdcrm_enable_cache_organisations_';
    var $_wpgf2pdcrm_enable_cache_people_option = '_wpgf2pdcrm_enable_cache_people_';
    var $_wpgf2pdcrm_enable_cache_product_list_option = '_wpgf2pdcrm_enable_cache_product_list_';
    var $_wpgf2pdcrm_enable_cache_fields_options_option = '_wpgf2pdcrm_enable_cache_fields_options_';

	var $_wpgf2pdcrm_fields_by_group = array();
	var $_wpgf2pdcrm_custom_fields_type_description = array();
	
	var $_wpgf2pdcrm_plugin_folder_url = '';
	
	var $_wpgf2pdcrm_addon_OBJECT = NULL;
	var $_wpgf2pdcrm_api_CLASS_OBJECT = NULL;
	var $_wpgf2pdcrm_gform_field_CLASS_OBJECT = NULL;
	
	public function __construct() {
		global $_wpgf2pdcrm_menu_url, $_wpgf2pdcrm_version;
		
		$this->_wpgf2pdcrm_plugin_page_url = $_wpgf2pdcrm_menu_url;
		$this->_wpgf2pdcrm_plugin_version = $_wpgf2pdcrm_version;
		
		$current_path = dirname(__FILE__);
		$this->_wpgf2pdcrm_plugin_folder_url = site_url().'/'.str_replace( ABSPATH, '', $current_path ).'/';
        
        //Deals
		$this->_wpgf2pdcrm_fields_by_group['Deals'] = array();
		$this->_wpgf2pdcrm_fields_by_group['Deals']['title'] = array('label' => 'Deal title', 'type' => 'string', 'description' => 'Mandatory field.');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['value'] = array('label' => 'Value of the deal', 'type' => 'string', 'description' => 'If omitted, value will be set to 0.');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['currency'] = array('label' => 'Currency of the deal', 'type' => 'string', 'description' => 'Accepts a 3-character currency code. If omitted, currency will be set to the default currency of the authorized user.');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['status'] = array('label' => 'Status', 'type' => 'string', 'description' => 'open = Open, won = Won, lost = Lost, deleted = Deleted. If omitted, status will be set to open.');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['visible_to'] = array('label' => 'Visibility of the deal', 'type' => 'number', 'description' => 'If omitted, visibility will be set to the default visibility setting of this item type for the authorized user. <br />0 = Entire team (public), 1 = Owner only (private)');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['file'] = array('label' => 'File', 'type' => 'file', 'description' => 'Lets you upload one file, and associate them with a Deal.');

        $this->_wpgf2pdcrm_fields_by_group['Deals']['person_id'] = array('label' => 'Associate exist Person to deal', 'type' => 'number', 'description' => 'ID of exist Person to be associated. <br />Keep empty if you assigned field to Person Name to create Person.');
		$this->_wpgf2pdcrm_fields_by_group['Deals']['org_id'] = array('label' => 'Associate exist Organization to deal ', 'type' => 'number', 'description' => 'ID of exist Organisation to be associated. <br />Keep empty if you selected Organisation Name to create Organisation.');
        
        //Deal Note
		$this->_wpgf2pdcrm_fields_by_group['Notes'] = array();
		$this->_wpgf2pdcrm_fields_by_group['Notes']['content'] = array('label' => 'Create a note with this field', 'type' => 'string', 'description' => '');
        
        //Deal Products
        $this->_wpgf2pdcrm_fields_by_group['Products']['product_id'] = array('label' => 'Product ID', 'type' => 'number', 'description' => 'ID of exist Product that will be attached.<br />* Mandatory when attach product');
        $this->_wpgf2pdcrm_fields_by_group['Products']['product_price'] = array('label' => 'Price', 'type' => 'number', 'description' => 'Price at which this product will be added to the deal.<br />* Mandatory when attach product');
        $this->_wpgf2pdcrm_fields_by_group['Products']['product_quantity'] = array('label' => 'Quantity', 'type' => 'number', 'description' => 'Quantity – e.g. how many items of this product will be attached to the deal.<br /> * Mandatory when attach product');
        $this->_wpgf2pdcrm_fields_by_group['Products']['product_discount'] = array('label' => 'Discount', 'type' => 'number', 'description' => 'Discount %. <br />If omitted, will be set to 0.');
        
        //Organisations
		$this->_wpgf2pdcrm_fields_by_group['Organisations'] = array();
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['new_org'] = array('label' => 'Organisation name', 'type' => 'string', 'description' => 'Create a new organisation in pipedrive with this field as their name.<br />If omitted, no organisation will be created.');		
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['street'] = array('label' => 'Address - Street', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['addressline2'] = array('label' => 'Address - Address Line 2', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['city'] = array('label' => 'Address - City', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['state'] = array('label' => 'Address - State / Province', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['postcode'] = array('label' => 'Address - ZIP / Post Code', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['Organisations']['country'] = array('label' => 'Address - Country', 'type' => 'string', 'description' => 'up to 255 characters');
		
        //People
		$this->_wpgf2pdcrm_fields_by_group['People'] = array();
		$this->_wpgf2pdcrm_fields_by_group['People']['name'] = array('label' => 'Person - Name', 'type' => 'string', 'description' => 'Create a new person in pipedrive with this field as their name.<br />If omitted, no person will be created.');
        $this->_wpgf2pdcrm_fields_by_group['People']['email'] = array('label' => 'Person - eMail( work )', 'type' => 'string', 'description' => 'The work email address for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['email_home'] = array('label' => 'Person - eMail( home )', 'type' => 'string', 'description' => 'The home email address for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['email_other'] = array('label' => 'Person - eMail( other )', 'type' => 'string', 'description' => 'The other email address for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['phone'] = array('label' => 'Person - Phone( work )', 'type' => 'string', 'description' => 'The work phone number for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['phone_home'] = array('label' => 'Person - Phone( home )', 'type' => 'string', 'description' => 'The home phone number for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['phone_mobile'] = array('label' => 'Person - Phone( mobile )', 'type' => 'string', 'description' => 'The mobile phone number for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['phone_other'] = array('label' => 'Person - Phone( other )', 'type' => 'string', 'description' => 'The other phone number for a new person record.');
        $this->_wpgf2pdcrm_fields_by_group['People']['p_postal_street'] = array('label' => 'Postal address - Street', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['People']['p_postal_addressline2'] = array('label' => 'Postal address - Address Line 2', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['People']['p_postal_city'] = array('label' => 'Postal address - City', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['People']['p_postal_state'] = array('label' => 'Postal address - State / Province', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['People']['p_postal_postcode'] = array('label' => 'Postal address - ZIP / Post Code', 'type' => 'string', 'description' => 'up to 255 characters');
		$this->_wpgf2pdcrm_fields_by_group['People']['p_postal_country'] = array('label' => 'Postal address - Country', 'type' => 'string', 'description' => 'up to 255 characters');
		
        //Activity
		$this->_wpgf2pdcrm_fields_by_group['Activity'] = array();
		$this->_wpgf2pdcrm_fields_by_group['Activity']['subject'] = array('label' => 'Activity - Subject', 'type' => 'string', 'description' => 'Subject of the activity.<br />If omitted, no activity will be created.');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['type'] = array('label' => 'Activity - Type', 'type' => 'string', 'description' => 'Type of the activity.<br />If omitted, no activity will be created.');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['done'] = array('label' => 'Activity - Done', 'type' => 'enumerated', 'description' => 'Whether the activity is done or not.<br />0 = Not done, 1 = Done');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['due_date'] = array('label' => 'Activity - Due date', 'type' => 'date', 'description' => 'Due date of the activity. Format: YYYY-MM-DD');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['due_time'] = array('label' => 'Activity - Due time', 'type' => 'time', 'description' => 'Due time of the activity in UTC. Format: HH:MM');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['duration'] = array('label' => 'Activity - Duration', 'type' => 'time', 'description' => 'Duration of the activity. Format: HH:MM');
		$this->_wpgf2pdcrm_fields_by_group['Activity']['note'] = array('label' => 'Activity - Note', 'type' => 'string', 'description' => 'Note of the activity (HTML format)');
        
        //Lead
		$this->_wpgf2pdcrm_fields_by_group['Lead'] = array();
		$this->_wpgf2pdcrm_fields_by_group['Lead']['title'] = array('label' => 'Lead - Title', 'type' => 'string', 'description' => 'The name of the Lead, required.');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['note'] = array('label' => 'Lead - Note', 'type' => 'string', 'description' => 'The Lead note.');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['label_ids'] = array('label' => 'Lead - Labels', 'type' => 'array', 'description' => 'The IDs of the Lead Labels which will be associated with the Lead');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['person_id'] = array('label' => 'Lead - Person ID', 'type' => 'integer', 'description' => 'The ID of a Person which this Lead will be linked to. If the Person does not exist yet, it needs to be created first. This property is required unless organization_id is specified.');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['organization_id'] = array('label' => 'Lead - Organization ID', 'type' => 'integer', 'description' => 'The ID of an Organization which this Lead will be linked to. If the Organization does not exist yet, it needs to be created first. This property is required unless person_id is specified.');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['value'] = array('label' => 'Lead - Value', 'type' => 'number', 'description' => 'The potential value of the Lead. Currency will use the select one in Gravity Forms\' General Settings tab');
		$this->_wpgf2pdcrm_fields_by_group['Lead']['expected_close_date'] = array('label' => 'Lead - Expected Close Date', 'type' => 'string', 'description' => 'The date of when the Deal which will be created from the Lead is expected to be closed. In ISO 8601 format: YYYY-MM-DD.');

		$this->_wpgf2pdcrm_custom_fields_type_description['varchar'] = 'Text field is used to store texts up to 255 characters.';
		$this->_wpgf2pdcrm_custom_fields_type_description['text'] = 'Large text field is used to store texts longer that usual.';
		$this->_wpgf2pdcrm_custom_fields_type_description['double'] = 'Numeric field is used to store data such as amount of commission or other custom numerical data.';
		$this->_wpgf2pdcrm_custom_fields_type_description['monetary'] = 'Monetary field is used to store data such as amount of commission.';
		$this->_wpgf2pdcrm_custom_fields_type_description['set'] = 'Multiple options field lets you predefine a list of values to choose from.';
		$this->_wpgf2pdcrm_custom_fields_type_description['enum'] = 'Single option field lets you predefine a list of values out of which one can be selected.';
		$this->_wpgf2pdcrm_custom_fields_type_description['phone'] = 'A phone number field can contain a phone number (naturally) or a Skype Name with a click-to-call functionality.';
		$this->_wpgf2pdcrm_custom_fields_type_description['text'] = 'Large text field is used to store texts longer that usual.';
		$this->_wpgf2pdcrm_custom_fields_type_description['timerange'] = 'Time field is used to store times, picked from a handy inline timepicker.';
		$this->_wpgf2pdcrm_custom_fields_type_description['time'] = 'Time field is used to store times, picked from a handy inline timepicker.';
		$this->_wpgf2pdcrm_custom_fields_type_description['date'] = 'Date field is used to store dates, picked from a handy inline calendar.';
		$this->_wpgf2pdcrm_custom_fields_type_description['daterange'] = 'Date range field is used to store date ranges, picked from a handy inline calendars.';
		$this->_wpgf2pdcrm_custom_fields_type_description['address'] = 'Address field is used to store addresses. Important: Address field can hold all parts of address components - including City, State, Zip Code, Country - so there is no need to create separate address fields for each address component.';
		
		register_activation_hook( __FILE__, array($this, 'wpgf2pdcrm_activate' ) );
		register_deactivation_hook( __FILE__, array($this, 'wpgf2pdcrm_deactivate' ) );
		register_uninstall_hook( __FILE__,  'WPGravityFormsToPipeDriveCRM::wpgf2pdcrm_remove_option' );
		
		add_action( 'plugins_loaded', array($this, 'wpgf2pdcrm_load_addon'), 999);
		
		//Plugin actions
		add_action( 'init', array($this, 'wpgf2pdcrm_post_action') );
		add_action( 'admin_init', array($this, 'wpgf2pdcrm_activate_license') );
		add_action( 'admin_init', array($this, 'wpgf2pdcrm_deactivate_license') );
		if( is_admin() ){
			add_action( 'admin_enqueue_scripts', array($this, 'wpgf2pdcrm_enqueue_scripts') );
			add_filter( 'gform_noconflict_scripts', array($this, 'wpgf2pdcrm_enqueue_scripts_4_gf_nonconflict_fun') );
			add_filter( 'gform_noconflict_styles', array($this, 'wpgf2pdcrm_enqueue_styles_4_gf_nonconflict_fun') );
			
			add_action( 'wp_ajax_wpgf2pdcrm_deal_title_add_custom_text', array($this, 'wpgf2pdcrm_deal_title_add_custom_text_fun') );
			add_action( 'wp_ajax_wpgf2pdcrm_deal_title_delete_custom_text', array($this, 'wpgf2pdcrm_deal_title_delete_custom_text_fun') );
		}
	}

	
	function wpgf2pdcrm_activate() {
	}
	
	
	function wpgf2pdcrm_deactivate(){
	}


	function wpgf2pdcrm_remove_option() {
        
        $plugin_options = get_option( '_wpgf2pdcrm_plugin_opitons_', false );
        $uninstall_data = false;
        if( $plugin_options && is_array( $plugin_options ) && count( $plugin_options ) > 0 ){
            if( isset($plugin_options['uninstall_data_option']) && 
                $plugin_options['uninstall_data_option'] == 'YES' ){
                
                $uninstall_data = true;
            }
        }
        
        if( $uninstall_data == false ){
            return;
        }
		delete_option('_wpgf2pdcrm_token_');
		delete_option('_wpgf2pdcrm_debug_enable_opiton_');
        delete_option('_wpgf2pdcrm_plugin_opitons_');
		delete_option('wpgf2pdcrm_license_key');
		delete_option('wpgf2pdcrm_license_key_status');
        
        delete_option('_wpgf2pdcrm_pipleline_n_stages_data_');
        delete_option('_wpgf2pdcrm_pipleline_users_data_');
        
		delete_option('_wpgf2pdcrm_deal_custom_field_data_');
        delete_option('_wpgf2pdcrm_organisation_custom_field_data_');
        delete_option('_wpgf2pdcrm_people_custom_field_data_');
        delete_option('_wpgf2pdcrm_product_custom_field_data_');
        
        delete_option('_wpgf2pdcrm_deal_fields_options_data_');
        delete_option('_wpgf2pdcrm_organisation_fields_options_data_');
        delete_option('_wpgf2pdcrm_people_fields_options_data_');
        delete_option('_wpgf2pdcrm_product_fields_options_data_');
        
		delete_option('_wpgf2pdcrm_upgrade_to_2_0_option_name_');
		delete_option('_wpgf2pdcrm_deal_title_custom_text_id_');
		delete_option('_wpgf2pdcrm_deal_title_custom_text_array_');
		delete_option('_wpgf2pdcrm_activation_deactivation_error_');
        
        delete_option('_wpgf2pdcrm_organisations_list_data_');
        delete_option('_wpgf2pdcrm_persons_list_data_');
        delete_option('_wpgf2pdcrm_products_list_data_');
        delete_option('_wpgf2pdcrm_activity_types_data_');
        delete_option('_wpgf2pdcrm_upgrade_to_new_custom_fields_name_option_name_');
        delete_option('_wpgf2pdcrm_custom_fields_name_mapping_');
        delete_option('_wpgf2pdcrm_enable_cache_organisations_');
        delete_option('_wpgf2pdcrm_enable_cache_people_');
        delete_option('_wpgf2pdcrm_enable_cache_product_list_');
		
		global $wpdb;
		
		$sql = 'DELETE FROM `'.$wpdb->options.'` WHERE `option_name` LIKE \'_wpgf2pdcrm_form_%_mapping_\'';
		$wpdb->query( $sql );
		
		return;
	}
	
	function wpgf2pdcrm_enqueue_scripts( $hook ){
		
		if( $hook == 'forms_page_gf2pdcrm' || 
			$hook == 'forms_page_gf_settings' || 
			$hook == 'toplevel_page_gf_edit_forms' || 
			$hook == 'forms1_page_gf_settings' ){
				
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 
                                          'wpgf2pdcrm-admin', 
                                          plugin_dir_url( __FILE__ ) . 'js/wpgf2pd_crm_admin.js', 
                                          array( 'jquery' ), 
                                          filemtime( plugin_dir_path( __FILE__ ).'js/wpgf2pd_crm_admin.js') 
                                        );
			wp_enqueue_style( 
                                        'wpgf2pdcrm-admin', 
                                        plugin_dir_url( __FILE__ ) . 'css/wpgf2pd_crm_admin.css', 
                                        array(), 
                                        filemtime( plugin_dir_path( __FILE__ ).'css/wpgf2pd_crm_admin.css')  
                                      );
		}else if( isset($_GET['subview']) && $_GET['subview'] == 'gf2pdcrm' ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wpgf2pdcrm-admin', plugin_dir_url( __FILE__ ) . 'js/wpgf2pd_crm_admin.js', array( 'jquery' ), $this->_wpgf2pdcrm_plugin_version );
			wp_enqueue_style( 'wpgf2pdcrm-admin', plugin_dir_url( __FILE__ ) . 'css/wpgf2pd_crm_admin.css', array(), $this->_wpgf2pdcrm_plugin_version );
		}
	}
	
	function wpgf2pdcrm_enqueue_scripts_4_gf_nonconflict_fun( $scripts_array ){
		$scripts_array[] = 'wpgf2pdcrm-admin';
		
		return $scripts_array;
	}
	
	function wpgf2pdcrm_enqueue_styles_4_gf_nonconflict_fun( $styles_array ){
		$styles_array[] = 'wpgf2pdcrm-admin';
		
		return $styles_array;
	}
	
	function wpgf2pdcrm_post_action(){
		if( isset( $_POST['wpgf2pdcrm_action'] ) && strlen($_POST['wpgf2pdcrm_action']) > 0 ) {
			do_action( 'wpgf2pdcrm_action_' . $_POST['wpgf2pdcrm_action'], $_POST );
		}
	}
	
	function wpgf2pdcrm_activate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['wpgf2pdcrm_license_activate'] ) ) {
			global $_wpgf2pdcrm_plugin_name, $_wpgf2pdcrm_home_url;

			// run a quick security check 
			if( ! check_admin_referer( 'wpgf2pdcrm_license_key_nonce', 'wpgf2pdcrm_license_key_nonce' ) ){
				wp_die( 'Security check' );
			}
			// retrieve the license from the database
			$license = trim( $_POST['wpgf2pdcrm_license_key'] );
				
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'activate_license', 
				'license' 	=> $license, 
				'item_name' => urlencode( $_wpgf2pdcrm_plugin_name ), // the name of our product in EDD
				'url'       => home_url()
			);
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $_wpgf2pdcrm_home_url ), array( 'timeout' => 15 ) );
			
			$message = '';
			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ){
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}
			}
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if( $license_data && isset( $license_data->success ) && false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $_wpgf2pdcrm_plugin_name );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}
			
			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				update_option( $this->_wpgf2pdcrm_active_deactive_error_message_option, $message );
                
                return;
			}else{
				delete_option( $this->_wpgf2pdcrm_active_deactive_error_message_option );
			}

			update_option( 'wpgf2pdcrm_license_key', $license );
			update_option( 'wpgf2pdcrm_license_key_status', $license_data->license );
		}
	}
	
	function wpgf2pdcrm_deactivate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['wpgf2pdcrm_license_deactivate'] ) ) {
			global $_wpgf2pdcrm_plugin_name, $_wpgf2pdcrm_home_url;
			
			// run a quick security check 
			if( ! check_admin_referer( 'wpgf2pdcrm_license_key_nonce', 'wpgf2pdcrm_license_key_nonce' ) ){
				wp_die( 'Security Check!' );
			}
	
			// retrieve the license from the database
			$license = trim( get_option( 'wpgf2pdcrm_license_key' ) );
	
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'deactivate_license', 
				'license' 	=> $license, 
				'item_name' => urlencode( $_wpgf2pdcrm_plugin_name ), // the name of our product in EDD
				'url'       => home_url()
			);
			
			// Call the custom API.
			global $_wpgf2pdcrm_home_url;
			$response = wp_remote_get( add_query_arg( $api_params, $_wpgf2pdcrm_home_url ), array( 'timeout' => 15 ) );
			
			$message = '';
			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}
			}
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' || $license_data->license == 'failed') {
				delete_option( 'wpgf2pdcrm_license_key_status' );
			}
			
			if ( ! empty( $message ) ) {
				update_option( $this->_wpgf2pdcrm_active_deactive_error_message_option, $message );
			}else{
				delete_option( $this->_wpgf2pdcrm_active_deactive_error_message_option );
			}
		}
	}
	
	function wpgf2pdcrm_upgrade_to_2_0_version(){
		//read old settings
		global $wpdb;
		
		if( get_option( $this->_wpgf2pdcrm_upgrade_to_2_0_option_name ) ){
			return;
		}
		
		$sql = 'SELECT * FROM `'.$wpdb->options.'` WHERE `option_name` LIKE \'_wpgf2pdcrm_form_%_mapping_\'';
		$results = $wpdb->get_results( $sql );
		if( !$results || !is_array($results) || count($results) < 1 ){
			update_option( $this->_wpgf2pdcrm_upgrade_to_2_0_option_name, true );
			return;
		}
		
		//check if table _gf_addon_feed exit
		$table_name = $wpdb->prefix.'gf_addon_feed';
		$return_var = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );
		if( $return_var !=  $table_name ){
			return;
		}
		
		$token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
		$pipeline_stage_data = '';
		$users_data = '';
		if( $token_saved ){
			$pipeline_stage_data = $this->_wpgf2pdcrm_api_CLASS_OBJECT->wpgf2pdcrm_read_pipelines_n_stages_data( $token_saved );
			$users_data = $this->_wpgf2pdcrm_api_CLASS_OBJECT->wpgf2pdcrm_read_users_data( $token_saved );
		}
		
		$item = 1;
		foreach( $results as $option_obj ){
			$form_id = str_replace( '_wpgf2pdcrm_form_', '', $option_obj->option_name );
			$form_id = str_replace( '_mapping_', '', $form_id );
			$form_id = intval($form_id);
			
			$data_to_insert = array();
			$data_to_insert['form_id'] = $form_id;
			$data_to_insert['is_active'] = 1;
			$data_to_insert['addon_slug'] = 'gf2pdcrm';
			
			$data_to_insert_meta = array();
			$data_to_insert_meta['feedName'] = 'Pipedrive Add-On Feed '.$item++;
			$data_to_insert_meta['pipeline_list_select'] = '1';
			$data_to_insert_meta['stage_list_select'] = '1';
			$data_to_insert_meta['feed_condition_conditional_logic'] = 0;
			$data_to_insert_meta['feed_condition_conditional_logic_object'] = array();
			
			$old_mapping_array = unserialize( $option_obj->option_value );
			if( !$old_mapping_array || !is_array($old_mapping_array) || count($old_mapping_array) < 1 ){
				continue;
			}

			foreach( $old_mapping_array as $map ){
				$gf_id = str_replace( 'input_', '', $map['field_id'] );
				//advanced field
				if( $pos = strpos($gf_id, '_') !== false ){
					$gf_id = substr($gf_id, 0, $pos);
				}
				
				if( strcmp($map['remote_field'], 'user_id') == 0 || strcmp($map['remote_field'], 'stage_id') == 0 ){
					//read gf field default value
					$default_value = '';
					$form = GFAPI::get_form( $form_id );
					$field  = RGFormsModel::get_field( $form, $gf_id );
					if( !isset($field->defaultValue) || !$field->defaultValue ){
						continue;
					}
					
					$default_value = $field->defaultValue;
					if( $map['remote_field'] == 'user_id' ){
						$data_to_insert_meta['owner_list_select'] = $default_value;
					}else if( $map['remote_field'] == 'stage_id' ){
						$data_to_insert_meta['stage_list_select'] = $default_value;
						//get pipeline
						$pipeline_id_got = '';
						
						if( $pipeline_stage_data && is_array($pipeline_stage_data) && count($pipeline_stage_data) > 0 ){
							foreach( $pipeline_stage_data as $pipeline_id => $pipeline_data ){
								if( $pipeline_data['stages'] && is_array($pipeline_data['stages']) && count($pipeline_data['stages']) > 0 ){
									foreach( $pipeline_data['stages'] as $stages_id => $stages_name ){
										if( $stages_id == $default_value ){
											$pipeline_id_got = $pipeline_id;
											break;
										}
									}
								}
								if( $pipeline_id_got ){
									break;
								}
							}
						}
						$data_to_insert_meta['pipeline_list_select'] = $pipeline_id_got;
					}
					continue;
				}
				
				$pipedrive_map_field = 'pipedrive_map_'.$map['remote_field'];
				$data_to_insert_meta[$pipedrive_map_field] = $gf_id;
			}

			$data_to_insert['meta'] = json_encode( $data_to_insert_meta );
			//create feed
			$wpdb->insert( $wpdb->prefix.'gf_addon_feed', $data_to_insert );
		}

		$sql = 'DELETE FROM `'.$wpdb->options.'` WHERE `option_name` LIKE \'_wpgf2pdcrm_form_%_mapping_\'';
		$wpdb->query( $sql );
		
		update_option( $this->_wpgf2pdcrm_upgrade_to_2_0_option_name, true );
	}
	
	function wpgf2pdcrm_load_addon(){
		
		if( !class_exists('WPGravityFormsToPipedriveAddOn') ) {
            require_once( 'inc/pipedrive-feed-addon.php' );
        }
		
		if( !class_exists('WPGravityFormsToPipedriveAPIClass') ) {
            require_once( 'inc/pipedrive-api.php' );
        }
		
		if( !class_exists('WPGravityFormsToPipedriveFormField') ) {
            require_once( 'inc/pipedrive-gform-field.php' );
        }
		
		if (class_exists("GFForms")) {
			$init_args = array();
			$init_args['token_option_name'] = $this->_wpgf2pdcrm_token_option_name;
			$init_args['debug_enable_option'] = $this->_wpgf2pdcrm_debug_enalbe_opiton;
            $init_args['plugin_options_option'] = $this->_wpgf2pdcrm_plugin_options_opiton;
			$init_args['plugin_page_url'] = $this->_wpgf2pdcrm_plugin_page_url;
			$init_args['deal_custom_field_option'] = $this->_wpgf2pdcrm_deal_custom_field_option_name;
			$init_args['org_custom_field_option'] = $this->_wpgf2pdcrm_organisation_custom_field_option_name;
			$init_args['people_custom_field_option'] = $this->_wpgf2pdcrm_people_custom_field_option_name;
            $init_args['product_custom_field_option'] = $this->_wpgf2pdcrm_product_custom_field_option_name;
            
            $init_args['deal_fields_options_cache_option'] = $this->_wpgf2pdcrm_deal_fields_options_cache_option_name;
			$init_args['organisation_fields_options_cache_option'] = $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name;
			$init_args['people_fields_options_cache_option'] = $this->_wpgf2pdcrm_people_fields_options_cache_option_name;
			$init_args['product_fields_options_cache_option'] = $this->_wpgf2pdcrm_product_fields_options_cache_option_name;
            
            
			$init_args['pipeline_stages_option'] = $this->_wpgf2pdcrm_pipelines_n_stages_option_name;
			$init_args['pipeline_users_option'] = $this->_wpgf2pdcrm_users_option_name;
			$init_args['fields_by_group'] = $this->_wpgf2pdcrm_fields_by_group;
			$init_args['custom_fields_type_description'] = $this->_wpgf2pdcrm_custom_fields_type_description;
			$init_args['custom_fields_name_mapping_option'] = $this->_wpgf2pdcrm_custom_fields_name_mapping_option;
			$init_args['active_deactive_error_message_option'] = $this->_wpgf2pdcrm_active_deactive_error_message_option;
			
			$init_args['deal_title_custom_text_array_option'] = $this->_wpgf2pdcrm_deal_title_custom_text_array;
			$init_args['deal_title_custom_text_key_prefix'] = $this->_wpgf2pdcrm_deal_title_custom_text_key_prefix;
			
			$init_args['plugin_folder_url'] = $this->_wpgf2pdcrm_plugin_folder_url;
			
			$init_args['org_list_cache_option'] = $this->_wpgf2pdcrm_organisations_list_option_name;
			$init_args['persons_list_cache_option'] = $this->_wpgf2pdcrm_persons_list_option_name;
            $init_args['products_list_cache_option'] = $this->_wpgf2pdcrm_products_list_option_name;
			            
            $init_args['enable_cache_organisations_option'] = $this->_wpgf2pdcrm_enable_cache_organisations_option;
            $init_args['enable_cache_people_option'] = $this->_wpgf2pdcrm_enable_cache_people_option;
            $init_args['enable_cache_product_list_option'] = $this->_wpgf2pdcrm_enable_cache_product_list_option;
            $init_args['enable_cache_fields_options_option'] = $this->_wpgf2pdcrm_enable_cache_fields_options_option;

			$init_args['activity_types_cache_option'] = $this->_wpgf2pdcrm_activity_types_cache_option;
            
            $init_args['leads_labels_option'] = $this->_wpgf2pdcrm_leads_labels_option_name;
			
			$this->_wpgf2pdcrm_api_CLASS_OBJECT = new WPGravityFormsToPipedriveAPIClass( $init_args );
			
			$init_args['API_CLASS_instance'] = $this->_wpgf2pdcrm_api_CLASS_OBJECT;
			
			$this->_wpgf2pdcrm_addon_OBJECT = new WPGravityFormsToPipedriveAddOn( $init_args );
			$this->wpgf2pdcrm_upgrade_to_2_0_version();
			$this->wpgf2pdcrm_upgrade_to_new_custom_fiels_name_fun();
			
			$this->_wpgf2pdcrm_gform_field_CLASS_OBJECT = new WPGravityFormsToPipedriveFormField( $init_args );
		}
	}
	
	function wpgf2pdcrm_upgrade_to_new_custom_fiels_name_fun(){
		if( get_option( $this->_wpgf2pdcrm_upgrade_to_new_custom_fields_name_option_name ) == 'YES' ){
			return;
		}
		$license_key = get_option ('wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		$token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
		if( $token_saved ){
			//update deal custom fields cache
			$this->_wpgf2pdcrm_api_CLASS_OBJECT->wpgf2pdcrm_update_deal_custom_fields_cache( $token_saved );
			//update organisation custom fields cache
			$this->_wpgf2pdcrm_api_CLASS_OBJECT->wpgf2pdcrm_update_organisation_custom_fields_cache( $token_saved );
			//update people custom fields cache
			$this->_wpgf2pdcrm_api_CLASS_OBJECT->wpgf2pdcrm_update_people_custom_fields_cache( $token_saved );
		}
		
		update_option( $this->_wpgf2pdcrm_upgrade_to_new_custom_fields_name_option_name, 'YES' );
	}
	
	function wpgf2pdcrm_deal_title_add_custom_text_fun(){
		global $current_user;
		if( $current_user->ID < 1 || !current_user_can( 'manage_options' ) ){
			wp_die( 'ERROR: Invalid Operation' );
		}
		
		$custom_text = $_POST['text'];
		$custom_text = wp_unslash( $custom_text );
		
		$deal_title_custom_text_id = get_option( $this->_wpgf2pdcrm_deal_title_custom_text_id, 0 );
		$deal_title_custom_text_id++;
		//save new custom text
		$deal_title_custom_array = get_option( $this->_wpgf2pdcrm_deal_title_custom_text_array, array() );
		$deal_title_custom_array[$this->_wpgf2pdcrm_deal_title_custom_text_key_prefix.$deal_title_custom_text_id] = $custom_text;
		
		update_option( $this->_wpgf2pdcrm_deal_title_custom_text_array, $deal_title_custom_array );
		update_option( $this->_wpgf2pdcrm_deal_title_custom_text_id, $deal_title_custom_text_id );
		
		wp_die( $this->_wpgf2pdcrm_deal_title_custom_text_key_prefix.$deal_title_custom_text_id );
	}
	
	function wpgf2pdcrm_deal_title_delete_custom_text_fun(){
		global $current_user;
		if( $current_user->ID < 1 || !current_user_can( 'manage_options' ) ){
			wp_die( 'ERROR: Invalid Operation' );
		}
		
		$custom_text_key = $_POST['key'];
		$deal_title_custom_array = get_option( $this->_wpgf2pdcrm_deal_title_custom_text_array, array() );
		unset( $deal_title_custom_array[$custom_text_key] );
		
		update_option( $this->_wpgf2pdcrm_deal_title_custom_text_array, $deal_title_custom_array );
	}
}


$wpgf2pdcrm_pro_instance = new WPGravityFormsToPipeDriveCRM();
