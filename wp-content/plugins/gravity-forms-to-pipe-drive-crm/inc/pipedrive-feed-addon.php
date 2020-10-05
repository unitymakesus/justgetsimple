<?php

if (class_exists("GFForms") && !class_exists('WPGravityFormsToPipedriveAddOn')) {
    GFForms::include_feed_addon_framework();

    class WPGravityFormsToPipedriveAddOn extends GFFeedAddOn {

        protected $_version = "1.0";
        protected $_min_gravityforms_version = "1.7.9999";
        protected $_slug = "gf2pdcrm";
        protected $_path = "gf2pdcrm/pipedrive-addon.php";
        protected $_full_path = __FILE__;
        protected $_title = "Gravity Forms to Pipedrive Addon";
        protected $_short_title = "Pipedrive Add-On";
		
		var $_wpgf2pdcrm_plugin_page_url = '';
		var $_wpgf2pdcrm_token_option_name = '';
		var $_wpgf2pdcrm_debug_enalbe_opiton = '';
        var $_wpgf2pdcrm_plugin_options_opiton = '';
		var $_wpgf2pdcrm_deal_custom_field_option_name = '';
		var $_wpgf2pdcrm_organisation_custom_field_option_name = '';
		var $_wpgf2pdcrm_people_custom_field_option_name = '';
		var $_wpgf2pdcrm_pipelines_n_stages_option_name = '';
		var $_wpgf2pdcrm_users_option_name = '';
		var $_wpgf2pdcrm_fields_by_group = array();
		var $_wpgf2pdcrm_custom_fields_type_description = array();
        
        var $_wpgf2pdcrm_enable_cache_organisations_option = '';
        var $_wpgf2pdcrm_enable_cache_people_option = '';
        var $_wpgf2pdcrm_enable_cache_product_list_option = '';
		
		var $_wpgf2pdcrm_deal_title_custom_text_array = '';
		var $_wpgf2pdcrm_deal_title_custom_text_key_prefix = '';
        
        var $_wpgf2pdcrm_activity_types_cache_option = '';
        var $_wpgf2pdcrm_leads_labels_option_name = '';
		
		var $_wpgf2pdcrm_plugin_folder_url = '';
		
		var $wpgf2pdcrm_option_CLASS = NULL;
		var $wpgf2pdcrm_api_CLASS = NULL;
		
		var $_wpgf2pdcrm_custom_fields_name_mapping_option = '';
		
		function __construct( $args ) {

            parent::__construct();
			
			$this->_wpgf2pdcrm_plugin_page_url = $args['plugin_page_url'];
			$this->_wpgf2pdcrm_token_option_name = $args['token_option_name'];
			$this->_wpgf2pdcrm_debug_enalbe_opiton = $args['debug_enable_option'];
            $this->_wpgf2pdcrm_plugin_options_opiton = $args['plugin_options_option'];
			$this->_wpgf2pdcrm_deal_custom_field_option_name = $args['deal_custom_field_option'];
			$this->_wpgf2pdcrm_organisation_custom_field_option_name = $args['org_custom_field_option'];
			$this->_wpgf2pdcrm_people_custom_field_option_name = $args['people_custom_field_option'];
			$this->_wpgf2pdcrm_pipelines_n_stages_option_name = $args['pipeline_stages_option'];
			$this->_wpgf2pdcrm_users_option_name = $args['pipeline_users_option'];
			$this->_wpgf2pdcrm_fields_by_group = $args['fields_by_group'];
			$this->_wpgf2pdcrm_custom_fields_type_description = $args['custom_fields_type_description'];
			$this->_wpgf2pdcrm_custom_fields_name_mapping_option = $args['custom_fields_name_mapping_option'];
			
			$this->_wpgf2pdcrm_organisations_list_option_name = $args['org_list_cache_option'];
			$this->_wpgf2pdcrm_persons_list_option_name = $args['persons_list_cache_option'];
            $this->_wpgf2pdcrm_products_list_option_name = $args['products_list_cache_option'];
			
			$this->_wpgf2pdcrm_deal_title_custom_text_array = $args['deal_title_custom_text_array_option'];
			$this->_wpgf2pdcrm_deal_title_custom_text_key_prefix = $args['deal_title_custom_text_key_prefix'];
			
			$this->_wpgf2pdcrm_activity_types_cache_option = $args['activity_types_cache_option'];
            $this->_wpgf2pdcrm_leads_labels_option_name = $args['leads_labels_option'];
			
			$this->_wpgf2pdcrm_plugin_folder_url = $args['plugin_folder_url'];
            
            $this->_wpgf2pdcrm_enable_cache_organisations_option = $args['enable_cache_organisations_option'];
            $this->_wpgf2pdcrm_enable_cache_people_option = $args['enable_cache_people_option'];
            $this->_wpgf2pdcrm_enable_cache_product_list_option = $args['enable_cache_product_list_option'];
			$this->wpgf2pdcrm_api_CLASS = $args['API_CLASS_instance'];
            
			require_once('gravity-forms-to-pipedrive-crm-options.php');

            $this->wpgf2pdcrm_option_CLASS = new WPGravityFormsToPipeDriveCRMOptions( $args );
        }
        
        public function plugin_page_container(){
            
            $plugin_page_icon = $this->_wpgf2pdcrm_plugin_folder_url.'images/h4wp-logo.png';
            ?>
            <div class="wrap">
                <img alt="<?php echo $this->_short_title ?>" id="h4wp-logo" src="<?php echo $plugin_page_icon; ?>" />
			    <h2 class="gf_admin_page_title"><?php echo $this->_title; ?></h2>
			<?php $this->wpgf2pdcrm_option_CLASS->wpgf2pdcrm_options_plugin_page(); ?>
            </div>
            <div class="wrap">
            <?php require_once('footer.php'); ?>
		    </div>
            <?php
        }

        public function plugin_page() {
            /*
			
			$this->wpgf2pdcrm_option_CLASS->wpgf2pdcrm_options_plugin_page();
			echo '</div>';
			echo '<div class="wrap">';
			//for footer
			require_once('footer.php');*/
        }
		
		public function plugin_page_icon(){

			//return $this->_wpgf2pdcrm_plugin_folder_url.'images/help-for-wordpress-small222.png"';
		}

		
		public function plugin_settings(){
			$license_key = get_option ('wpgf2pdcrm_license_key', '' );
			$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
			if( !$license_key || $license_key_status != 'valid' ){
				echo '<br /><br />';
				echo '<h4>Please <a href="'.$this->_wpgf2pdcrm_plugin_page_url.'">activate your license</a> first</h4>';
				
				return;
			}
            echo '<form id="gravity_forms_to_pipedrive_addon_plugin_settings_form" action="" method="post">';
			$this->wpgf2pdcrm_option_CLASS->wpgf2pdcrm_options_plugin_settings();
            echo '</form>';
			
			$token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
			if( $token_saved ){
				//update deal custom fields cache
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_deal_custom_fields_cache( $token_saved );
				//update organisation custom fields cache
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_organisation_custom_fields_cache( $token_saved );
				//update people custom fields cache
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_people_custom_fields_cache( $token_saved );
                //update product custom fields cache
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_product_custom_fields_cache( $token_saved );
				
				//update organisations list cache
                if( get_option( $this->_wpgf2pdcrm_enable_cache_organisations_option, false ) == true ){
                    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_organisations_list_cache( $token_saved );
                }
				//update persons list cache
                if( get_option( $this->_wpgf2pdcrm_enable_cache_people_option, false ) == true ){
				    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_persons_list_cache( $token_saved );
                }
                //update products list cache
                if( get_option( $this->_wpgf2pdcrm_enable_cache_product_list_option, false ) == true ){
				    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_update_products_list_cache( $token_saved );
                }
				
				
				//read pipeline & stage
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_read_pipelines_n_stages_data( $token_saved );
				//read users
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_read_users_data( $token_saved );
				
				//read activity types
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_read_activity_types( $token_saved );
                
                //read lead labels
                $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_read_leads_labels_cache( $token_saved );
				
				echo '<p>                        
						<input type="button" id="wpgf2pdcrm_test_connection_button_id" class="button-primary" value="Test Connection to Pipedrive CRM" />
                        <span id="wpgf2pdcrm_test_connection_ajax_loader_id" style="display: none;">
							<img src="'.plugin_dir_url("").'gravity-forms-to-pipe-drive-crm/images/ajax-loader.gif" />
						</span>
					  </p>';

			}
		}
		
		public function plugin_settings_icon(){
			return '<img src="'.$this->_wpgf2pdcrm_plugin_folder_url.'images/h4wp-logo.png" id="h4wp-plugin-icon" alt="Help For WordPress Logo" />';
		}
		
		public function form_settings( $form ) {
			$license_key = get_option ('wpgf2pdcrm_license_key', '' );
			$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
			if( !$license_key || $license_key_status != 'valid' ){
				echo '<br /><br />';
				echo '<h4>Please <a href="'.$this->_wpgf2pdcrm_plugin_page_url.'">activate your license</a> first</h4>';
				
				return;
			}
			
			$pipedrive_token = get_option( $this->_wpgf2pdcrm_token_option_name, '' );
			if ( ! $pipedrive_token ){
				$addon_setting_page = add_query_arg( array( 'page' => 'gf_settings', 'subview' => 'gf2pdcrm' ),  admin_url( 'admin.php' ) );
				echo '<p style="font-size:14px;">Please go to <a href="'.$addon_setting_page.'">addon setting page</a> to save your Pipedrive token. You may get it from your Pipedrive Account-->Settings-->API</p>';
				return;
			}
			
			
			
			if ( ! $this->_multiple_feeds || $this->is_detail_page() ) {
				// feed edit page
				$feed_id = $this->_multiple_feeds ? $this->get_current_feed_id() : $this->get_default_feed_id( $form['id'] );
				
				/*
				 * Compatiable with feed setting before version 2.5 ( no feed_behaviour )
				 */
				$this->wpgf2pdcrm_comatiable_with_feed_settings_no_feed_behaviour( $feed_id );
	
				$this->feed_edit_page( $form, $feed_id );
			} else {
				// feed list UI
				$this->feed_list_page( $form );
			}
		}

        public function feed_settings_fields() {
            return array(
				//group
                array(
                    "title"  => "",
                    "fields" => array(
                        array(
                            "label"   => "Feed name",
                            "type"    => "text",
                            "name"    => "feedName",
                            "tooltip" => "<h6>Feed name</h6>",
                            "class"   => "medium"
                        ),
						array(
                            "label"   => "Feed behaviour",
                            "type"    => "select",
                            "name"    => "feed_behaviour",
							'onchange' => "jQuery(this).parents('form').submit();",
							'choices'  => array(
												array(
													'label' => 'Select a behaviour',
													'value' => ''
												),
												array(
													'label' => 'Create a contact only in Pipedrive',
													'value' => 'only_contact'
												),
												array(
													'label' => 'Create an activity only in Pipedrive',
													'value' => 'only_activity'
												),
												array(
													'label' => 'Create a contact / a deal / an activity in Pipedrive',
													'value' => 'contact_n_deal',
												),
                                                array(
													'label' => 'Create a contact / a lead in Pipedrive',
													'value' => 'contact_n_lead',
												)
											),
							'default_value'	=> '',
                            "tooltip" => "<h6>Feed behaviour</h6>Choose to create a contact / a deal / a lead / an activity.",
                            "class"   => "medium"
                        )
					 )
				),
				array(
					"title" => "Settings for creating a contact / a deal / an activity",
					'dependency' => array(
						'field'  => 'feed_behaviour',
						'values' => array( 'contact_n_deal' )
					),
					"fields" => array(
                        array(
							'name'     => 'pipeline_list_select',
							'label'    => 'Pipeline List',
							'type'     => 'pipeline_list',
							'required' => false,
							'tooltip'  => '<h6>Pipeline List</h6>Select the Pipeline list you would like to add your deal to.',
							'class'    => 'medium'
						),
						array(
							'dependency'  => 'pipeline_list_select',
							'name'     => 'stage_list_select',
							'label'    => 'Stage List',
							'type'     => 'stage_list',
							'required' => false,
							'tooltip'  => '<h6>Stage List</h6>Select the Stage you would like to add your deal to.',
							'class'    => 'medium'
						),
						array(
							'dependency'  => 'stage_list_select',
							'name'     => 'owner_list_select',
							'label'    => 'User ID of owner',
							'type'     => 'owner_list',
							'required' => false,
							'tooltip'  => '<h6>Owner</h6>ID of the user who will be marked as the owner of the deal, person and organisation. If omitted, the authorized user ID will be used.',
							'class'    => 'medium'
						),	
						array(
							'dependency'  => 'stage_list_select',
							'name'     => 'check_for_duplicates',
							'label'    => 'Check for duplicates',
							'type'     => 'duplicate_checking_raido',
							'required' => false,
							'tooltip'  => '<h6>Duplicate checking</h6>Will detect based on email for person and name for organisation. <br />If person or organisation exist the lead will be associated with the existing contact in PipeDrive ',
							'class'    => 'medium'
						),
						array(
							'dependency'  => 'stage_list_select',
                            "name" => "pipedrive_map",
                            "label" => "Map Fields",
                            "type" => "deal_field_map",
							'required' => false,
                            "field_map" => array() //the fields will be orgainsed in field_map overwrite function
                        )
                    )
                ),
				array(
					"title" => "Settings for creating a contact only",
					'dependency' => array(
						'field'  => 'feed_behaviour',
						'values' => array( 'only_contact' )
					),
					"fields" => array(
						array(
							'name'     => 'owner_id_ctct_o',
							'label'    => 'User ID of owner',
							'type'     => 'owner_list',
							'required' => false,
							'tooltip'  => '<h6>Owner</h6>ID of the user who will be marked as the owner of the deal, person and organisation. If omitted, the authorized user ID will be used.',
							'class'    => 'medium'
						),
						array(
							'name'     => 'check_for_duplicates_ctct_o',
							'label'    => 'Check for duplicates',
							'type'     => 'duplicate_checking_raido',
							'required' => false,
							'tooltip'  => '<h6>Duplicates checking</h6>Detect email for person, detect name for organisation.',
							'class'    => 'medium'
						),
						array(
                            "name" => "pd_map_ctct_o",
                            "label" => "Map Fields",
                            "type" => "field_map_contact_only",
							'required' => false,
                            "field_map" => array() //the fields will be orgainsed in field_map overwrite function
                        ),
                   )
                ),
				array(
					"title" => "Settings for creating an activity only",
					'dependency' => array(
						'field'  => 'feed_behaviour',
						'values' => array( 'only_activity' )
					),
					"fields" => array(
						array(
							'name'     => 'owner_id_activity_o',
							'label'    => 'User ID of owner',
							'type'     => 'owner_list',
							'required' => false,
							'tooltip'  => '<h6>Owner</h6>ID of the user who will be marked as the owner of the deal, person and organisation. If omitted, the authorized user ID will be used.',
							'class'    => 'medium'
						),
						array(
                            "name" => "pd_map_activity_o",
                            "label" => "Map Fields",
                            "type" => "field_map_activity_only",
							'required' => false,
                            "field_map" => array() //the fields will be orgainsed in field_map overwrite function
                        ),
                   )
                ),
                
                array(
					"title" => "Settings for creating a contact / a lead",
					'dependency' => array(
						'field'  => 'feed_behaviour',
						'values' => array( 'contact_n_lead' )
					),
					"fields" => array(
                        array(
							'name'     => 'owner_id_lead',
							'label'    => 'User ID of owner',
							'type'     => 'owner_list',
							'required' => false,
							'tooltip'  => '<h6>Owner</h6>ID of the user who will be marked as the owner of the deal, person and organisation. If omitted, the authorized user ID will be used.',
							'class'    => 'medium'
						),
                        array(
							'name'     => 'check_for_duplicates_lead',
							'label'    => 'Check for duplicates',
							'type'     => 'duplicate_checking_raido',
							'required' => false,
							'tooltip'  => '<h6>Duplicates checking</h6>Detect email for person, detect name for organisation.',
							'class'    => 'medium'
						),
                        array(
							'name'     => 'lead_labels',
							'label'    => 'Lead Labels',
							'type'     => 'field_lead_labels',
							'required' => false,
							'tooltip'  => '<h6>Lead Labels</h6>Select the labels you would like to add to your lead.',
							'class'    => 'medium'
						),
						
						array(
                            "name" => "lead_field_map",
                            "label" => "Map Fields",
                            "type" => "field_lead_field_map",
							'required' => false,
                            "field_map" => array() //the fields will be orgainsed in field_map overwrite function
                        )
                    )
                ),
                
				array(
					"title" => 'Feed Conditions',
					"fields" => array(
                        array(
                            "name" => "condition",
                            "label" => "Condition",
                            "type" => "feed_condition",
                            "checkbox_label" => 'Enable Condition',
                            "instructions" => "Process this feed if",
                        )
                    )
                )
            );
        }

        public function feed_list_columns() {
            return array(
                'feedName' => 'Name',
				'feed_behaviour' => 'Behaviour',
				'owner_list_select' => 'Owner',
                'pipeline_list_select' => 'Pipeline',
				'stage_list_select' => 'Stage',
            );
        }
		
		// customize the value of pipeline_list_select before it's rendered to the list
        public function get_column_value_owner_list_select($feed){
			$user_name = '';
			
			$users_data = get_option( $this->_wpgf2pdcrm_users_option_name, '' );
			if( is_array($users_data) && count($users_data) > 0 && isset($feed["meta"]["owner_list_select"]) ){
				$owner_ID = $feed["meta"]["owner_list_select"];
				if( isset( $users_data[$owner_ID] ) ){
					$user_name = $users_data[$owner_ID]['name'];
				}
			}
			
            return $user_name;
        }
		
		// customize the value of feed_behaviour before it's rendered to the list
        public function get_column_value_feed_behaviour($feed){
			$value = 'Deal with contact / activity';
			if( is_array($feed) && isset($feed['meta']) && is_array($feed['meta']) && isset($feed['meta']['feed_behaviour']) ){
					
			    if( $feed["meta"]["feed_behaviour"] == 'only_contact' ){
				    $value = 'Contact only';
                }else if( $feed["meta"]["feed_behaviour"] == 'only_activity' ){
                    $value = 'Activity only';
                }else if( $feed["meta"]["feed_behaviour"] == 'contact_n_lead' ){
                    $value = 'Lead with contact';
                }
				
			}
			
            return $value;
        }
		
		// customize the value of pipeline_list_select before it's rendered to the list
        public function get_column_value_pipeline_list_select($feed){
			$piple_line_name = '';
			
			$pipelines_n_stages = get_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, '' );
			if( is_array($pipelines_n_stages) && count($pipelines_n_stages) > 0 && isset($feed["meta"]["pipeline_list_select"]) ){
				foreach ( $pipelines_n_stages as $pipeline_id => $pipeline_obj ) {
					$options[] = array(
					'label' => esc_html( $pipeline_obj['name'] ),
					'value' => esc_attr( $pipeline_id )
					);
					if( $pipeline_id == $feed["meta"]["pipeline_list_select"] ){
						$piple_line_name = "<b>" .esc_html( $pipeline_obj['name'] )."</b>";
						break;
					}
				}
			}
			
            return $piple_line_name;
        }
		
		// customize the value of stage_list_select before it's rendered to the list
		public function get_column_value_stage_list_select($feed){
			$stage_name = '';
			
			$pipelines_n_stages = get_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, '' );
			if( is_array($pipelines_n_stages) && count($pipelines_n_stages) > 0 && 
			    isset($feed["meta"]["pipeline_list_select"]) && isset($feed["meta"]["stage_list_select"]) ){
				foreach ( $pipelines_n_stages as $pipeline_id => $pipeline_obj ) {
					if( $pipeline_id != $feed["meta"]["pipeline_list_select"] ){
						continue;
					}
					if( is_array($pipeline_obj['stages']) && count($pipeline_obj['stages']) > 0 ){
						foreach( $pipeline_obj['stages'] as $stage_id => $stage_name ){
							if( $stage_id == $feed["meta"]["stage_list_select"] ){
								$stage_name = "<b>" .esc_html( $stage_name )."</b>";
								break;
							}
						}
					}
					break;
				}
			}
			
            return $stage_name;
        }
		
		function get_field_value_by_id( $field_id, $entry, $form ){
			$field_value = '';
			
			//gravity form's built-in fields
			switch( $field_id ){
				case 'id':
					$field_value = $entry['id'];
				break;
				case 'date_created':
					$field_value = $entry['date_created'];
				break;
				case 'date_created_ymd':
					$field_value_array = explode(' ', $entry['date_created']);
					$field_value = trim($field_value_array[0]);
				break;
				case 'ip':
					$field_value = $entry['ip'];
				break;
				case 'source_url':
					$field_value = $entry['source_url'];
				break;
				case 'form_title':
					$field_value = $form['title'];
				break;
				default:
					$field      = RGFormsModel::get_field( $form, $field_id );
					$input_type  = RGFormsModel::get_input_type( $field );
					$field_value = RGFormsModel::get_lead_field_value($entry, $field);
					if( $input_type == 'address' && is_array($field_value) ){
						if( $field_id == $field['id'] ){
							//full address
                            foreach( $field_value as $key => $val ){
                                if( $val == '' ){
                                    unset($field_value[$key]);
                                }
                            }
							$field_value = implode( ', ', $field_value );
						}else{
						    $field_value = $field_value[$field_id];
						}
					}else if( $input_type == 'name' && is_array($field_value) ){
                        if( $field_id == $field['id'] ){
                            foreach( $field_value as $key => $val ){
                                if( $val == '' ){
                                    unset($field_value[$key]);
                                }
                            }
							//full name
							$field_value = implode( ' ', $field_value );
						}else{
						$field_value = $field_value[$field_id];
						}
					}else if( $input_type == 'checkbox' && is_array($field_value) ){
						$field_value = implode(',', $field_value);
					}else if( is_array($field_value) ){
						$field_value = implode(' ', $field_value);
					}
				break;
			}
			
			return $field_value;
		}

        public function process_feed($feed, $entry, $form){
			
            $entry_note_array = array();
			$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_init();
			
			$form_id = $form['id'];
			
			$wpgf2pdcrm_license_key = trim(get_option('wpgf2pdcrm_license_key'));
			$wpgf2pdcrm_license_key_status = trim(get_option('wpgf2pdcrm_license_key_status'));
			if( !$wpgf2pdcrm_license_key || $wpgf2pdcrm_license_key_status != 'valid' ){
				delete_option( 'wpgf2pdcrm_license_key_status' );
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'license', 'You have not a valid license please activate the plugin first.' );
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
				return;
			}
			
			//if the entry marked as spam by Gravity Forms then don't post
			if( isset($entry['status']) && $entry['status'] == 'spam' ){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'Entry was marked as spam', 'Entry ID: '.$entry['id'] );
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
				return;
			}
		
			$feed_behaviour = 'contact_n_deal';
			if( $feed && is_array($feed) && isset($feed['meta']) && is_array($feed['meta']) && isset($feed['meta']['feed_behaviour']) && 
				$feed['meta']['feed_behaviour']){
				
				$feed_behaviour = $feed['meta']['feed_behaviour'];
			}
			
			$field_map = '';
			if( $feed_behaviour == 'only_contact' ){
				$field_map = $this->get_field_map_fields( $feed, 'pd_map_ctct_o' );
			}else if( $feed_behaviour == 'only_activity' ){
				$field_map = $this->get_field_map_fields( $feed, 'pd_map_activity_o' );
			}else if( $feed_behaviour == 'contact_n_lead' ){
				$field_map = $this->get_field_map_fields( $feed, 'lead_field_map' );
			}else{
				$field_map = $this->get_field_map_fields( $feed, 'pipedrive_map' );
			}

            $token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
			if( $token_saved == "" ){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'No Pipedrive token', 'Please save Pipedrive token first.' );
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
                return;
			}

			//organisation data
			$new_organization = false;
			$new_person = false;
			$new_organization_id = '';
			$new_person_id = '';
			$exsit_org_id = '';
			$exsit_person_id = '';
			$owner_id = '';
			$notes_content = array();
			$file_url_to_upload_array = array();
			$data_to_post = array();
			$organisation_custom_fields = array();
			$people_custom_fields = array();
			$data_to_post = array();

            foreach( $field_map as $pipedrive_field => $field_id ){
				if( !$field_id ){
					continue;
				}
				
				if( $pipedrive_field == 'title' ){
					$gf_fields_array = explode(',', $field_id);//Deal title supports multiple fields
					$deal_title_value_array = array();
					foreach( $gf_fields_array as $gf_field_field_id ){
						if( strpos( $gf_field_field_id, $this->_wpgf2pdcrm_deal_title_custom_text_key_prefix ) !== false ){
							$deal_title_value_array[] = $this->wpgf2pdcrm_get_custom_text_by_key( $gf_field_field_id );
						}else{
							$deal_title_value_array[] = $this->get_field_value_by_id( $gf_field_field_id, $entry, $form );
						}
					}
					$data_to_post[$pipedrive_field] = implode( ' ', $deal_title_value_array );
					
					continue;
				}else if( $pipedrive_field == 'content' ){ 
					$default_choices = $this->get_field_map_choices( $form_id );
					$gf_fields_array = explode(',', $field_id);//Deal title supports multiple fields
					$deal_notes_value_array = array();
					foreach( $gf_fields_array as $gf_field_field_id ){
						$field_value = $this->get_field_value_by_id( $gf_field_field_id, $entry, $form );
						$field       = RGFormsModel::get_field( $form, $gf_field_field_id );
						$field_label = '';
						if( $field ){
							$field_label = $field['label'];
                            if( $field['type'] == 'name' || $field['type'] == 'address' ){
                                foreach( $field['inputs'] as $input_single ){
                                    if( $input_single['id'] == $gf_field_field_id ){
                                        $field_label = $field_label.'.'.$input_single['label'];
                                    }
                                }
                            }
						}else{
							foreach( $default_choices as $choice_val ){
								if( $choice_val['value'] == $gf_field_field_id ){
									$field_label = $choice_val['label'];
								}
							}
						}
						$notes_content[$gf_field_field_id] = array('label' => $field_label, 'content' => $field_value);
					}
					continue;
				}else{
					$field_value = $this->get_field_value_by_id( $field_id, $entry, $form );
				}
				
				switch( $pipedrive_field ){
					case 'new_org':
						if( $field_value ){
							$new_organization = true;
							$data_to_post['new_org'] = $field_value;
						}
					break;
					case 'name':
                    case 'name_additional':
						$new_person = true;
						if( !isset($data_to_post['name']) ){
							$data_to_post['name'] = $field_value.' ';
						}else{
							$data_to_post['name'] .= $field_value.' ';
						}
					break;
					case 'file':
						$file_url_to_upload_array[$field_id] = $field_value;
					break;
					case 'content':
						$field       = RGFormsModel::get_field( $form, $field_id );
						$field_label = $field['label'];
						$notes_content[$field_id] = array('label' => $field_label, 'content' => $field_value);
					break;
					case 'org_id':
                    case 'organization_id':
						$field_id_array = explode( '#', $field_id );
						if( count($field_id_array) > 1 ){
							if( $field_id_array[1] == 'PD' ){
								$data_to_post['org_id'] = $field_id_array[0];
								$exsit_org_id = $field_id_array[0];
							}else if( $field_id_array[1] == 'GF' && $field_value ){
								$data_to_post['org_id'] = $field_value;
								$exsit_org_id = $field_value;
							}
						}else{
							if( $field_value ){
								$data_to_post['org_id'] = $field_value;
								$exsit_org_id = $field_value;
							}
						}
					break;
					case 'person_id':
						$field_id_array = explode( '#', $field_id );
						if( count($field_id_array) > 1 ){
							if( $field_id_array[1] == 'PD' ){
								$person_org_id_array = explode( '-', $field_id_array[0] );
								$data_to_post['person_id'] = $person_org_id_array[0];
								$exsit_person_id = $person_org_id_array[0];
							}else if( $field_id_array[1] == 'GF' && $field_value ){
								$data_to_post['person_id'] = $field_value;
								$exsit_person_id = $field_value;
							}
						}else{
							if( $field_value ){
								$data_to_post['person_id'] = $field_value;
								$exsit_person_id = $field_value;
							}
						}
					break;
					case 'type':
						$field_id_array = explode( '#', $field_id );
						if( count($field_id_array) > 1 ){
							if( $field_id_array[1] == 'PD' ){
								$activity_type_id_array = explode( '-', $field_id_array[0] );
								$data_to_post['type'] = $activity_type_id_array[0];
							}else if( $field_id_array[1] == 'GF' && $field_value ){
								$data_to_post['type'] = $field_value;
							}
						}else{
							if( $field_value ){
								$data_to_post['type'] = $field_value;
							}
						}
					break;
                    case 'product_id':
						$field_id_array = explode( '#', $field_id );
						if( count($field_id_array) > 1 ){
							if( $field_id_array[1] == 'PD' ){
								$person_org_id_array = explode( '-', $field_id_array[0] );
								$data_to_post['product_id'] = $person_org_id_array[0];
							}else if( $field_id_array[1] == 'GF' && $field_value ){
								$data_to_post['product_id'] = $field_value;
							}
						}else{
							if( $field_value ){
								$data_to_post['product_id'] = $field_value;
							}
						}
					break;
					default:
						//process value for file upload => single text
						if( json_decode($field_value) ){
							$field_value_array = json_decode( $field_value );
							if( is_array($field_value_array) && count($field_value_array) > 0 ){
								$files_processed_array = array();
								foreach( $field_value_array as $val ){
									$decoded_array = json_decode($val);
									if( $decoded_array && is_array($decoded_array) ){
										$files_processed_array = array_merge( $files_processed_array, $decoded_array );
									}else{
										$files_processed_array[] = $val;
									}
								}
								$field_value = implode(',', $files_processed_array );
							}
						}
						
				
						$custom_field_id = $this->wpgf2pdcrm_custom_fields_name_mapping_get_r( $pipedrive_field );
						if( $custom_field_id ){
							$pipedrive_field = $custom_field_id;
						}
						//if new organisation is true and there's custom fields mapped then organise custom fields
						if( substr($pipedrive_field, 0, 8) == '_cf_org_' ){
							if( $new_organization ){
								$organisation_custom_fields[str_replace('_cf_org_', '', $pipedrive_field)] = $field_value;
							}
						}else if( substr($pipedrive_field, 0, 11) == '_cf_people_' ){
							if( $new_person ){
								$people_custom_fields[str_replace('_cf_people_', '', $pipedrive_field)] = $field_value;
							}
						}else{
							$data_to_post[$pipedrive_field] = $field_value;
						}
					break;
				}
			}

            if( count($data_to_post) < 1 ){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'No mapping saved', 'You saved the feed, but there is not field map choosen.' );
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
				return;
			}

            $checking_for_duplicates = false;
			if( $feed_behaviour == 'only_contact' ){
				if( isset($feed['meta']['check_for_duplicates_ctct_o']) ){
					$checking_for_duplicates = $feed['meta']['check_for_duplicates_ctct_o'] == 'YES' ? true : false;
				}
			}else if( $feed_behaviour == 'contact_n_lead' ){
				if( isset($feed['meta']['check_for_duplicates_lead']) ){
					$checking_for_duplicates = $feed['meta']['check_for_duplicates_lead'] == 'YES' ? true : false;
				}
			}else{
				if( isset($feed['meta']['check_for_duplicates']) ){
					$checking_for_duplicates = $feed['meta']['check_for_duplicates'] == 'YES' ? true : false;
				}
			}

			if( $feed_behaviour == 'only_contact' ){
				if( isset($feed['meta']['owner_id_ctct_o']) && $feed['meta']['owner_id_ctct_o'] ){
					$owner_id = $feed['meta']['owner_id_ctct_o'];
				}
			}else if( $feed_behaviour == 'only_activity' ){
				if( isset($feed['meta']['owner_id_activity_o']) && $feed['meta']['owner_id_activity_o'] ){
					$owner_id = $feed['meta']['owner_id_activity_o'];
				}
			}else if( $feed_behaviour == 'contact_n_lead' ){
				if( isset($feed['meta']['owner_id_lead']) && $feed['meta']['owner_id_lead'] ){
					$owner_id = $feed['meta']['owner_id_lead'];
				}
			}else{
				if( isset($feed['meta']['owner_list_select']) && $feed['meta']['owner_list_select'] ){
					$owner_id = $feed['meta']['owner_list_select'];
				}
			}
			
			if( isset($data_to_post['new_org']) ){
				$data_to_post['new_org'] = trim( $data_to_post['new_org'] );
				if( !$data_to_post['new_org'] ){
					unset( $data_to_post['new_org'] );
				}
			}
			if( isset($data_to_post['name']) ){
				$data_to_post['name'] = trim( $data_to_post['name'] );
				if( !$data_to_post['name'] ){
					unset( $data_to_post['name'] );
				}
			}

			//Deal title is mandary for Create contact and deal
			if( $feed_behaviour == 'only_contact' ){
				if( (!isset($data_to_post['new_org']) || trim($data_to_post['new_org']) == "") &&
					(!isset($data_to_post['name']) || trim($data_to_post['name']) == "") ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'Create contact only', 'no organisation name or person name existed' );
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
					return;
				}
			}else if( $feed_behaviour == 'only_activity' ){
				if( (!isset($data_to_post['subject']) || trim($data_to_post['subject']) == "") &&
					(!isset($data_to_post['type']) || trim($data_to_post['type']) == "") ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'Create activity only', 'no subject or type existed' );
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
					return;
				}
			}else if( $feed_behaviour == 'contact_n_lead' ){
				if( !isset($data_to_post['title']) || trim($data_to_post['title']) == "" ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'No Lead Title', 'Lead title is required.' );
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
					return;
				}
			}else{
				if( !isset($data_to_post['title']) || trim($data_to_post['title']) == "" ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'No Deal Title', 'Deal title is required.' );
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
					return;
				}
			}

            //create organization
			if( $new_organization && 
				isset($data_to_post['new_org']) && trim($data_to_post['new_org']) &&
				$exsit_org_id == "" ){ //if user selected exist owner id and it is a value then use it, otherwise create a new organization

				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_org', 'Need create new org ' );
				$error_message = '';
				
				//check_for_duplicates
				$duplicate_organisation_id = false;
				if( $checking_for_duplicates ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'check_for_duplicates', 'Duplicates checking enabled!' );
					
					$duplicate_organisation_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_get_org_by_name( $token_saved, $data_to_post['new_org'] );
				}

                if( $duplicate_organisation_id ){
					$data_to_post['org_id'] = $duplicate_organisation_id;
					$exsit_org_id = $duplicate_organisation_id;
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'duplicate_person_exist', 'Duplicate person existed, ID: '.$duplicate_organisation_id );
				}else{
					//organise organisation address
					$address_vales_str = '';
					$address_values_array = array();
					if( isset($data_to_post['street']) && trim($data_to_post['street']) ){
						$address_values_array['street'] = trim($data_to_post['street']);
					}
					if( isset($data_to_post['addressline2']) && trim($data_to_post['addressline2']) ){
						$address_values_array['street'] .= ' '.trim($data_to_post['addressline2']);
					}
					if( isset($data_to_post['city']) && trim($data_to_post['city']) ){
						$address_values_array['city'] = trim($data_to_post['city']);
					}
					if( isset($data_to_post['state']) && trim($data_to_post['state']) ){
						$address_values_array['state_postcode'] = trim($data_to_post['state']);
					}
					if( isset($data_to_post['postcode']) && trim($data_to_post['postcode']) ){
						$address_values_array['state_postcode'] .= ' '.trim($data_to_post['postcode']);
					}
					if( isset($data_to_post['country']) && trim($data_to_post['country']) ){
						$address_values_array['country'] = trim($data_to_post['country']);
					}
		
					if( count($address_values_array) > 0 ){
						$address_vales_str = implode( ', ', $address_values_array );
					}
					//create organisation
					$new_organization_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_organisation( $token_saved, $data_to_post['new_org'], $owner_id, $address_vales_str, $organisation_custom_fields, $error_message);
					if( !$new_organization_id ){
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_org_failed', 'Create org failed: '.$error_message );
                        $entry_note_array[] = 'There was an error injecting this entry to Pipedrive CRM as a organisation '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_message;
					}else{
						$data_to_post['org_id'] = $new_organization_id;
						$exsit_org_id = $new_organization_id;
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_org_success', 'Create org success org ID: '.$new_organization_id );
                        $entry_note_array[] = 'This record was automatically submitted to Pipedrive as an organisation '.date('Y-m-d H:i:s', current_time('timestamp'));
					}
				}
				
				unset($data_to_post['new_org']);
			}
			
			unset($data_to_post['street']);
			unset($data_to_post['addressline2']);
			unset($data_to_post['city']);
			unset($data_to_post['state']);
			unset($data_to_post['postcode']);
			unset($data_to_post['country']);
			
			if( $new_person && 
				isset($data_to_post['name']) && trim($data_to_post['name']) &&
				$exsit_person_id == ""){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_person', 'Need create new person ' );
				$error_message = '';
				$email_array = array();
				$phone_array = array();
                
				if( isset($data_to_post['email']) ){
					$email_array[] = array( 
                                        'label' => 'work', 
                                        'value' => $data_to_post['email'], 
                                        'primary' => true 
                                     );
				}
                if( isset($data_to_post['email_home']) && $data_to_post['email_home'] ){
                    $email_array[] = array( 
                                            'label' => 'home', 
                                            'value' => $data_to_post['email_home'], 
                                            'primary' => false 
                                         );
                }
                if( isset($data_to_post['email_other']) && $data_to_post['email_other'] ){
                    $email_array[] = array( 
                                            'label' => 'other', 
                                            'value' => $data_to_post['email_other'], 
                                            'primary' => false 
                                         );
                }
				if( isset($data_to_post['phone']) ){
					$phone_array[] = array( 
                                        'label' => 'work', 
                                        'value' => $data_to_post['phone'], 
                                        'primary' => true 
                                     );
				}
				if( isset($data_to_post['phone_home']) && $data_to_post['phone_home'] ){
                    $phone_array[] = array( 
                                            'label' => 'home', 
                                            'value' => $data_to_post['phone_home'], 
                                            'primary' => true 
                                         );
                }
                if( isset($data_to_post['phone_mobile']) && $data_to_post['phone_mobile'] ){
                    $phone_array[] = array( 
                                            'label' => 'mobile', 
                                            'value' => $data_to_post['phone_mobile'], 
                                            'primary' => true 
                                         );
                }
                if( isset($data_to_post['phone_other']) && $data_to_post['phone_other'] ){
                    $phone_array[] = array( 
                                            'label' => 'other', 
                                            'value' => $data_to_post['phone_other'], 
                                            'primary' => true 
                                         );
                }
				$duplicate_person_id = false;
                $work_email = array();
                if( isset( $data_to_post['email'] ) && $data_to_post['email'] ){
                    $work_email = explode( ',', $data_to_post['email'] );
                }
				if( $checking_for_duplicates && count($work_email) > 0 ){
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'check_for_duplicates', 'Duplicates checking enabled!' );
					
					$duplicate_person_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_get_person_by_email( $token_saved, $work_email[0] );
				}
				
				if( $duplicate_person_id ){
					$data_to_post['person_id'] = $duplicate_person_id;
					$new_person_id = $duplicate_person_id;
					$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'duplicate_person_existed', 'Duplicate person existed, ID: '.$duplicate_person_id );
				}else{
                    //organise people postal_address
					$postal_address = '';
					$address_values_array = array();
					if( isset($data_to_post['p_postal_street']) && trim($data_to_post['p_postal_street']) ){
						$address_values_array['p_postal_street'] = trim($data_to_post['p_postal_street']);
					}
					if( isset($data_to_post['p_postal_addressline2']) && trim($data_to_post['p_postal_addressline2']) ){
						$address_values_array['p_postal_street'] .= ' '.trim($data_to_post['p_postal_addressline2']);
					}
					if( isset($data_to_post['p_postal_city']) && trim($data_to_post['p_postal_city']) ){
						$address_values_array['p_postal_city'] = trim($data_to_post['p_postal_city']);
					}
					if( isset($data_to_post['p_postal_state']) && trim($data_to_post['p_postal_state']) ){
						$address_values_array['p_postal_state_postcode'] = trim($data_to_post['p_postal_state']);
					}
					if( isset($data_to_post['p_postal_postcode']) && trim($data_to_post['p_postal_postcode']) ){
						$address_values_array['p_postal_state_postcode'] .= ' '.trim($data_to_post['p_postal_postcode']);
					}
					if( isset($data_to_post['p_postal_country']) && trim($data_to_post['p_postal_country']) ){
						$address_values_array['p_postal_country'] = trim($data_to_post['p_postal_country']);
					}
		
					if( count($address_values_array) > 0 ){
						$postal_address = implode( ', ', $address_values_array );
					}
                    
					$new_person_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_people($token_saved, $data_to_post['name'], $postal_address, $owner_id, $exsit_org_id, $email_array, $phone_array, $people_custom_fields, $error_message);
					if( $new_person_id < 1 ){
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_person_failed', 'Create person failed: '.$error_message );
                        $entry_note_array[] = 'There was an error injecting this entry as a Pipedrive CRM Person record '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_message;
					}else{
						$data_to_post['person_id'] = $new_person_id;
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_person_success', 'Create person success person ID: '.$new_person_id );
                        $entry_note_array[] = 'This record was automatically submitted to Pipedrive as a person '.date('Y-m-d H:i:s', current_time('timestamp'));
					}
				}
			}
			unset($data_to_post['name']);
			unset($data_to_post['email']);
            unset($data_to_post['email_home']);
            unset($data_to_post['email_other']);
			unset($data_to_post['phone']);
            unset($data_to_post['phone_home']);
            unset($data_to_post['phone_mobile']);
            unset($data_to_post['phone_other']);
            
            unset($data_to_post['p_postal_street']);
            unset($data_to_post['p_postal_addressline2']);
            unset($data_to_post['p_postal_city']);
            unset($data_to_post['p_postal_state']);
            unset($data_to_post['p_postal_postcode']);
            unset($data_to_post['p_postal_country']);
			
			unset($data_to_post['new_org']);
			
			if( $feed_behaviour == 'only_contact' ){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'Only contact field_map', $field_map );
			}else if( $feed_behaviour == 'only_activity' ){
				$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'Only activity field_map', $field_map );
				
				//create activity
				if( isset( $data_to_post['subject'] ) && $data_to_post['subject'] && 
					isset( $data_to_post['type'] ) && $data_to_post['type'] ){
					
					$error_msg = '';
                    $subject = $data_to_post['subject'];
                    $type = $data_to_post['type'];
					$done = isset( $data_to_post['done'] ) ? $data_to_post['done'] : '';
					$due_date = isset( $data_to_post['due_date'] ) ? $data_to_post['due_date'] : '';
					$due_time = isset( $data_to_post['due_time'] ) ? $data_to_post['due_time'] : '';
					$duration = isset( $data_to_post['duration'] ) ? $data_to_post['duration'] : '';
					$note = isset( $data_to_post['note'] ) ? $data_to_post['note'] : '';
					$new_activity_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_activity( $token_saved, $owner_id, $subject, $type, $done, 
																											  $due_date, $due_time, 
																						   					  $duration, $note, 0, 0, 0, $error_msg );
					if( !$new_activity_id ){
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_activity_failed', 'Create activity failed: '.$error_msg );
                        $entry_note_array[] = 'There was an error injecting this entry into Pipedrive CMR as an activity '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_msg;
					}else{
						$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_activity_success', 'Create activity success ID: '.$new_activity_id );
                        $entry_note_array[] = 'This record was automatically submitted to Pipedrive as an activity '.date('Y-m-d H:i:s', current_time('timestamp'));
					}
				}
			}else if( $feed_behaviour == 'contact_n_lead' ){
                //change owner deal
				if( $owner_id ){
					$data_to_post['owner_id'] = intval( $owner_id );
				}
                
                //organise labels
                $lead_labels = $this->get_field_map_fields( $feed, 'lead_labels' );
                $label_ids = array();
                if( $lead_labels && is_array( $lead_labels ) && count( $lead_labels ) ){
                    foreach( $lead_labels as $lead_label_id => $value ){
                        if( $value ){
                            $label_ids[] = $lead_label_id;
                        }
                    }
                }
                if( count($label_ids) ){
                    $data_to_post['label_ids'] = $label_ids;
                }
                
                //organise value
                if( isset( $data_to_post['value'] ) ){
                    $value_obj = new stdClass();
                    $value_obj->amount = floatval( $data_to_post['value'] );
                    $value_obj->currency = GFCommon::get_currency();//'USD';
                    $data_to_post['value'] = $value_obj;
                }
                
                $error_msg = '';
				$lead_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_lead( $token_saved, $data_to_post, $error_msg );
                if( $lead_id ){
                    do_action( 'wpgf2pdcrm_after_lead_created', $lead_id );
                    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'field_map', $field_map );
                    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'data_post_to_pipedirve', serialize($data_to_post) );
                    
                    $entry_note_array[] = 'This record was automatically submitted to Pipedrive as a lead '.date('Y-m-d H:i:s', current_time('timestamp'));
                }else{
                    $entry_note_array[] = 'There was an error injecting this entry into Pipedrive as a deal '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_msg;
                }
            }else{
				//change Deal stage
				if( isset($feed['meta']['pipeline_list_select']) && $feed['meta']['pipeline_list_select'] && 
					isset($feed['meta']['stage_list_select']) && $feed['meta']['stage_list_select'] ){
					$data_to_post['stage_id'] = $feed['meta']['stage_list_select'];
				}
				
				//change owner deal
				if( $owner_id ){
					$data_to_post['user_id'] = $owner_id;
				}
				
				$files_processed_array = array();
				if( is_array($file_url_to_upload_array) && count($file_url_to_upload_array) > 0 ){
					foreach( $file_url_to_upload_array as $val ){
						$decoded_array = json_decode($val);
						if( $decoded_array && is_array($decoded_array) ){
							$files_processed_array = array_merge( $files_processed_array, $decoded_array );
						}else{
							$files_processed_array[] = $val;
						}
					}
				}
				
                //organise activity data
				$data_for_activity = array();
				if( isset( $data_to_post['subject'] ) ){
					 $data_for_activity['subject'] = $data_to_post['subject'];
					 unset( $data_to_post['subject'] );
				}
				if( isset( $data_to_post['type'] ) ){
					 $data_for_activity['type'] = $data_to_post['type'];
					 unset( $data_to_post['type'] );
				}
				if( isset( $data_to_post['done'] ) ){
					 $data_for_activity['done'] = $data_to_post['done'];
					 unset( $data_to_post['done'] );
				}
				if( isset( $data_to_post['due_date'] ) ){
					 $data_for_activity['due_date'] = $data_to_post['due_date'];
					 unset( $data_to_post['due_date'] );
				}
				if( isset( $data_to_post['due_time'] ) ){
					 $data_for_activity['due_time'] = $data_to_post['due_time'];
					 unset( $data_to_post['due_time'] );
				}
				if( isset( $data_to_post['duration'] ) ){
					 $data_for_activity['duration'] = $data_to_post['duration'];
					 unset( $data_to_post['duration'] );
				}
				if( isset( $data_to_post['note'] ) ){
					 $data_for_activity['note'] = $data_to_post['note'];
					 unset( $data_to_post['note'] );
				}
                
                //organise product to attach data
				$data_for_product = array();
				if( isset( $data_to_post['product_id'] ) ){
					 $data_for_product['product_id'] = $data_to_post['product_id'];
					 unset( $data_to_post['product_id'] );
				}
                if( isset( $data_to_post['product_price'] ) ){
					 $data_for_product['product_price'] = $data_to_post['product_price'];
					 unset( $data_to_post['product_price'] );
				}
                if( isset( $data_to_post['product_quantity'] ) ){
					 $data_for_product['product_quantity'] = $data_to_post['product_quantity'];
					 unset( $data_to_post['product_quantity'] );
				}
                if( isset( $data_to_post['product_discount'] ) ){
					 $data_for_product['product_discount'] = $data_to_post['product_discount'];
					 unset( $data_to_post['product_discount'] );
				}

                //combine all fields into one note
                if( isset($feed['meta']['pipedrive_map_content_gf2zoho_all_in_one']) &&
                    $feed['meta']['pipedrive_map_content_gf2zoho_all_in_one'] == 'YES' ){
                    if( is_array($notes_content) && count($notes_content) > 0 ){
                        $notes_content['gf2zoho_combin_all_in_one_note'] = true;
                    }
                }
                unset( $data_to_post['content_gf2zoho_all_in_one'] );

                $error_msg = '';
				$dela_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_deal($token_saved, $data_to_post, $notes_content, $files_processed_array, $error_msg );
                if( $dela_id ){
                    do_action( 'wpgf2pdcrm_after_deal_created', $dela_id );
                    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'field_map', $field_map );
                    $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'data_post_to_pipedirve', serialize($data_to_post) );
                    
                    $entry_note_array[] = 'This record was automatically submitted to Pipedrive as a deal '.date('Y-m-d H:i:s', current_time('timestamp'));
                    //create activity
                    if( isset( $data_for_activity['subject'] ) && $data_for_activity['subject'] && 
                        isset( $data_for_activity['type'] ) && $data_for_activity['type'] ){

                        $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'come_to_create_activity', 'Create deal successfully, now come to create activity' );

                        $error_msg = '';
                        $subject = $data_for_activity['subject'];
                        $activity_type = $data_for_activity['type'];
                        $activity_done = isset( $data_for_activity['done'] ) ? $data_for_activity['done'] : '';
                        $activity_due_date = isset( $data_for_activity['due_date'] ) ? $data_for_activity['due_date'] : '';
                        $activity_due_time = isset( $data_for_activity['due_time'] ) ? $data_for_activity['due_time'] : '';
                        $activity_duration = isset( $data_for_activity['duration'] ) ? $data_for_activity['duration'] : '';
                        $activity_note = isset( $data_for_activity['note'] ) ? $data_for_activity['note'] : '';
                        $new_activity_id = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_create_activity( 
                                                        $token_saved, $owner_id, $subject, $activity_type, $activity_done, 
                                                        $activity_due_date, $activity_due_time, $activity_duration, $activity_note, 
                                                                                                                  $dela_id, $new_person_id, $new_organization_id, $error_msg );
                        if( !$new_activity_id ){
                            $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_activity_failed', 'Create activity failed: '.$error_msg );
                            $entry_note_array[] = 'There was an error injecting this entry into Pipedrive as an activity '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_msg;
                        }else{
                            $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'new_activity_success', 'Create activity success ID: '.$new_activity_id );
                            $entry_note_array[] = 'This record was automatically submitted to Pipedrive as an activity '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_msg;
                        }
                    }

                    //attach product to Deal
                    if( ( isset( $data_for_product['product_id'] ) && $data_for_product['product_id'] ) &&
                        ( isset( $data_for_product['product_price'] ) && $data_for_product['product_price'] ) && 
                        ( isset( $data_for_product['product_quantity'] ) && $data_for_product['product_quantity'] )  ){
                        $discount = isset( $data_for_product['product_discount'] ) ? $data_for_product['product_discount']  : 0;
                        $attach_product_return = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_attach_product( 
                                                                                             $token_saved, 
                                                                                             $dela_id,       
                                                                                             $data_for_product['product_id'], 
                                                                                             $data_for_product['product_price'], 
                                                                                             $data_for_product['product_quantity'], 
                                                                                             $discount, 
                                                                                             $error_msg );
                        if( $attach_product_return ){
                            $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'attach_product_to_deal', 'Attach product successfully' );
                        }else{
                            $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_push( 'attach_product_to_deal', 'Attach product failed: '.$error_msg );
                        }
                    }
                }else{
                    $entry_note_array[] = 'There was an error injecting this entry into Pipedrive as a deal '.date('Y-m-d H:i:s', current_time('timestamp'))."\n".$error_msg;
                }
			}
            
            //add note to entry
            if( count($entry_note_array) > 0 ){
                $note_message = implode( "\n", $entry_note_array );
                $feed_name = $feed['meta']['feedName'];
                $this->add_note( $entry['id'], 'Feed Name: '.$feed_name."\n".$note_message );
            }
			
			$this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_debug_show();
        }
		
		public function settings_owner_list( $field, $setting_value = '', $echo = true ) {
			$users_data = get_option( $this->_wpgf2pdcrm_users_option_name, '' );
			$options = array(
				array(
					'label' => 'Select a User',
					'value' => ''
				)
			);
			if( is_array($users_data) && count($users_data) > 0 ){
				foreach ( $users_data as $user_ID => $user_data ) {
					$options[] = array(
					'label' => esc_html( $user_data['name'] ),
					'value' => esc_attr( $user_ID )
					);
				}
			}

			$field['type']     = 'select';
			$field['choices']  = $options;

			$html = $this->settings_select( $field, $setting_value, false );
			
			if ( $echo ) {
				echo $html;
                
                return '';
			}
	
			return $html;
        }
		
		public function settings_duplicate_checking_raido( $field, $setting_value = '', $echo = true ) {
			
			$options = array(
				array(
					'label' => 'Yes',
					'value' => 'YES',
					'class' => 'small'
				),
				array(
					'label' => 'No',
					'value' => 'NO',
					'class' => 'small'
				)
			);
			$field['choices'] = $options;
			$field['default_value'] = 'NO';
			$field['horizontal'] = true;
			$html = $this->settings_radio( $field, $setting_value, false );
			
			if ( $echo ) {
				echo $html;
                
                return '';
			}
	
			return $html;
        }
		
		public function settings_pipeline_list( $field, $setting_value = '', $echo = true ) {
			$pipelines_n_stages = get_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, '' );
			$options = array(
				array(
					'label' => 'Select a Pipeline',
					'value' => ''
				)
			);
			if( is_array($pipelines_n_stages) && count($pipelines_n_stages) > 0 ){
				foreach ( $pipelines_n_stages as $pipeline_id => $pipeline_obj ) {
					$options[] = array(
					'label' => esc_html( $pipeline_obj['name'] ),
					'value' => esc_attr( $pipeline_id )
					);
				}
			}

			$field['type']     = 'select';
			$field['choices']  = $options;
			$field['onchange'] = 'jQuery(this).parents("form").submit();';

			$html = $this->settings_select( $field, $setting_value, false );
			
			if ( $echo ) {
				echo $html;
                
                return '';
			}
	
			return $html;
        }
		
		public function settings_stage_list( $field, $setting_value = '', $echo = true ) {
			$pipeline_id_saved   = $this->get_setting( 'pipeline_list_select' );
            $pipelines_n_stages = get_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, '' );
			$options = array(
				array(
					'label' => 'Select a Stage',
					'value' => ''
				)
			);
			if( is_array($pipelines_n_stages) && count($pipelines_n_stages) > 0 ){
				foreach ( $pipelines_n_stages as $pipeline_id => $pipeline_obj ) {
					if( $pipeline_id != $pipeline_id_saved ){
						continue;
					}

					if( is_array($pipeline_obj['stages']) && count($pipeline_obj['stages']) > 0 ){
						foreach( $pipeline_obj['stages'] as $stage_id => $stage_name ){
							$options[] = array(
								'label' => esc_html( $stage_name ),
								'value' => esc_attr( $stage_id )
							);
						}
					}
				}
			}
			
			$field['type']     = 'select';
			$field['choices']  = $options;
			$field['onchange'] = 'jQuery(this).parents("form").submit();';
			
			$html = $this->settings_select( $field, $setting_value, false );
			
			if ( $echo ) {
                echo $html;
                return '';
			}
			
			return $html;
        }
		
		public function settings_deal_field_map( $field, $echo = true ) {

			$html      = '';
			
			$form_id = rgget( 'id' );
			
			$html .= '<table class="wpgf2pdcrm-settings-filed-map-header" cellspacing="0" cellpadding="0" style="width:95%">' .
						'<thead>
							<th style="width:50%">Pipedrive Field</th>
							<th style="width:50%">Form Field</th>
						 </thead>
					  </table>';
			$html .= '<div class="wpgf2pdcrm_scroll_content">
					  <div class="wpgf2pdcrm_scroll_container">';
			$html .= '<table class="wpgf2pdcrm-settings-filed-map" cellspacing="0" cellpadding="0" style="width:100%;">
						 <tbody>';
			
			//for deals
			$html .= '<tr><td colspan="4"><h4>Deals</h4></td></tr>';
			$item = 0;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Deals'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				
				if( $child_field_name == 'pipedrive_map_title' ){
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['class'] = 'wpgf2pdcrm-deal-title-selector';
					
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							$this->settings_field_map_deal_title( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}else if( $child_field_name == 'pipedrive_map_person_id' ){
                    //moved to Person section
					continue;
				}else if( $child_field_name == 'pipedrive_map_org_id' ){
					//moved to Organisation section
					continue;
				}else{
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}
					
				$item++;
			}
			//for deals custom fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Deals Custom Fields</h4></td></tr>';
			$item++;
			
			$deals_custom_fields = get_option( $this->_wpgf2pdcrm_deal_custom_field_option_name, '' );
			if( $deals_custom_fields && is_array($deals_custom_fields) && count($deals_custom_fields) > 0 ){
				foreach ( $deals_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			
			//for Notes
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Notes</h4></td></tr>';
			$item++;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Notes'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$field_rebuilt['class'] = 'wpgf2pdcrm-deal-notes-selector';
				$html .= '
					<tr'.$style.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_deal_notes( $field_rebuilt, $form_id ) .
					   '</td>
					</tr>';
					
				$item++;
			}
            
            //for Add products
            $style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Attach Product to Deal</h4></td></tr>';
			$item++;
            foreach ( $this->_wpgf2pdcrm_fields_by_group['Products'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				if( $child_field_name == 'pipedrive_map_product_id' ){
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_deal_product( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';

				}else{
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}
					
				$item++;
			}
            
			//for Organisations
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations</h4></td></tr>';
			$item++;
            
            foreach ( $this->_wpgf2pdcrm_fields_by_group['Deals'] as $key => $child_field ) {
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-assoicate-exist-organisations"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
                
                if( $child_field_name == 'pipedrive_map_org_id' ){
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_deal_org( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
                    $item++;
                    break;
				}
			}
            
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Organisations'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-create-organisations-fields"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$html .= '
					<tr'.$style.$class.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
					   '</td>
					</tr>';
					
				$item++;
			}
			//for Organisations Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
            
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations Custom Fields</h4></td></tr>';
			$item++;
			$organisation_custom_fields = get_option( $this->_wpgf2pdcrm_organisation_custom_field_option_name, '' );
			if( $organisation_custom_fields && is_array($organisation_custom_fields) && count($organisation_custom_fields) > 0 ){
				foreach ( $organisation_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				    $class = ' class="deal-create-organisations-fields"';

					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			//for People
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People</h4></td></tr>';
			$item++;
            
            foreach ( $this->_wpgf2pdcrm_fields_by_group['Deals'] as $key => $child_field ) {
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				$class = ' class="deal-assoicate-exist-person"';
                
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				
				if( $child_field_name == 'pipedrive_map_person_id' ){
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_deal_person( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
                    $item++;
                    break;
				}
			}
            
            $support_postal_address = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_is_postal_address_enable_for_people();
			foreach ( $this->_wpgf2pdcrm_fields_by_group['People'] as $key => $child_field ) {
				if( !$support_postal_address && strpos( $key, 'p_postal_' ) !== false ){
                    continue;
                }
                
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-create-person-fields"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$html .= '
					<tr'.$style.$class.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_select( $field_rebuilt, $form_id );
                if( $key == 'name' ){
                    $field_rebuilt_name_additional = array();
                    $field_rebuilt_name_additional['name'] = $child_field_name.'_additional';
                    $html .= '<br /><br />';
                    $html .= $this->settings_field_map_select( $field_rebuilt_name_additional, $form_id );
                }
                
				$html .= '</td>
					</tr>';
					
				$item++;
			}
			//for People Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People Custom Fields</h4></td></tr>';
			$item++;
			
			$people_custom_fields = get_option( $this->_wpgf2pdcrm_people_custom_field_option_name, '' );
			if( $people_custom_fields && is_array($people_custom_fields) && count($people_custom_fields) > 0 ){
				foreach ( $people_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					$class = ' class="deal-create-person-fields"';
                    
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			
			//for activity
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Activity</h4></td></tr>';
			$item++;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Activity'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'subject' || $key == 'type' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				
				if( $child_field_name == 'pipedrive_map_type' ){
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_activity_type( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';

				}else{
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}
				
				$item++;
			}
			
			$html .= '
					</tbody>
				</table>
				</div>
				</div>';
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		public function settings_field_map_deal_title( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
			
			//upate entry date format
			$new_choices_1 = array();
			$new_choices_2 = array();
			$new_choices_to_add = array( array( 'value' => 'date_created_ymd', 'label' => 'Entry Date(Y-m-d)') );
			if( is_array($field['choices']) && count($field['choices']) > 0 ){
				$index = 0;
				foreach( $field['choices'] as $key => $gf_field ){
					if( $gf_field['value'] == 'date_created' ){
						$gf_field['label'] .= '(Y-m-d H:i:s)';
						
						$field['choices'][$key] = $gf_field;
						
						$new_choices_1 = array_slice($field['choices'], 0, $index + 1, true);
						$new_choices_2 = array_slice($field['choices'], $index + 1, count($field['choices']) - 1, true) ;
						
						break;
					}
					$index++;
				}
			}
			$field['choices'] = array_merge( $new_choices_1, $new_choices_to_add, $new_choices_2 );
			
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
	
			return $this->settings_select_deal_title( $field, false );
	
		}
		
		public function settings_select_deal_title( $field, $echo = true ) {
			
			$random_id = 'deal_title_'.rand( 10000, 99999 );
			
			$field['id'] = $random_id;
			$field['type'] = 'select'; // making sure type is set to select
			$attributes    = $this->get_field_attributes( $field );
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );

			$saved_deal_title_field_array = array();
			if( $value ){
				$saved_deal_title_field_array = explode( ',', $value );
			}
			
			$deal_title_fields_span_str = '';
			foreach( $saved_deal_title_field_array as $field_val ){
				if( strpos( $field_val, $this->_wpgf2pdcrm_deal_title_custom_text_key_prefix ) !== false ){
					$field_label = $this->wpgf2pdcrm_get_custom_text_by_key( $field_val );
					if( $field_label == "" ){
						continue;
					}
				}else{
					$field_label = $this->settings_select_deal_title_get_field_label( $field_val, $field['choices'] );
				}
				$deal_title_fields_span_str .= '<span style="display:inline-block; margin-right:10px;"><a style="cursor: pointer;" class="wpgf2pd-deal-title-values-list-del-icon" rel="'.$field_val.'" relselectorid="'.$random_id.'" style="margin-left:0;">X</a>&nbsp;'.$field_label.'</span>';
			}
					
			$html  = '<div class="wpgf2pd-deal-title-values-list-container" style="margin-left:0;padding-left:0;">' . $deal_title_fields_span_str . '</div>';
			$html .= sprintf( '<span class="wpgf2pdcrm-deal-title-selector-container"><select %1$s>%2$s</select></span>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
            
			$html .= 'OR<span class="wpgf2pdcrm-deal-title-custom-text-container"><input type="text" placeholder="Custom text..." class="gaddon-setting gaddon-select wpgf2pd-deal-title-values-add-custom-text-input" id="wpgf2pdcrm_deal_title_custom_text_'.$random_id.'" rel="'.$random_id.'" />';
            
			$html .= '<input type="button" value="Add..." class="button-secondary wpgf2pd-deal-title-values-add-custom-text-button" rel="'.$random_id.'" /></span>';
			$html .= '<span id="wpgf2pdcrm_deal_title_add_custom_text_ajax_loader_id_'.$random_id.'" style="display: none;">
						<img src="'.plugin_dir_url("").'gravity-forms-to-pipe-drive-crm/images/ajax-loader.gif" />
					  </span>';
            
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_deal_title_val_'.$random_id.'" />';
			
            
            $html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		function settings_select_deal_title_get_field_label( $field_val, $choices ){
			if( !is_array($choices) || count($choices) < 1 ){
				return '';
			}
			foreach( $choices as $choice_val_label ){
				if( $choice_val_label['value'] == $field_val ){
					return $choice_val_label['label'];
				}
			}
			
			return '';
		}
		
		public function settings_field_map_deal_person( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
			
			//upate entry date format
			$new_choices_1 = array();
			$new_choices_2 = array();
			$new_choices_to_add = array( array( 'value' => 'date_created_ymd', 'label' => 'Entry Date(Y-m-d)') );
			if( is_array($field['choices']) && count($field['choices']) > 0 ){
				$index = 0;
				foreach( $field['choices'] as $key => $gf_field ){
					if( $gf_field['value'] == 'date_created' ){
						$gf_field['label'] .= '(Y-m-d H:i:s)';
						
						$field['choices'][$key] = $gf_field;
						
						$new_choices_1 = array_slice($field['choices'], 0, $index + 1, true);
						$new_choices_2 = array_slice($field['choices'], $index + 1, count($field['choices']) - 1, true) ;
						
						break;
					}
					$index++;
				}
			}
			$field['choices'] = array_merge( $new_choices_1, $new_choices_to_add, $new_choices_2 );
			
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
	
			return $this->settings_select_deal_person( $field, false );
	
		}
		
		public function settings_select_deal_person( $field, $echo = true ) {
			
			$random_id = 'deal_person_'.rand( 10000, 99999 );
			
			$field['id'] = 'wpgf2pd_deal_person_select_form_field_id_'.$random_id;
			$field['type'] = 'select'; // making sure type is set to select
			$field['class'] = 'wpgf2pd-deal-person-select-form-field';
			$attributes    = $this->get_field_attributes( $field );
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );
			
			// get all persons which exist in Pipedrive
			$exist_persons_array = get_option( $this->_wpgf2pdcrm_persons_list_option_name );
			$value_array = explode('#', $value);
			$saved_person_id = $value_array[0];
			$saved_type = 'GF';
			if( count($value_array) > 1 ){
				$saved_type = $value_array[1];
			}
			
			$html = '';
			if( $saved_type == 'GF' ){
                //update "Select a field"
                foreach( $field['choices'] as $key => $field_choice ){
                    if( $field_choice['value']  == "" && $field_choice['label'] == 'Select a Field' ){
                        $new_chocie = array( 'value' => '', 'label' => 'Select a Field which contains exist Person ID' );
                        $field['choices'][$key] = $new_chocie;
                        break;
                    }
                }
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-person-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], $saved_person_id ) );
			}else{
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-person-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
			}
            
            if( get_option( $this->_wpgf2pdcrm_enable_cache_people_option, false ) == true ){
                $html .= ' OR </span><br />';
                $html .= '<select class="wpgf2pd-deal-person-select-exist-person" id="wpgf2pd_deal_person_select_exist_person_id_'.$random_id.'" rel="'.$random_id.'" style="margin-top:10px;" />';
                $html .= '<option value="">Select exist person from your Pipedrive</option>';
                if( $exist_persons_array && is_array($exist_persons_array) && count($exist_persons_array) > 0 ){
                    $options_list_str = '';
                    foreach( $exist_persons_array as $person_id => $person_data_node ){
                        if( isset($person_data_node['org_id']) && $person_data_node['org_id'] ){
                            $selected_str = '';
                            if( $saved_type == 'PD' ){
                                if( $person_id.'-'.$person_data_node['org_id'] == $saved_person_id ){
                                    $selected_str = 'selected="selected"';
                                }
                            }
                            $options_list_str .= '<option value="'.$person_id.'-'.$person_data_node['org_id'].'" '.$selected_str.'>'.$person_data_node['name'].' ('.$person_data_node['org_name'].') </option>';
                        }else{
                            $selected_str = '';
                            if( $saved_type == 'PD' ){
                                if( $person_id == $saved_person_id ){
                                    $selected_str = 'selected="selected"';
                                }
                            }
                            $options_list_str .= '<option value="'.$person_id.'" '.$selected_str.'>'.$person_data_node['name'].'</option>';
                        }
                    }
                    $html .= $options_list_str;
                }
                $html .= '</select>';
            }else{
                $html .= '</span>';
            }
            
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
            
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_deal_person_val_'.$random_id.'" />';
            
            
			$html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		public function settings_field_map_deal_org( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
			
			//upate entry date format
			$new_choices_1 = array();
			$new_choices_2 = array();
			$new_choices_to_add = array( array( 'value' => 'date_created_ymd', 'label' => 'Entry Date(Y-m-d)') );
			if( is_array($field['choices']) && count($field['choices']) > 0 ){
				$index = 0;
				foreach( $field['choices'] as $key => $gf_field ){
					if( $gf_field['value'] == 'date_created' ){
						$gf_field['label'] .= '(Y-m-d H:i:s)';
						
						$field['choices'][$key] = $gf_field;
						
						$new_choices_1 = array_slice($field['choices'], 0, $index + 1, true);
						$new_choices_2 = array_slice($field['choices'], $index + 1, count($field['choices']) - 1, true) ;
						
						break;
					}
					$index++;
				}
			}
			$field['choices'] = array_merge( $new_choices_1, $new_choices_to_add, $new_choices_2 );
			
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
	
			return $this->settings_select_deal_org( $field, false );
	
		}
		
		public function settings_select_deal_org( $field, $echo = true ) {
			
			$random_id = 'deal_org_'.rand( 10000, 99999 );
			
			$field['id'] = 'wpgf2pd_deal_org_select_form_field_id_'.$random_id;
			$field['class'] = 'wpgf2pd-deal-org-select-form-field';
			$field['type'] = 'select'; // making sure type is set to select
			$attributes    = $this->get_field_attributes( $field );
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );

            // get all orgs which exist in Pipedrive
			$exist_orgs_array = get_option( $this->_wpgf2pdcrm_organisations_list_option_name );
			$value_array = explode('#', $value);
			$saved_org_id = $value_array[0];
			$saved_type = 'GF';
			if( count($value_array) > 1 ){
				$saved_type = $value_array[1];
			}
			
			$html = '';
			if( $saved_type == 'GF' ){
                //update "Select a field"
                foreach( $field['choices'] as $key => $field_choice ){
                    if( $field_choice['value']  == "" && $field_choice['label'] == 'Select a Field' ){
                        $new_chocie = array( 'value' => '', 'label' => 'Select a Field which contains exist Organisation ID' );
                        $field['choices'][$key] = $new_chocie;
                        break;
                    }
                }
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-org-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], $saved_org_id ) );
			}else if( $saved_type == 'PD' ){
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-org-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
			}
            
            if( get_option( $this->_wpgf2pdcrm_enable_cache_organisations_option, false ) == true ){
                $html .= ' OR </span><br />';
                $html .= '<select class="wpgf2pd-deal-org-select-exist-org" id="wpgf2pd_deal_org_select_exist_org_id_'.$random_id.'" rel="'.$random_id.'" style="margin-top:10px;" />';
                $html .= '<option value="">Select exist organisation from your Pipedrive</option>';
                if( $exist_orgs_array && is_array($exist_orgs_array) && count($exist_orgs_array) > 0 ){
                    $options_list_str = '';
                    foreach( $exist_orgs_array as $org_id => $org_data_node ){
                        $selected_str = '';
                        if( $value == $org_id && $saved_type == 'PD' ){
                            $selected_str = 'selected="selected"';
                        }
                        $options_list_str .= '<option value="'.$org_id.'" '.$selected_str.'>'.$org_data_node['name'].'</option>';
                    }
                    $html .= $options_list_str;
                }
                $html .= '</select>';
            }else{
                $html .= '</span>';
            }
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
            
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_deal_org_val_'.$random_id.'" />';
            
            
			$html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		public function settings_field_map_deal_notes( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
			
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
			
			//upate entry date format
			$new_choices_1 = array();
			$new_choices_2 = array();
			$new_choices_to_add = array( array( 'value' => 'date_created_ymd', 'label' => 'Entry Date(Y-m-d)') );
			if( is_array($field['choices']) && count($field['choices']) > 0 ){
				$index = 0;
				foreach( $field['choices'] as $key => $gf_field ){
					if( $gf_field['value'] == 'date_created' ){
						$gf_field['label'] .= '(Y-m-d H:i:s)';
						
						$field['choices'][$key] = $gf_field;
						
						$new_choices_1 = array_slice($field['choices'], 0, $index + 1, true);
						$new_choices_2 = array_slice($field['choices'], $index + 1, count($field['choices']) - 1, true) ;
						
						break;
					}
					$index++;
				}
			}
			$field['choices'] = array_merge( $new_choices_1, $new_choices_to_add, $new_choices_2 );
			
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
	
			return $this->settings_select_deal_note( $field, false );
	
		}
		
		public function settings_select_deal_note( $field, $echo = true ) {
			
			$random_id = 'deal_note_'.rand( 10000, 99999 );
			
			$field['id'] = $random_id;
			$field['type'] = 'select'; // making sure type is set to select
			$attributes    = $this->get_field_attributes( $field );
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );

            $all_in_one_value = $this->get_setting( $field['name'].'_gf2zoho_all_in_one', rgar( $field, 'NO' ) );

			$saved_deal_title_field_array = array();
			if( $value ){
				$saved_deal_title_field_array = explode( ',', $value );
			}
			
			$deal_title_fields_span_str = '';
			foreach( $saved_deal_title_field_array as $field_val ){
				$field_label = $this->settings_select_deal_notes_get_field_label( $field_val, $field['choices'] );
				$deal_title_fields_span_str .= '<span style="margin-right:10px;"><a style="cursor: pointer;" class="ntdelbutton wpgf2pd-deal-notes-values-list-del-icon" rel="'.$field_val.'" relselectorid="'.$random_id.'">X</a>&nbsp;'.$field_label.'</span>';
			}
					
			$html  = '<div class="wpgf2pd-deal-notes-values-list-container">' . $deal_title_fields_span_str . '</div>';
			$html .= sprintf( '<span class="wpgf2pdcrm-deal-notes-selector-container"><select %1$s>%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
            
            $all_in_one_checked = '';
            if( $all_in_one_value == 'YES' ){
                $all_in_one_checked = ' checked';
            }
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
            
            $html .= '<br /><label><input type="checkbox" name="' . $default_setting_field_name . '_gf2zoho_all_in_one" value="YES"'.$all_in_one_checked.' style="margin-right:5px;"/> Combine all fields into one note</label>';
            
            
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_deal_notes_val_'.$random_id.'" />';
			
            $html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
                return '';
			}
	
			return $html;
		}
        
        public function settings_field_map_deal_product( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
			
			//upate entry date format
			$new_choices_1 = array();
			$new_choices_2 = array();
			$new_choices_to_add = array( array( 'value' => 'date_created_ymd', 'label' => 'Entry Date(Y-m-d)') );
			if( is_array($field['choices']) && count($field['choices']) > 0 ){
				$index = 0;
				foreach( $field['choices'] as $key => $gf_field ){
					if( $gf_field['value'] == 'date_created' ){
						$gf_field['label'] .= '(Y-m-d H:i:s)';
						
						$field['choices'][$key] = $gf_field;
						
						$new_choices_1 = array_slice($field['choices'], 0, $index + 1, true);
						$new_choices_2 = array_slice($field['choices'], $index + 1, count($field['choices']) - 1, true) ;
						
						break;
					}
					$index++;
				}
			}
			$field['choices'] = array_merge( $new_choices_1, $new_choices_to_add, $new_choices_2 );
			
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
	
			return $this->settings_select_deal_product( $field, false );
	
		}
		
		public function settings_select_deal_product( $field, $echo = true ) {
			
			$random_id = 'deal_product_'.rand( 10000, 99999 );
			
			$field['id'] = 'wpgf2pd_deal_product_select_form_field_id_'.$random_id;
			$field['class'] = 'wpgf2pd-deal-product-select-form-field';
			$field['type'] = 'select'; // making sure type is set to select
			$attributes    = $this->get_field_attributes( $field );
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );
			
			// get all products which exist in Pipedrive
			$exist_products_array = get_option( $this->_wpgf2pdcrm_products_list_option_name );
			$value_array = explode('#', $value);
			$saved_product_id = $value_array[0];
			$saved_type = 'GF';
			if( count($value_array) > 1 ){
				$saved_type = $value_array[1];
			}
			
			$html = '';
			if( $saved_type == 'GF' ){
                //update "Select a field"
                foreach( $field['choices'] as $key => $field_choice ){
                    if( $field_choice['value']  == "" && $field_choice['label'] == 'Select a Field' ){
                        $new_chocie = array( 'value' => '', 'label' => 'Select a Field which contains exist Product ID' );
                        $field['choices'][$key] = $new_chocie;
                        break;
                    }
                }
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-product-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], $saved_product_id ) );
			}else if( $saved_type == 'PD' ){
				$html .= sprintf( '<span class="wpgf2pdcrm-deal-product-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
			}
            
            if( get_option( $this->_wpgf2pdcrm_enable_cache_product_list_option, false ) == true ){
                $html .= ' OR </span><br />';
                $html .= '<select class="wpgf2pd-deal-product-select-exist-product" id="wpgf2pd_deal_product_select_exist_product_id_'.$random_id.'" rel="'.$random_id.'" style="margin-top:10px;" />';
                $html .= '<option value="">Select exist product from your Pipedrive</option>';
                if( $exist_products_array && is_array($exist_products_array) && count($exist_products_array) > 0 ){
                    $options_list_str = '';
                    foreach( $exist_products_array as $product_id => $product_data_node ){
                        $selected_str = '';
                        if( $value == $product_id && $saved_type == 'PD' ){
                            $selected_str = 'selected="selected"';
                        }
                        $options_list_str .= '<option value="'.$product_id.'" '.$selected_str.'>'.$product_data_node['name'].'</option>';
                    }
                    $html .= $options_list_str;
                }
                $html .= '</select>';
            }else{
                $html .= '</span>';
            }
            
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_deal_product_val_'.$random_id.'" />';
            
            
			$html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		function settings_select_deal_notes_get_field_label( $field_val, $choices ){
			if( !is_array($choices) || count($choices) < 1 ){
				return '';
			}
			foreach( $choices as $choice_val_label ){
				if( $choice_val_label['value'] == $field_val ){
					return $choice_val_label['label'];
				}
			}
			
			return '';
		}
		
		public function settings_field_map_activity_type( $field, $form_id ) {
            
			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
						
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			// Set default value if aliases are provided.
			if ( rgars( $field, 'default_value/aliases' ) ) {
				
				// Prepare variable to store default value.
				$default_value = null;
				
				// Prepare variable to store suggested field percentage.
				$suggested_field_percent = 0;
				
				foreach ( $field['default_value']['aliases'] as $alias ) {
					
					// Prepare the string we're testing against.
					$alias_string = $field['label'] . ' ' . $alias;
					
					foreach ( $field['choices'] as $choice ) {
						
						// If choice value is empty, skip it.
						if ( rgblank( $choice['value'] ) ) {
							continue;
						}
						
						// Run a string comparison.
						similar_text( $alias_string, $choice['label'], $alias_match );
						
						// If match percentage is higher than current percentage, set this field as suggested field.
						if ( $alias_match > $suggested_field_percent ) {
							$default_value           = $choice['value'];
							$suggested_field_percent = $alias_match;
						}
						
					} 
					
				}
				
				// Set default value.
				$field['default_value'] = $default_value;
				
			}
            
			return $this->settings_select_activity_type( $field, false );
	
		}
		
		public function settings_select_activity_type( $field, $echo = true ) {
			$random_id = 'activity_type_'.rand( 10000, 99999 );

            $field['id'] = 'wpgf2pd_activity_type_select_form_field_id_'.$random_id;
			$field['type'] = 'select'; // making sure type is set to select
			$field['class'] = 'wpgf2pd-activity-type-select-form-field';
			$attributes    = $this->get_field_attributes( $field );
            
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );
			
			// get all activity type which exist in Pipedrive
			$exist_activity_types_array = get_option( $this->_wpgf2pdcrm_activity_types_cache_option );
			$value_array = explode( '#', $value );
			$saved_activity_type = $value_array[0];
			$saved_type = 'GF';
			if( count($value_array) > 1 ){
				$saved_type = $value_array[1];
			}
			
			$html = '';
			if( $saved_type == 'GF' ){
				$html .= sprintf( '<span class="wpgf2pdcrm-activity-type-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], $saved_activity_type ) );
			}else{
				$html .= sprintf( '<span class="wpgf2pdcrm-activity-type-selector-container"><select %1$s rel="'.$random_id.'">%2$s</select>', implode( ' ', $attributes ), $this->get_select_options( $field['choices'], '' ) );
			}
			$html .= ' OR </span><br />';
			$html .= '<select class="wpgf2pd-activity-type-select-exist-type" id="wpgf2pd_activity_type_select_exist_type_id_'.$random_id.'" rel="'.$random_id.'" style="margin-top:10px;" />';
			$html .= '<option value="">Select exist activity type from your Pipedrive</option>';
			if( $exist_activity_types_array && is_array($exist_activity_types_array) && count($exist_activity_types_array) > 0 ){
				$options_list_str = '';
				foreach( $exist_activity_types_array as $activity_type_id => $activity_type_data ){
					$selected_str = '';
					if( $saved_type == 'PD' ){
						if( $activity_type_id == $saved_activity_type ){
							$selected_str = 'selected="selected"';
						}
					}
					$options_list_str .= '<option value="'.$activity_type_id.'" '.$selected_str.'>'.$activity_type_data['name'].'</option>';
				}
				$html .= $options_list_str;
			}
			$html .= '</select>';
            
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
            
			$html .= '<input type="hidden" name="' . $default_setting_field_name . '" value="'.$value.'" id="wpgf2pdcrm_activity_type_val_'.$random_id.'" />';
			
            
            $html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		public function settings_field_map_contact_only( $field, $echo = true ) {

			$html      = '';
			
			$form_id = rgget( 'id' );
			
			$html .= '<table class="wpgf2pdcrm-settings-filed-map-header" cellspacing="0" cellpadding="0" style="width:95%">' .
						'<thead>
							<th style="width:50%">Pipedrive Field</th>
							<th style="width:50%">Form Field</th>
						 </thead>
					  </table>';
			$html .= '<div class="wpgf2pdcrm_scroll_content">
					  <div class="wpgf2pdcrm_scroll_container">';
			$html .= '<table class="wpgf2pdcrm-settings-filed-map" cellspacing="0" cellpadding="0" style="width:100%;">
						 <tbody>';
			
			//for Organisations
			$item = 0;
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations</h4></td></tr>';
			$item++;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Organisations'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'new_org' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$html .= '
					<tr'.$style.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
					   '</td>
					</tr>';
					
				$item++;
			}
			//for Organisations Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations Custom Fields</h4></td></tr>';
			$item++;
			$organisation_custom_fields = get_option( $this->_wpgf2pdcrm_organisation_custom_field_option_name, '' );
			if( $organisation_custom_fields && is_array($organisation_custom_fields) && count($organisation_custom_fields) > 0 ){
				foreach ( $organisation_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			//for People
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People</h4></td></tr>';
			$item++;
            $support_postal_address = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_is_postal_address_enable_for_people();
			foreach ( $this->_wpgf2pdcrm_fields_by_group['People'] as $key => $child_field ) {
				if( !$support_postal_address && strpos( $key, 'p_postal_' ) !== false ){
                    continue;
                }
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'name' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
                
				$html .= '
					<tr'.$style.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
				        $this->settings_field_map_select( $field_rebuilt, $form_id );
                        if( $key == 'name' ){
                            $field_rebuilt_name_additional = array();
                            $field_rebuilt_name_additional['name'] = $child_field_name.'_additional';
                            $html .= '<br /><br />';
                            $html .= $this->settings_field_map_select( $field_rebuilt_name_additional, $form_id );
                        }
                $html .= '       
					    </td>
					</tr>';
					
				$item++;
			}
			//for People Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People Custom Fields</h4></td></tr>';
			$item++;
			
			$people_custom_fields = get_option( $this->_wpgf2pdcrm_people_custom_field_option_name, '' );
			if( $people_custom_fields && is_array($people_custom_fields) && count($people_custom_fields) > 0 ){
				foreach ( $people_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			$html .= '
					</tbody>
				</table>
				</div>
				</div>';
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		public function settings_field_map_activity_only( $field, $echo = true ) {

			$html      = '';
			
			$form_id = rgget( 'id' );
			
			$html .= '<table class="wpgf2pdcrm-settings-filed-map-header" cellspacing="0" cellpadding="0" style="width:95%">' .
						'<thead>
							<th style="width:50%">Pipedrive Field</th>
							<th style="width:50%">Form Field</th>
						 </thead>
					  </table>';
			$html .= '<div class="wpgf2pdcrm_scroll_content">
					  <div class="wpgf2pdcrm_scroll_container">';
			$html .= '<table class="wpgf2pdcrm-settings-filed-map" cellspacing="0" cellpadding="0" style="width:100%;">
						 <tbody>';
			
			//for activity
			$item = 0;
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			//$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations</h4></td></tr>';
			$item++;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Activity'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'subject' || $key == 'type' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];

				if( $child_field_name == 'pd_map_activity_o_type' ){
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_activity_type( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';

				}else{
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}
					
				$item++;
			}

			$html .= '
					</tbody>
				</table>
				</div>
				</div>';
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
        
        public function settings_field_lead_labels( $field, $setting_value = '', $echo = true ) {
			$lead_labels_cache_data = get_option( $this->_wpgf2pdcrm_leads_labels_option_name, '' );

            $checkbox_field = array(
                'name'       => $field['name'],
                'type'       => 'checkbox',
                'horizontal' => true,
                'choices'    => array(),
            );
            
            if( is_array($lead_labels_cache_data) && count($lead_labels_cache_data) > 0 ){
				foreach( $lead_labels_cache_data as $label_id => $label_obj ){
					$checkbox_field['choices'][] = array(
                        'label'    => $label_obj['name'],
                        'name'     => $field['name'] .'_'. $label_id,
                        'value'    => $label_id,
                    );
				}
			}

			$html = $this->settings_checkbox( $checkbox_field, false );
			
			if ( $echo ) {
				echo $html;
                
                return '';
			}
	
			return $html;
        }
        
        public function settings_field_lead_field_map( $field, $echo = true ) {

			$html      = '';
			
			$form_id = rgget( 'id' );
			
			$html .= '<table class="wpgf2pdcrm-settings-filed-map-header" cellspacing="0" cellpadding="0" style="width:95%">' .
						'<thead>
							<th style="width:50%">Pipedrive Field</th>
							<th style="width:50%">Form Field</th>
						 </thead>
					  </table>';
			$html .= '<div class="wpgf2pdcrm_scroll_content">
					  <div class="wpgf2pdcrm_scroll_container">';
			$html .= '<table class="wpgf2pdcrm-settings-filed-map" cellspacing="0" cellpadding="0" style="width:100%;">
						 <tbody>';
			
			//for lead
			$html .= '<tr><td colspan="4"><h4>Lead</h4></td></tr>';
			$item = 0;
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Lead'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				
				if( $child_field_name == 'lead_field_map_title' ){
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['class'] = 'wpgf2pdcrm-deal-title-selector';
					
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							$this->settings_field_map_deal_title( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}else if( $child_field_name == 'lead_field_map_label_ids' ){
                    //moved to Person section
					continue;
				}else if( $child_field_name == 'lead_field_map_person_id' ){
                    //moved to Person section
					continue;
				}else if( $child_field_name == 'lead_field_map_organization_id' ){
					//moved to Organisation section
					continue;
				}else{
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
				}
					
				$item++;
			}
            //as lead use same custom fields as deal so it list deal custom fields
			//for deals custom fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Lead Custom Fields</h4></td></tr>';
			$item++;
			
			$deals_custom_fields = get_option( $this->_wpgf2pdcrm_deal_custom_field_option_name, '' );
			if( $deals_custom_fields && is_array($deals_custom_fields) && count($deals_custom_fields) > 0 ){
				foreach ( $deals_custom_fields as $key => $child_field ) {
					if( $key == 'expected_close_date' ){
                        //because this is a defaul field for lead
                        continue;
                    }
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			
			//for Organisations
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations</h4></td></tr>';
			$item++;
            
            foreach ( $this->_wpgf2pdcrm_fields_by_group['Lead'] as $key => $child_field ) {
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-assoicate-exist-organisations"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
                
                if( $child_field_name == 'lead_field_map_organization_id' ){
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_deal_org( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
                    $item++;
                    break;
				}
			}
            
			foreach ( $this->_wpgf2pdcrm_fields_by_group['Organisations'] as $key => $child_field ) {
				
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-create-organisations-fields"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$html .= '
					<tr'.$style.$class.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_select( $field_rebuilt, $form_id ) .
					   '</td>
					</tr>';
					
				$item++;
			}
			//for Organisations Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
            
			$html .= '<tr'.$style.'><td colspan="4"><h4>Organisations Custom Fields</h4></td></tr>';
			$item++;
			$organisation_custom_fields = get_option( $this->_wpgf2pdcrm_organisation_custom_field_option_name, '' );
			if( $organisation_custom_fields && is_array($organisation_custom_fields) && count($organisation_custom_fields) > 0 ){
				foreach ( $organisation_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				    $class = ' class="deal-create-organisations-fields"';

					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			//for People
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People</h4></td></tr>';
			$item++;
            
            foreach ( $this->_wpgf2pdcrm_fields_by_group['Lead'] as $key => $child_field ) {
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
				$class = ' class="deal-assoicate-exist-person"';
                
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				
				if( $child_field_name == 'lead_field_map_person_id' ){
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_deal_person( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
                    $item++;
                    break;
				}
			}
            
            $support_postal_address = $this->wpgf2pdcrm_api_CLASS->wpgf2pdcrm_pipedrive_crm_is_postal_address_enable_for_people();
			foreach ( $this->_wpgf2pdcrm_fields_by_group['People'] as $key => $child_field ) {
				if( !$support_postal_address && strpos( $key, 'p_postal_' ) !== false ){
                    continue;
                }
                
				$child_field_name = $this->get_mapped_field_name( $field, $key );
				$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
                $class = ' class="deal-create-person-fields"';
				
				$field_rebuilt = array();
				$field_rebuilt['name'] = $child_field_name;
				$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
				$html .= '
					<tr'.$style.$class.'>
						<td style="width:50%">
							<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
						</td>
						<td style="width:50%">' .
						 $this->settings_field_map_select( $field_rebuilt, $form_id );
                if( $key == 'name' ){
                    $field_rebuilt_name_additional = array();
                    $field_rebuilt_name_additional['name'] = $child_field_name.'_additional';
                    $html .= '<br /><br />';
                    $html .= $this->settings_field_map_select( $field_rebuilt_name_additional, $form_id );
                }
                
				$html .= '</td>
					</tr>';
					
				$item++;
			}
			//for People Custom Fields
			$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
			$html .= '<tr'.$style.'><td colspan="4"><h4>People Custom Fields</h4></td></tr>';
			$item++;
			
			$people_custom_fields = get_option( $this->_wpgf2pdcrm_people_custom_field_option_name, '' );
			if( $people_custom_fields && is_array($people_custom_fields) && count($people_custom_fields) > 0 ){
				foreach ( $people_custom_fields as $key => $child_field ) {
					
					$new_key = $this->wpgf2pdcrm_custom_fields_name_mapping_get( $key );
					
					$child_field_name = $this->get_mapped_field_name( $field, $new_key );
					$required = $key == 'title' ? $this->get_required_indicator( $child_field ) : '';
					$style = $item % 2 == 0 ? ' style="background-color:#f9f9f9"' : '';
					$class = ' class="deal-create-person-fields"';
                    
					$field_rebuilt = array();
					$field_rebuilt['name'] = $child_field_name;
					$field_rebuilt['old_name'] = $this->get_mapped_field_name( $field, $key );
					$field_rebuilt['tooltip'] = '<h6>'.$child_field['type'].'</h6>'.$child_field['description'];
					$html .= '
						<tr'.$style.$class.'>
							<td style="width:50%">
								<label for="' . $child_field_name . '">' . $child_field['label'] . ' ' . gform_tooltip( $field_rebuilt['tooltip'], '', true ).' '. $required . '<label>
							</td>
							<td style="width:50%">' .
							 $this->settings_field_map_select_4_custom_fields( $field_rebuilt, $form_id ) .
						   '</td>
						</tr>';
						
					$item++;
				}
			}
			
			$html .= '
					</tbody>
				</table>
				</div>
				</div>';
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		function settings_field_map_select_4_custom_fields( $field, $form_id ) {

			$field_type          = rgempty( 'field_type', $field ) ? null : $field['field_type'];
			$exclude_field_types = rgempty( 'exclude_field_types', $field ) ? null : $field['exclude_field_types'];
	
			$field['choices'] = $this->get_field_map_choices( $form_id, $field_type, $exclude_field_types );
	
			if ( empty( $field['choices'] ) || ( count( $field['choices'] ) == 1 && rgblank( $field['choices'][0]['value'] ) ) ) {
				
				if ( ( ! is_array( $field_type ) && ! rgblank( $field_type ) ) || ( is_array( $field_type ) && count( $field_type ) == 1 ) ) {
				
					$type = is_array( $field_type ) ? $field_type[0] : $field_type;
					$type = ucfirst( GF_Fields::get( $type )->get_form_editor_field_title() );
					
					return sprintf( __( 'Please add a %s field to your form.', 'gravityforms' ), $type );
					
				}
	
			}
			
			return $this->settings_select_4_custom_fields( $field, false );
	
		}
		
		function settings_select_4_custom_fields( $field, $echo = true ) {
	
			$field['type'] = 'select'; // making sure type is set to select
			$value         = $this->get_setting( $field['name'], rgar( $field, 'default_value' ) );
			$name          = '' . esc_attr( $field['name'] );
			
			//check if the old setting value exist
			if( trim($field['old_name']) ){
				$old_value = $this->get_setting( $field['old_name'], rgar( $field, 'default_value' ) );
				if( $old_value ){
					$value = $old_value;
				}
			}
			unset( $field['old_name'] );
			$attributes    = $this->get_field_attributes( $field );
            
            //here need to hidden default settings field with right name
            //otherwise the field cannot be saved
            $default_setting_select_html = $this->settings_select( $field, false );
            $pattern = '/<select[ ]+name="(.*?)"/s';
            $matches = array();
            $default_setting_field_name = $name;
            if( preg_match_all( $pattern, $default_setting_select_html, $matches ) ){
                $default_setting_field_name = $matches[1][0];
            }
			
			$html = sprintf(
                                '<select name="%1$s" %2$s>%3$s</select>',
                                $default_setting_field_name, implode( ' ', $attributes ), 
                                $this->get_select_options( $field['choices'], $value )
                           );
			
			$html .= rgar( $field, 'after_select' );
	
			if ( $this->field_failed_validation( $field ) ) {
				$html .= $this->get_error_icon( $field );
			}
	
			if ( $echo ) {
				echo $html;
			}
	
			return $html;
		}
		
		function wpgf2pdcrm_custom_fields_name_mapping_get( $custom_field_id ){
			$saved_array = get_option( $this->_wpgf2pdcrm_custom_fields_name_mapping_option, array() );
			if( !is_array( $saved_array ) ){
				return false;
			}
			
			if( isset($saved_array[$custom_field_id]) ){
				return $saved_array[$custom_field_id];
			}
			
			return false;
		}
		
		function wpgf2pdcrm_custom_fields_name_mapping_get_r( $name ){
			$saved_array = get_option( $this->_wpgf2pdcrm_custom_fields_name_mapping_option, array() );
			if( !is_array( $saved_array ) ){
				return false;
			}
			
			foreach( $saved_array as $custom_field_id => $new_name ){
				if( $new_name == $name ){
					return $custom_field_id;
				}
			}
			
			return false;
		}
		
		function wpgf2pdcrm_get_custom_text_by_key( $key ){
			$saved_custom_text_array = get_option( $this->_wpgf2pdcrm_deal_title_custom_text_array, array() );
			if( isset($saved_custom_text_array[$key]) ){
				return $saved_custom_text_array[$key];
			}
			
			return '';
		}
		
		function wpgf2pdcrm_comatiable_with_feed_settings_no_feed_behaviour( $feed_id ){
			global $wpdb;

			$sql = $wpdb->prepare( "SELECT `meta` FROM {$wpdb->prefix}gf_addon_feed WHERE id=%d", $feed_id );
	
			$row = $wpdb->get_results( $sql, ARRAY_A );
			if ( ! $row || !is_array($row) || count($row) < 1 ) {
				return false;
			}
			
			$feed_meta = json_decode( $row[0]['meta'], true );
			if( !isset($feed_meta['feed_behaviour']) || $feed_meta['feed_behaviour'] == "" ){
				$feed_meta['feed_behaviour'] = 'contact_n_deal';
				$data_to_update = array( 'meta' => json_encode( $feed_meta ) );
				
				$wpdb->update( $wpdb->prefix.'gf_addon_feed', $data_to_update, array( 'id' => $feed_id ) );
			}
			
			return;
		}
		
        public function note_avatar() {
            return $this->_wpgf2pdcrm_plugin_folder_url . "/images/gravity-forms-pipedrive-logo-84x84.png";
        }
        
    } //end of class
    
}
