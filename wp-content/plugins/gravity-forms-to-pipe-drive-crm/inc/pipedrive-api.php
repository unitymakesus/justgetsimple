<?php

if ( !class_exists('WPGravityFormsToPipedriveAPIClass') ) {
	
    class WPGravityFormsToPipedriveAPIClass {

		var $_wpgf2pdcrm_plugin_page_url = '';
		var $_wpgf2pdcrm_token_option_name = '';
		var $_wpgf2pdcrm_debug_enalbe_opiton = '';
		
		var $_wpgf2pdcrm_deal_custom_field_option_name = '';
		var $_wpgf2pdcrm_organisation_custom_field_option_name = '';
		var $_wpgf2pdcrm_people_custom_field_option_name = '';
        var $_wpgf2pdcrm_product_custom_field_option_name = '';
        
        var $_wpgf2pdcrm_deal_fields_options_cache_option_name = '';
        var $_wpgf2pdcrm_organisation_fields_options_cache_option_name = '';
        var $_wpgf2pdcrm_people_fields_options_cache_option_name = '';
        var $_wpgf2pdcrm_product_fields_options_cache_option_name = '';
        
		var $_wpgf2pdcrm_pipelines_n_stages_option_name = '';
		var $_wpgf2pdcrm_users_option_name = '';
		var $_wpgf2pdcrm_custom_fields_type_description = array();
		var $_wpgf2pdcrm_custom_fields_name_mapping_option = '';
		
		var $_wpgf2pdcrm_organisations_list_option_name = '';
		var $_wpgf2pdcrm_persons_list_option_name = '';
        var $_wpgf2pdcrm_products_list_option_name = '';
        
        var $_wpgf2pdcrm_enable_cache_organisations_option = '';
        var $_wpgf2pdcrm_enable_cache_people_option = '';
        var $_wpgf2pdcrm_enable_cache_product_list_option = '';
        
        var $_wpgf2pdcrm_enable_postal_address_for_people_option = '_wpgf2pdcrm_enable_postal_address_for_people_';
		
		var $_wpgf2pdcrm_activity_types_option_name = '';
        
        var $_wpgf2pdcrm_leads_labels_option_name = '';
		
		var $_wpgf2pdcrm_debug_array = array();

	
		function __construct( $args ) {
			$this->_wpgf2pdcrm_plugin_page_url = $args['plugin_page_url'];
			$this->_wpgf2pdcrm_token_option_name = $args['token_option_name'];
			$this->_wpgf2pdcrm_debug_enalbe_opiton = $args['debug_enable_option'];
			$this->_wpgf2pdcrm_deal_custom_field_option_name = $args['deal_custom_field_option'];
			$this->_wpgf2pdcrm_organisation_custom_field_option_name = $args['org_custom_field_option'];
			$this->_wpgf2pdcrm_people_custom_field_option_name = $args['people_custom_field_option'];
			$this->_wpgf2pdcrm_product_custom_field_option_name = $args['product_custom_field_option'];
            
            $this->_wpgf2pdcrm_deal_fields_options_cache_option_name = $args['deal_fields_options_cache_option'];
            $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name = $args['organisation_fields_options_cache_option'];
            $this->_wpgf2pdcrm_people_fields_options_cache_option_name = $args['people_fields_options_cache_option'];
            $this->_wpgf2pdcrm_product_fields_options_cache_option_name = $args['product_fields_options_cache_option'];
            
			$this->_wpgf2pdcrm_pipelines_n_stages_option_name = $args['pipeline_stages_option'];
			$this->_wpgf2pdcrm_users_option_name = $args['pipeline_users_option'];
			$this->_wpgf2pdcrm_fields_by_group = $args['fields_by_group'];
			$this->_wpgf2pdcrm_custom_fields_type_description = $args['custom_fields_type_description'];
			$this->_wpgf2pdcrm_custom_fields_name_mapping_option = $args['custom_fields_name_mapping_option'];
			
			$this->_wpgf2pdcrm_organisations_list_option_name = $args['org_list_cache_option'];
			$this->_wpgf2pdcrm_persons_list_option_name = $args['persons_list_cache_option'];
            $this->_wpgf2pdcrm_products_list_option_name = $args['products_list_cache_option'];
            
            $this->_wpgf2pdcrm_enable_cache_organisations_option = $args['enable_cache_organisations_option'];
            $this->_wpgf2pdcrm_enable_cache_people_option = $args['enable_cache_people_option'];
            $this->_wpgf2pdcrm_enable_cache_product_list_option = $args['enable_cache_product_list_option'];
			
			$this->_wpgf2pdcrm_activity_types_option_name = $args['activity_types_cache_option'];
            
            $this->_wpgf2pdcrm_leads_labels_option_name = $args['leads_labels_option'];
			
			if( is_admin() ){	
				add_action( 'wp_ajax_wpgf2pdcrm_test_connection', array($this, 'wpgf2pdcrm_test_connection_to_pipedrive_crm') );
				add_action( 'wp_ajax_wpgf2pdcrm_refresh_pipedrive_data_cache', array($this, 'wpgf2pdcrm_refresh_pipedrive_data_cache_fun') );
			}
        }
        
        function wpgf2pdcrm_valid_access_token( $token ){
            $url = 'https://api.pipedrive.com/v1/pipelines?api_token='.$token;
			$arg = array( 'method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$pipelines_data = json_decode( $resp_body );
			if( !isset($pipelines_data->success) || 
				!$pipelines_data->success ||
				!isset($pipelines_data->data) || 
				!is_array($pipelines_data->data) || 
				count($pipelines_data->data) < 1 ){
					
				return false;
			}
            
            return true;
        }
		
		function wpgf2pdcrm_test_connection_to_pipedrive_crm(){
			global $current_user;
			if( $current_user->ID < 1 ){
				wp_die( 'ERROR: Invalid Operation' );
			}

			$token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
			if( $token_saved == "" ){
				wp_die( 'ERROR: Please save a Token first' );
			}
			
			$data_to_post = array( 'title' => 'Gravity Form to Pipedrive CRM test deal', 'value' => 100, 'currency' => 'AUD' );
			
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/deals?api_token='.$token_saved, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				wp_die( 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again.' );
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						wp_die( 'SUCCESS' );
					}else{
						wp_die( 'ERROR: Test connection failed. '.$deal_return->error );
					}
				}
				wp_die( 'ERROR: Test connection failed. Retrive resonse failed.' );
			}
			wp_die('SUCCESS');
		}
		
		function wpgf2pdcrm_refresh_pipedrive_data_cache_fun(){
			global $current_user;
			if( $current_user->ID < 1 ){
				wp_die( 'ERROR: Invalid Operation' );
			}

            $token_saved = get_option( $this->_wpgf2pdcrm_token_option_name );
			if( $token_saved == "" ){
				wp_die( 'ERROR: Please save a Token first' );
			}

            //update deal custom fields cache
			$this->wpgf2pdcrm_update_deal_custom_fields_cache( $token_saved );
			//update organisation custom fields cache
			$this->wpgf2pdcrm_update_organisation_custom_fields_cache( $token_saved );
			//update people custom fields cache
			$this->wpgf2pdcrm_update_people_custom_fields_cache( $token_saved );
            //update product custom fields cache
			$this->wpgf2pdcrm_update_product_custom_fields_cache( $token_saved );
			
			//update organisations list cache
            if( get_option( $this->_wpgf2pdcrm_enable_cache_organisations_option, false ) == true ){
                $this->wpgf2pdcrm_update_organisations_list_cache( $token_saved );
            }
			//update persons list cache
            if( get_option( $this->_wpgf2pdcrm_enable_cache_people_option, false ) == true ){
                $this->wpgf2pdcrm_update_persons_list_cache( $token_saved );
            }
            //update products list cache
            if( get_option( $this->_wpgf2pdcrm_enable_cache_product_list_option, false ) == true ){
                $this->wpgf2pdcrm_update_products_list_cache( $token_saved );
            }
            
            //read pipeline & stage
			$this->wpgf2pdcrm_read_pipelines_n_stages_data( $token_saved );
            
			//read users
			$this->wpgf2pdcrm_read_users_data( $token_saved );
            
			//read activity types
			$this->wpgf2pdcrm_read_activity_types( $token_saved );
            
            //read lead leabels
            $this->wpgf2pdcrm_read_leads_labels_cache( $token_saved );

            wp_die( 'SUCCESS' );
		}

		function wpgf2pdcrm_update_deal_custom_fields_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/dealFields?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$deal_fields = json_decode( $resp_body );
			if( !isset($deal_fields->success) || 
				!$deal_fields->success ||
				!isset($deal_fields->data) || 
				!is_array($deal_fields->data) || 
				count($deal_fields->data) < 1 ){
					
				return false;
			}

			$custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
            
            $deal_custom_fields_process_return = $this->wpgf2pdcrm_organise_deal_custom_fields_cache( $deal_fields->data );
            $custom_fields_with_type_description = $deal_custom_fields_process_return['fields_with_type_desc'];
            $custom_fields_descrption_by_key = $deal_custom_fields_process_return['descriptions'];
            $custom_fields_type_by_key = $deal_custom_fields_process_return['types'];
            $fields_options_array = $deal_custom_fields_process_return['fields_options'];
            
			$more_custom_fields = false;
			$next_start = 0;
			if( isset($deal_fields->additional_data) && isset($deal_fields->additional_data->pagination) && 
				isset($deal_fields->additional_data->pagination->more_items_in_collection) ){
				
				$more_custom_fields = $deal_fields->additional_data->pagination->more_items_in_collection;
				if( isset($deal_fields->additional_data->pagination->next_start) ){
					$next_start = $deal_fields->additional_data->pagination->next_start;
				}
			}
            while( $more_custom_fields && $next_start ){
				$url = 'https://api.pipedrive.com/v1/dealFields?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
                $arg = array('method' => 'GET' );
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					break;
				}
                $resp_body = wp_remote_retrieve_body( $response );
				$deal_fields = json_decode( $resp_body );
                if( !isset($deal_fields->success) || 
                    !$deal_fields->success ||
                    !isset($deal_fields->data) || 
                    !is_array($deal_fields->data) || 
                    count($deal_fields->data) < 1 ){

                    break;
                }
                
                $deal_custom_fields_process_return = $this->wpgf2pdcrm_organise_deal_custom_fields_cache( $deal_fields->data );

                $custom_fields_with_type_description = array_merge( $custom_fields_with_type_description, $deal_custom_fields_process_return['fields_with_type_desc'] );
                $custom_fields_descrption_by_key = array_merge( $custom_fields_descrption_by_key, $deal_custom_fields_process_return['descriptions'] );
                $custom_fields_type_by_key = array_merge( $custom_fields_type_by_key, $deal_custom_fields_process_return['types'] );
                $fields_options_array = array_merge( $fields_options_array, $deal_custom_fields_process_return['fields_options'] );
                
				if( isset($deal_fields->additional_data) && isset($deal_fields->additional_data->pagination) && 
					isset($deal_fields->additional_data->pagination->more_items_in_collection) ){
					
					$more_custom_fields = $deal_fields->additional_data->pagination->more_items_in_collection;
					$next_start = $deal_fields->additional_data->pagination->next_start;
				}
			}
			
			update_option( $this->_wpgf2pdcrm_deal_custom_field_option_name, $custom_fields_with_type_description );
            update_option( $this->_wpgf2pdcrm_deal_fields_options_cache_option_name, $fields_options_array );
			
			return array('description' => $custom_fields_descrption_by_key, 'type' => $custom_fields_type_by_key);
		}
        
        function wpgf2pdcrm_organise_deal_custom_fields_cache( $deal_fields_data ){
            
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
            
            foreach( $deal_fields_data as $deal_fields_obj ){
				//for expected_close_date
				if( $deal_fields_obj->key == 'expected_close_date' ){
					$description = 'Optional expect close date of the deal in UTC. Requires admin user API token. Format: YYYY-MM-DD';
					$custom_fields_with_type_description[$deal_fields_obj->key] = array( 'label' => $deal_fields_obj->name, 
																					 	 'type' => $deal_fields_obj->field_type, 
																					     'description' => $description );
					$custom_fields_descrption_by_key[$deal_fields_obj->key] = $description;
					$custom_fields_type_by_key[$deal_fields_obj->key] = $deal_fields_obj->field_type;
					
					$this->wpgf2pdcrm_custom_fields_name_mapping_update( $deal_fields_obj->key );
					continue;
				}
                
				if( $deal_fields_obj->edit_flag == false ){
                    if( $deal_fields_obj->field_type == 'enum' || $deal_fields_obj->field_type == 'set' ){
                        $options = false;
                        if( isset( $deal_fields_obj->options ) && $deal_fields_obj->options ){
                            $options = $deal_fields_obj->options;
                        }
                        $fields_options_array[$deal_fields_obj->key] = array( 
                                                                                'label' => $deal_fields_obj->name, 
                                                                                'options' => $options,
                                                                            );
                    }
					continue;
				}
				//populate custom fiels with type description
				$description = '';
				if( isset($this->_wpgf2pdcrm_custom_fields_type_description[$deal_fields_obj->field_type]) ){
					$description = $this->_wpgf2pdcrm_custom_fields_type_description[$deal_fields_obj->field_type];
				}
				$custom_fields_with_type_description[$deal_fields_obj->key] = array( 'label' => $deal_fields_obj->name, 
																					 'type' => $deal_fields_obj->field_type, 
																					 'description' => $description );
				if( $deal_fields_obj->field_type == 'set' || $deal_fields_obj->field_type == 'enum' ){
					$custom_fields_with_type_description[$deal_fields_obj->key]['options'] = $deal_fields_obj->options;
				}
				$custom_fields_descrption_by_key[$deal_fields_obj->key] = $description;
				$custom_fields_type_by_key[$deal_fields_obj->key] = $deal_fields_obj->field_type;
				
				$this->wpgf2pdcrm_custom_fields_name_mapping_update( $deal_fields_obj->key );
			}
            
            return array( 
                          'fields_with_type_desc' => $custom_fields_with_type_description, 
                          'descriptions' => $custom_fields_descrption_by_key, 
                          'types' => $custom_fields_type_by_key,
                          'fields_options' => $fields_options_array,
                        );
        }
		
		function wpgf2pdcrm_update_organisation_custom_fields_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/organizationFields?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$organisation_fields = json_decode( $resp_body );
			if( !isset($organisation_fields->success) || 
				!$organisation_fields->success ||
				!isset($organisation_fields->data) || 
				!is_array($organisation_fields->data) || 
				count($organisation_fields->data) < 1 ){
					
				return false;
			}
			
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
            
            $organisation_custom_fields_process_return = $this->wpgf2pdcrm_organise_organisation_custom_fields_cache( $organisation_fields->data );
            $custom_fields_with_type_description = $organisation_custom_fields_process_return['fields_with_type_desc'];
            $custom_fields_descrption_by_key = $organisation_custom_fields_process_return['descriptions'];
            $custom_fields_type_by_key = $organisation_custom_fields_process_return['types'];
            $fields_options_array = $organisation_custom_fields_process_return['fields_options'];
            
			$more_custom_fields = false;
			$next_start = 0;
			if( isset($organisation_fields->additional_data) && isset($organisation_fields->additional_data->pagination) && 
				isset($organisation_fields->additional_data->pagination->more_items_in_collection) ){
				
				$more_custom_fields = $organisation_fields->additional_data->pagination->more_items_in_collection;
				if( isset($organisation_fields->additional_data->pagination->next_start) ){
					$next_start = $organisation_fields->additional_data->pagination->next_start;
				}
			}
            while( $more_custom_fields && $next_start ){
				$url = 'https://api.pipedrive.com/v1/organizationFields?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
                $arg = array('method' => 'GET' );
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					break;
				}
                $resp_body = wp_remote_retrieve_body( $response );
				$organisation_fields = json_decode( $resp_body );
                if( !isset($organisation_fields->success) || 
                    !$organisation_fields->success ||
                    !isset($organisation_fields->data) || 
                    !is_array($organisation_fields->data) || 
                    count($organisation_fields->data) < 1 ){

                    break;
                }
                
                $organisation_custom_fields_process_return = $this->wpgf2pdcrm_organise_organisation_custom_fields_cache( $organisation_fields->data );

                $custom_fields_with_type_description = array_merge( $custom_fields_with_type_description, $organisation_custom_fields_process_return['fields_with_type_desc'] );
                $custom_fields_descrption_by_key = array_merge( $custom_fields_descrption_by_key, $organisation_custom_fields_process_return['descriptions'] );
                $custom_fields_type_by_key = array_merge( $custom_fields_type_by_key, $organisation_custom_fields_process_return['types'] );
                $fields_options_array = array_merge( $fields_options_array, $organisation_custom_fields_process_return['fields_options'] );
                
				if( isset($organisation_fields->additional_data) && 
                    isset($organisation_fields->additional_data->pagination) && 
					isset($organisation_fields->additional_data->pagination->more_items_in_collection) ){
					
					$more_custom_fields = $organisation_fields->additional_data->pagination->more_items_in_collection;
					$next_start = $organisation_fields->additional_data->pagination->next_start;
				}
			}

			update_option( $this->_wpgf2pdcrm_organisation_custom_field_option_name, $custom_fields_with_type_description );
            update_option( $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name, $fields_options_array );
			
			return array('description' => $custom_fields_descrption_by_key, 'type' => $custom_fields_type_by_key);
		}
        
        function wpgf2pdcrm_organise_organisation_custom_fields_cache( $organisaiton_fields_data ){
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
			foreach( $organisaiton_fields_data as $organisation_fields_obj ){
                
				if( $organisation_fields_obj->edit_flag == false ){
                    if( $organisation_fields_obj->field_type == 'enum' || $organisation_fields_obj->field_type == 'set' ){
                        $fields_options_array[$organisation_fields_obj->key] = array( 
                                                                                    'label' => $organisation_fields_obj->name, 
                                                                                    'options' => $organisation_fields_obj->options,
                                                                                );
                    }
					continue;
				}
				//populate custom fiels with type description
				$description = '';
				if( isset($this->_wpgf2pdcrm_custom_fields_type_description[$organisation_fields_obj->field_type]) ){
					$description = $this->_wpgf2pdcrm_custom_fields_type_description[$organisation_fields_obj->field_type];
				}
				$custom_fields_with_type_description['_cf_org_'.$organisation_fields_obj->key] = array( 'label' => $organisation_fields_obj->name, 
																										'type' => $organisation_fields_obj->field_type, 
																										'description' => $description );
				if( $organisation_fields_obj->field_type == 'set' || $organisation_fields_obj->field_type == 'enum' ){
					$custom_fields_with_type_description['_cf_org_'.$organisation_fields_obj->key]['options'] = $organisation_fields_obj->options;
				}
				$custom_fields_descrption_by_key['_cf_org_'.$organisation_fields_obj->key] = $description;
				$custom_fields_type_by_key['_cf_org_'.$organisation_fields_obj->key] = $organisation_fields_obj->field_type;
				
				$this->wpgf2pdcrm_custom_fields_name_mapping_update( '_cf_org_'.$organisation_fields_obj->key );
			}
            
            return array( 
                          'fields_with_type_desc' => $custom_fields_with_type_description, 
                          'descriptions' => $custom_fields_descrption_by_key, 
                          'types' => $custom_fields_type_by_key,
                          'fields_options' => $fields_options_array,
                        );
        }
		
		function wpgf2pdcrm_update_people_custom_fields_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/personFields?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$people_fields = json_decode( $resp_body );
			if( !isset($people_fields->success) || 
				!$people_fields->success ||
				!isset($people_fields->data) || 
				!is_array($people_fields->data) || 
				count($people_fields->data) < 1 ){
					
				return false;
			}
            
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
            $postal_address_exist = false;
            
			$people_custom_fields_process_return = $this->wpgf2pdcrm_organise_people_custom_fields_cache( $people_fields->data );
            $custom_fields_with_type_description = $people_custom_fields_process_return['fields_with_type_desc'];
            $custom_fields_descrption_by_key = $people_custom_fields_process_return['descriptions'];
            $custom_fields_type_by_key = $people_custom_fields_process_return['types'];
            $fields_options_array = $people_custom_fields_process_return['fields_options'];
            $postal_address_exist = $people_custom_fields_process_return['postal_address_exist'];
            if( $people_custom_fields_process_return['postal_address_exist'] ){
                $postal_address_exist = $people_custom_fields_process_return['postal_address_exist'];
            }
            
			$more_custom_fields = false;
			$next_start = 0;
			if( isset($people_fields->additional_data) && isset($people_fields->additional_data->pagination) && 
				isset($people_fields->additional_data->pagination->more_items_in_collection) ){
				
				$more_custom_fields = $people_fields->additional_data->pagination->more_items_in_collection;
				if( isset($people_fields->additional_data->pagination->next_start) ){
					$next_start = $people_fields->additional_data->pagination->next_start;
				}
			}
            while( $more_custom_fields && $next_start ){
				$url = 'https://api.pipedrive.com/v1/personFields?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
                $arg = array('method' => 'GET' );
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					break;
				}
                $resp_body = wp_remote_retrieve_body( $response );
				$people_fields = json_decode( $resp_body );
                if( !isset($people_fields->success) || 
                    !$people_fields->success ||
                    !isset($people_fields->data) || 
                    !is_array($people_fields->data) || 
                    count($people_fields->data) < 1 ){

                    break;
                }
                
                $people_custom_fields_process_return = $this->wpgf2pdcrm_organise_people_custom_fields_cache( $people_fields->data );

                $custom_fields_with_type_description = array_merge( $custom_fields_with_type_description, $people_custom_fields_process_return['fields_with_type_desc'] );
                $custom_fields_descrption_by_key = array_merge( $custom_fields_descrption_by_key, $people_custom_fields_process_return['descriptions'] );
                $custom_fields_type_by_key = array_merge( $custom_fields_type_by_key, $people_custom_fields_process_return['types'] );
                $fields_options_array = array_merge( $fields_options_array, $people_custom_fields_process_return['fields_options'] );
                if( $people_custom_fields_process_return['postal_address_exist'] ){
                    $postal_address_exist = $people_custom_fields_process_return['postal_address_exist'];
                }
                
				if( isset($people_fields->additional_data) && 
                    isset($people_fields->additional_data->pagination) && 
					isset($people_fields->additional_data->pagination->more_items_in_collection) ){
					
					$more_custom_fields = $people_fields->additional_data->pagination->more_items_in_collection;
					$next_start = $people_fields->additional_data->pagination->next_start;
				}
			}
			
			update_option( $this->_wpgf2pdcrm_people_custom_field_option_name, $custom_fields_with_type_description );
			update_option( $this->_wpgf2pdcrm_enable_postal_address_for_people_option, $postal_address_exist );
            update_option( $this->_wpgf2pdcrm_people_fields_options_cache_option_name, $fields_options_array );

            return array('description' => $custom_fields_descrption_by_key, 'type' => $custom_fields_type_by_key);
		}
        
        function wpgf2pdcrm_organise_people_custom_fields_cache( $people_fields_data ){
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
            $postal_address_exist = false;
			foreach( $people_fields_data as $people_fields_obj ){
                if( $people_fields_obj->key == 'postal_address' ){
                    $postal_address_exist = true;
                    continue;
                }
                
				if( $people_fields_obj->edit_flag == false ){
                    if( $people_fields_obj->field_type == 'enum' || $people_fields_obj->field_type == 'set' ){
                        $fields_options_array[$people_fields_obj->key] = array( 
                                                                                'label' => $people_fields_obj->name, 
                                                                                'options' => $people_fields_obj->options,
                                                                            );
                    }
					continue;
				}
				//populate custom fiels with type description
				$description = '';
				if( isset($this->_wpgf2pdcrm_custom_fields_type_description[$people_fields_obj->field_type]) ){
					$description = $this->_wpgf2pdcrm_custom_fields_type_description[$people_fields_obj->field_type];
				}
				$custom_fields_with_type_description['_cf_people_'.$people_fields_obj->key] = array( 'label' => $people_fields_obj->name, 
																									 'type' => $people_fields_obj->field_type, 
																									 'description' => $description );
                if( $people_fields_obj->field_type == 'set' || $people_fields_obj->field_type == 'enum' ){
					$custom_fields_with_type_description['_cf_people_'.$people_fields_obj->key]['options'] = $people_fields_obj->options;
				}
				$custom_fields_descrption_by_key['_cf_people_'.$people_fields_obj->key] = $description;
				$custom_fields_type_by_key['_cf_people_'.$people_fields_obj->key] = $people_fields_obj->field_type;
				
				$this->wpgf2pdcrm_custom_fields_name_mapping_update( '_cf_people_'.$people_fields_obj->key );
			}
            
            return array( 
                          'fields_with_type_desc' => $custom_fields_with_type_description, 
                          'descriptions' => $custom_fields_descrption_by_key, 
                          'types' => $custom_fields_type_by_key,
                          'fields_options' => $fields_options_array,
                          'postal_address_exist' => $postal_address_exist,
                        );
        }
        
        function wpgf2pdcrm_update_product_custom_fields_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/productFields?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$product_fields = json_decode( $resp_body );
			if( !isset($product_fields->success) || 
				!$product_fields->success ||
				!isset($product_fields->data) || 
				!is_array($product_fields->data) || 
				count($product_fields->data) < 1 ){
					
				return false;
			}

			$custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();

            $more_custom_fields = false;
			$next_start = 0;
			if( isset($product_fields->additional_data) && isset($product_fields->additional_data->pagination) && 
				isset($product_fields->additional_data->pagination->more_items_in_collection) ){
				
				$more_custom_fields = $product_fields->additional_data->pagination->more_items_in_collection;
				if( isset($product_fields->additional_data->pagination->next_start) ){
					$next_start = $product_fields->additional_data->pagination->next_start;
				}
			}
            while( $more_custom_fields && $next_start ){
				$url = 'https://api.pipedrive.com/v1/productFields?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
                $arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					break;
				}
                $resp_body = wp_remote_retrieve_body( $response );
				$product_fields = json_decode( $resp_body );
                if( !isset($product_fields->success) || 
                    !$product_fields->success ||
                    !isset($product_fields->data) || 
                    !is_array($product_fields->data) || 
                    count($product_fields->data) < 1 ){

                    break;
                }
                
                $product_custom_fields_process_return = $this->wpgf2pdcrm_organise_people_custom_fields_cache( $product_fields->data );

                $custom_fields_with_type_description = array_merge( $custom_fields_with_type_description, $product_custom_fields_process_return['fields_with_type_desc'] );
                $custom_fields_descrption_by_key = array_merge( $custom_fields_descrption_by_key, $product_custom_fields_process_return['descriptions'] );
                $custom_fields_type_by_key = array_merge( $custom_fields_type_by_key, $product_custom_fields_process_return['types'] );
                $fields_options_array = array_merge( $fields_options_array, $product_custom_fields_process_return['fields_options'] );
                
				if( isset($product_fields->additional_data) && 
                    isset($product_fields->additional_data->pagination) && 
					isset($product_fields->additional_data->pagination->more_items_in_collection) ){
					
					$more_custom_fields = $product_fields->additional_data->pagination->more_items_in_collection;
					$next_start = $product_fields->additional_data->pagination->next_start;
				}
			}

            update_option( $this->_wpgf2pdcrm_product_custom_field_option_name, $custom_fields_with_type_description );
            update_option( $this->_wpgf2pdcrm_product_fields_options_cache_option_name, $fields_options_array );

            return array('description' => $custom_fields_descrption_by_key, 'type' => $custom_fields_type_by_key);
		}
        
        function wpgf2pdcrm_organise_product_custom_fields_cache( $product_fields_data ){
            $custom_fields_with_type_description = array();
			$custom_fields_descrption_by_key = array();
			$custom_fields_type_by_key = array();
            $fields_options_array = array();
			foreach( $product_fields_data as $product_fields_obj ){
                
				if( $product_fields_obj->edit_flag == false ){
                    if( $product_fields_obj->field_type == 'enum' || $product_fields_obj->field_type == 'set' ){
                        $fields_options_array[$product_fields_obj->key] = array( 
                                                                                'label' => $product_fields_obj->name, 
                                                                                'options' => $product_fields_obj->options,
                                                                            );
                    }
					continue;
				}
				//populate custom fiels with type description
				$description = '';
				if( isset($this->_wpgf2pdcrm_custom_fields_type_description[$product_fields_obj->field_type]) ){
					$description = $this->_wpgf2pdcrm_custom_fields_type_description[$product_fields_obj->field_type];
				}
				$custom_fields_with_type_description['_cf_product_'.$product_fields_obj->key] = array( 
                                                                                'label' => $product_fields_obj->name, 
																				'type' => $product_fields_obj->field_type, 
																                'description' => $description );
                if( $product_fields_obj->field_type == 'set' || $product_fields_obj->field_type == 'enum' ){
					$custom_fields_with_type_description['_cf_product_'.$product_fields_obj->key]['options'] = $product_fields_obj->options;
				}
				$custom_fields_descrption_by_key['_cf_product_'.$product_fields_obj->key] = $description;
				$custom_fields_type_by_key['_cf_product_'.$product_fields_obj->key] = $product_fields_obj->field_type;
				
				$this->wpgf2pdcrm_custom_fields_name_mapping_update( '_cf_product_'.$product_fields_obj->key );
			}
            
            return array( 
                          'fields_with_type_desc' => $custom_fields_with_type_description, 
                          'descriptions' => $custom_fields_descrption_by_key, 
                          'types' => $custom_fields_type_by_key,
                          'fields_options' => $fields_options_array,
                        );
        }
		
		function wpgf2pdcrm_debug_init(){
			if( get_option( $this->_wpgf2pdcrm_debug_enalbe_opiton, false) == true ){
				$this->_wpgf2pdcrm_debug_array = array();
			}
		}
		
		function wpgf2pdcrm_debug_push( $key, $error){
			if( get_option( $this->_wpgf2pdcrm_debug_enalbe_opiton, false) == true ){
				$this->_wpgf2pdcrm_debug_array[$key] = $error;
			}
		}
		
		function wpgf2pdcrm_debug_show(){
			if( get_option( $this->_wpgf2pdcrm_debug_enalbe_opiton, false) == true ){
				echo "\nGravity Form to Pipe Drive CRM debug info: \n<br />";
				foreach( $this->_wpgf2pdcrm_debug_array as $key => $value ){
					if( is_array($value) ){
						echo "\n\nKey:&nbsp;&nbsp;&nbsp;&nbsp;".$key.'----------------Value:&nbsp;&nbsp;&nbsp;&nbsp;'.serialize($value)." \n<br />";
					}else if( is_object($value) ){
						 $array_value = (array)$value;
						 echo "\n\nKey:&nbsp;&nbsp;&nbsp;&nbsp;".$key.'----------------Value:&nbsp;&nbsp;&nbsp;&nbsp;'.serialize($array_value)." \n<br />";
					}else{
						echo "\n\nKey:&nbsp;&nbsp;&nbsp;&nbsp;".$key.'----------------Value:&nbsp;&nbsp;&nbsp;&nbsp;'.$value." \n<br />";
					}
				}
				echo "\n\n\n<br />";
				exit;
			}
		}
		
		function wpgf2pdcrm_pipedrive_crm_create_organisation( $token, $name, $owner_id, $address, $custom_fields_array, &$error_msg ){
		
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				return false;
			}
			
			$data_to_post = $custom_fields_array;
			$data_to_post['name'] = $name;
			if( $address ){
				$data_to_post['address'] = $address;
			}
			if( $owner_id ){
				$data_to_post['owner_id'] = $owner_id;
			}
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/organizations?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				$error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
				return;
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						$error_msg = '';
						
						return $deal_return->data->id;
					}else{
						$error_msg = $deal_return->error;
						
						return false;
					}
				}
			}
			
			return false;
		}
		
		function wpgf2pdcrm_pipedrive_crm_create_people( $token, $name, $postal_address, $owner_id, $org_id, $email, $phone, $custom_fields_array, &$error_msg ){
			
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				return false;
			}
            
            $support_postal_address = $this->wpgf2pdcrm_pipedrive_crm_is_postal_address_enable_for_people();
			
			$data_to_post = array( 'name' => $name, 'email' => $email, 'phone' => $phone );
            if( $support_postal_address && $postal_address ){
				$data_to_post['postal_address'] = $postal_address;
			}
			if( $org_id ){
				$data_to_post['org_id'] = $org_id;
			}
			if( $owner_id ){
				$data_to_post['owner_id'] = $owner_id;
			}
			$data_to_post = array_merge($data_to_post, $custom_fields_array);
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/persons?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				$error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						$error_msg = '';
						return $deal_return->data->id;
					}else{
						$error_msg = $deal_return->error;
						
						return false;
					}
				}
			}
			
			return false;
		}
		
		function wpgf2pdcrm_pipedrive_crm_create_note( $token, $content, $deal_id, &$error_msg ){
			
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				return false;
			}
			
			$data_to_post = array( 'content' => $content, 'deal_id' => $deal_id );
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/notes?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				$error_msg = 'WordPress API "wp_remote_post" encountered an ERROR, please try again.';
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						$error_msg = '';
						
						return $deal_return->data->id;
					}else{
						$error_msg = $deal_return->error;
						
						return false;
					}
				}
			}
			
			return false;
		}
		
		function wpgf2pdcrm_pipedrive_crm_upload_file_to_deal( $token, $deal_id, $file_path_to_upload, $file_name_to_upload, &$error_msg ){
			
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				
				return -1;
			}
			
			$url = 'https://api.pipedrive.com/v1/files?api_token='.$token;
			$postdata = array( 
				'deal_id' => $deal_id
			); 
			
			$data = ""; 
			$boundary = "---------------------".substr(md5(rand(0,32000)), 0, 10); 
		
			//Collect Postdata 
			foreach($postdata as $key => $val) 
			{ 
				$data .= "--$boundary\n"; 
				$data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n"; 
			} 
		
			$data .= "--$boundary\n"; 
		
			//Collect Filedata 
			$fileContents = file_get_contents( $file_path_to_upload ); 
			$data .= "Content-Disposition: form-data; name=\"file\"; filename=\"$file_name_to_upload\"\n"; 
			$data .= "Content-Type: image/jpeg\n"; 
			$data .= "Content-Transfer-Encoding: binary\n\n"; 
			$data .= $fileContents."\n"; 
			$data .= "--$boundary--\n"; 
			
			$params = array('http' => array( 
										   'method' => 'POST', 
										   'header' => 'Content-Type: multipart/form-data; boundary='.$boundary, 
										   'content' => $data 
										)
						   ); 
			$context = stream_context_create($params);
			$fp = fopen($url, 'rb', false, $context);
			if( !$fp ) { 
				$error_msg = "Problem with $url, $php_errormsg";
				return -1;
			} 
			$response = stream_get_contents($fp);
			
			$deal_return = json_decode( $response );
			if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
				$error_msg = 'Upload file to deal succeed';
				return $deal_return->data->id;
			}else{
				$error_msg = $deal_return->error;
				
				return -1;
			}
			
			return -1;
		}
		
		function wpgf2pdcrm_pipedrive_crm_upload_file_to_deal_HTTP_API( $token, $deal_id, $file_path_to_upload, $file_name_to_upload, &$error_msg ){
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				
				return -1;
			}
			
			$post_fields = array(
				'deal_id' => $deal_id
			);
			$boundary = wp_generate_password(24); // Just a random string
			$headers = array(
				'content-type' => 'multipart/form-data; boundary=' . $boundary
			);
			$payload = '';
			// First, add the standard POST fields:
			foreach( $post_fields as $field_name => $value ) {
				$payload .= '--' . $boundary;
				$payload .= "\n";
				$payload .= 'Content-Disposition: form-data; name="' . $field_name . '"' . "\n\n";
				$payload .= $value;
				$payload .= "\n";
			}
			// Upload the file
			
			$payload .= '--' . $boundary;
			$payload .= "\n";
			$payload .= 'Content-Disposition: form-data; name="file"; filename="' . $file_name_to_upload. '"' . "\n";
			$payload .= "Content-Type: image/jpeg\n"; 
			$payload .= "Content-Transfer-Encoding: binary\n"; 
			$payload .= "\n";
			$payload .= file_get_contents( $file_path_to_upload );
			$payload .= "\n";
			$payload .= '--' . $boundary . '--';
			
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/files?api_token='.$token, 
										array('headers' => $headers, 'body' => $payload, 'timeout' => 300 ) 
									  );
			if( is_wp_error( $response ) ) {
				$error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
				return -1;
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				$deal_return = json_decode( $resp_body );
				if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
					$error_msg = 'Upload file to deal succeed';
					
					return $deal_return->data->id;
				}else{
					$error_msg = $deal_return->error;
					
					return -1;
				}
			}

			return -1;
		}
		
		function wpgf2pdcrm_read_pipelines_n_stages_data( $token ){			
			//read all pipeline first
			if( $token == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/pipelines?api_token='.$token;
			$arg = array( 'method' => 'GET' );
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$pipelines_data = json_decode( $resp_body );
			if( !isset($pipelines_data->success) || 
				!$pipelines_data->success ||
				!isset($pipelines_data->data) || 
				!is_array($pipelines_data->data) || 
				count($pipelines_data->data) < 1 ){
					
				return false;
			}
			$pipelines_data_to_save = array();
			foreach( $pipelines_data->data as $pipeline_obj ){
				if( !$pipeline_obj->active ){
					continue;
				}
				$pipelines_data_to_save[$pipeline_obj->id] = array( 'name' => $pipeline_obj->name, 'stages' => array() );
			}
			//no active pipeline
			if( count($pipelines_data_to_save) < 1 ){
				update_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, '' );
				return false;
			}

			//read stages
			foreach( $pipelines_data_to_save as $pipleline_id => $pipeline_name ){
				$url = 'https://api.pipedrive.com/v1/stages?pipeline_id='.$pipleline_id.'&api_token='.$token;
				$arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					return false;
				}
				$resp_body = wp_remote_retrieve_body( $response );
				$stages_data = json_decode( $resp_body );
				if( !isset($stages_data->success) || 
					!$stages_data->success ||
					!isset($stages_data->data) || 
					!is_array($stages_data->data) || 
					count($stages_data->data) < 1 ){
					continue;
				}
				foreach( $stages_data->data as $stage_obj ){
					if( !$stage_obj->active_flag ){
						continue;
					}
					$pipelines_data_to_save[$pipleline_id]['stages'][$stage_obj->id] = $stage_obj->name;
				}
			}
			
			update_option( $this->_wpgf2pdcrm_pipelines_n_stages_option_name, $pipelines_data_to_save );

			return $pipelines_data_to_save;
		}
		
		function wpgf2pdcrm_pipedrive_crm_create_deal( $token, $data_to_post, $notes_content, $files_url_array, &$error_msg ){
			if( $token == "" ){
				return false;
			}

			$response = wp_remote_post( 'https://api.pipedrive.com/v1/deals?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) )
									  );
			if( is_wp_error($response) ) {
                $error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post',  $error_msg );
				
				return false;
			}
			$resp_body = wp_remote_retrieve_body($response);
			if( !$resp_body ){
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post_response', 'ERROR: retrive response body failed.' );
				
				return false;
			}
			$deal_return = json_decode( $resp_body );
			if( !is_object($deal_return) ||  !isset($deal_return->success) || !$deal_return->success || !isset($deal_return->data) || !is_object($deal_return->data)){
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post_response', 'ERROR: post deal failed, return error message: '.$deal_return->error );
				$error_msg = $deal_return->error;
				
				return false;
			}
			//SUCCESS
			$this->wpgf2pdcrm_debug_push( 'create deal success', 'return deal id: '.$deal_return->data->id );
			
			//now come to create note
			if( $notes_content && is_array($notes_content) && count($notes_content) > 0 ){
				$this->wpgf2pdcrm_debug_push( 'note', 'Need create note to the deal' );
				$error_message = '';
                
                if( isset($notes_content['gf2zoho_combin_all_in_one_note']) && 
                    $notes_content['gf2zoho_combin_all_in_one_note'] ){
                    
                    unset( $notes_content['gf2zoho_combin_all_in_one_note'] );
                    
                    $notes_content_string_array = array();
                    foreach( $notes_content as $field_id => $lable_n_value ){
                        $notes_content_string_array[] = $lable_n_value['label']."<br />".$lable_n_value['content'];
                    }

                    $notes_content_string = implode( '<br /><br />', $notes_content_string_array );
                    $new_note_id = $this->wpgf2pdcrm_pipedrive_crm_create_note($token, $notes_content_string, $deal_return->data->id, $error_message);
					if( !$new_note_id ){
						$this->wpgf2pdcrm_debug_push( 'add_note_failed_4_'.$field_id, 'Create note failed: '.$error_message );
						$this->wpgf2pdcrm_debug_push( 'add_note_failed_content_4_'.$field_id, 'Notes: '.$notes_content );
						$this->wpgf2pdcrm_debug_push( 'add_note_failed_deal_id__4_'.$field_id, 'Deal_id: '.$deal_return->data->id );
					}else{
						$this->wpgf2pdcrm_debug_push( 'add_note_success', 'Create note success note ID: '.$new_note_id );
					}
                }else{
                    foreach( $notes_content as $field_id => $lable_n_value ){
                        $notes_content_string = $lable_n_value['label']."<br />".$lable_n_value['content'];
                        $new_note_id = $this->wpgf2pdcrm_pipedrive_crm_create_note($token, $notes_content_string, $deal_return->data->id, $error_message);
                        if( !$new_note_id ){
                            $this->wpgf2pdcrm_debug_push( 'add_note_failed_4_'.$field_id, 'Create note failed: '.$error_message );
                            $this->wpgf2pdcrm_debug_push( 'add_note_failed_content_4_'.$field_id, 'Notes: '.$notes_content );
                            $this->wpgf2pdcrm_debug_push( 'add_note_failed_deal_id__4_'.$field_id, 'Deal_id: '.$deal_return->data->id );
                        }else{
                            $this->wpgf2pdcrm_debug_push( 'add_note_success', 'Create note success note ID: '.$new_note_id );
                        }
                    }
                }
			}
			
			//upload files
			if( $files_url_array && count($files_url_array) > 0 ){
				$i = 1;
				foreach( $files_url_array as $file_url_to_upload ){
					if( trim($file_url_to_upload) == "" ){
						continue;
					}
					//now come to upload file to PipeDrive
					$this->wpgf2pdcrm_debug_push( 'file_url_to_upload_'.$i.' ', $file_url_to_upload);
				
					$file_name_array = explode('/', $file_url_to_upload);
					$file_name = $file_name_array[count($file_name_array) - 1];
                    
                    $site_url = site_url();
                    $site_url_no_ssl = str_replace( 'https', 'http', $site_url );
					$file_url_to_upload = str_replace( $site_url, '', $file_url_to_upload);
                    $file_path_to_upload = str_replace( $site_url_no_ssl, '', $file_url_to_upload);
                    
					$file_path_to_upload = ABSPATH.$file_path_to_upload;
					if( !file_exists($file_path_to_upload) ){
						$this->wpgf2pdcrm_debug_push( 'file_doesn\'t exist_'.$i, $file_path_to_upload);	
					}else{
						$upload_file_return = $this->wpgf2pdcrm_pipedrive_crm_upload_file_to_deal_HTTP_API($token, $deal_return->data->id, $file_path_to_upload, $file_name, $error_message );
						if( $upload_file_return < 1 ){
							$this->wpgf2pdcrm_debug_push( 'upload_file_failed_'.$i, 'Upload file failed: '.$error_message );
							
						}else{
							$this->wpgf2pdcrm_debug_push( 'upload_file_success_'.$i, 'Upload file succeed, file ID: '.$upload_file_return );
						}
					}
                    $i++;
				}
			}
			
			return $deal_return->data->id;
		}//end of function
		
		function wpgf2pdcrm_read_users_data( $token_saved ){
			//read all pipeline first
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/users?api_token='.$token_saved;
			$arg = array('method' => 'GET');
            $response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){

                return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$users_data = json_decode( $resp_body );
			if( !isset($users_data->success) || 
				!$users_data->success ||
				!isset($users_data->data) || 
				!is_array($users_data->data) || 
				count($users_data->data) < 1 ){

                return false;
			}
			$users_data_to_save = array();
			foreach( $users_data->data as $user_obj ){
				if( !$user_obj->active_flag ){
					continue;
				}
				$users_data_to_save[$user_obj->id] = array( 'name' => $user_obj->name, 'is_you' => $user_obj->is_you );
			}

            //no active pipeline
			if( count($users_data_to_save) < 1 ){
				update_option( $this->_wpgf2pdcrm_users_option_name, '' );
				return false;
			}

            update_option( $this->_wpgf2pdcrm_users_option_name, $users_data_to_save );

			return $users_data_to_save;
		}//end of function
		
		function wpgf2pdcrm_custom_fields_name_mapping_update( $custom_field_id ){
			$saved_array = get_option( $this->_wpgf2pdcrm_custom_fields_name_mapping_option, array() );
			if( !is_array( $saved_array ) ){
				$saved_array = array();
			}
			
			if( isset($saved_array[$custom_field_id]) ){
				return;
			}
			$saved_array[$custom_field_id] = 'u_'.count($saved_array);
			
			update_option( $this->_wpgf2pdcrm_custom_fields_name_mapping_option, $saved_array );
		}
		
		function wpgf2pdcrm_update_organisations_list_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			
			$organisations_download = array();
			
			$url = 'https://api.pipedrive.com/v1/organizations?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$organisations_data = json_decode( $resp_body );
			if( isset($organisations_data->success) &&
				$organisations_data->success &&
				isset($organisations_data->data) && 
				is_array($organisations_data->data) && 
				count($organisations_data->data) > 0 ){
					
				foreach( $organisations_data->data as $organistion_obj ){
					$organisations_download[$organistion_obj->id] = array( 'name' => $organistion_obj->name );
				}
			}
			
			$more_organisations = false;
			$next_start = 0;
			if( isset($organisations_data->additional_data) && isset($organisations_data->additional_data->pagination) && 
				isset($organisations_data->additional_data->pagination->more_items_in_collection) ){
				
				$more_organisations = $organisations_data->additional_data->pagination->more_items_in_collection;
				if( isset($organisations_data->additional_data->pagination->next_start) ){
					$next_start = $organisations_data->additional_data->pagination->next_start;
				}
			}
			
			while( $more_organisations && $next_start ){
				$url = 'https://api.pipedrive.com/v1/organizations?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
				$arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					return false;
				}
				$resp_body = wp_remote_retrieve_body( $response );
				$organisations_data = json_decode( $resp_body );
				if( isset($organisations_data->success) &&
					$organisations_data->success &&
					isset($organisations_data->data) && 
					is_array($organisations_data->data) && 
					count($organisations_data->data) > 0 ){
						
					foreach( $organisations_data->data as $organistion_obj ){
						$organisations_download[$organistion_obj->id] = array( 'name' => $organistion_obj->name );
					}
				}
				if( isset($organisations_data->additional_data) && isset($organisations_data->additional_data->pagination) && 
					isset($organisations_data->additional_data->pagination->more_items_in_collection) ){
					
					$more_organisations = $organisations_data->additional_data->pagination->more_items_in_collection;
					$next_start = $organisations_data->additional_data->pagination->next_start;
				}
			}
			
			asort( $organisations_download );
			
			update_option( $this->_wpgf2pdcrm_organisations_list_option_name, $organisations_download );
		}
		
		function wpgf2pdcrm_update_persons_list_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			
			$persons_download = array();
			
			$url = 'https://api.pipedrive.com/v1/persons?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$persons_data = json_decode( $resp_body );
			if( isset($persons_data->success) &&
				$persons_data->success &&
				isset($persons_data->data) && 
				is_array($persons_data->data) && 
				count($persons_data->data) > 0 ){
					
				foreach( $persons_data->data as $person_obj ){
					$person_obj_data_to_save = array( 'name' => $person_obj->name );
					if( isset($person_obj->org_id) && 
						isset($person_obj->org_id->name) ){
						$person_obj_data_to_save['org_name'] = $person_obj->org_id->name;
						$person_obj_data_to_save['org_id'] = $person_obj->org_id->value;
					}
					$persons_download[$person_obj->id] = $person_obj_data_to_save;
				}
			}
			
			$more_persons = false;
			$next_start = 0;
			if( isset($persons_data->additional_data) && isset($persons_data->additional_data->pagination) && 
				isset($persons_data->additional_data->pagination->more_items_in_collection) ){
				
				$more_persons = $persons_data->additional_data->pagination->more_items_in_collection;
				if( isset($persons_data->additional_data->pagination->next_start) ){
					$next_start = $persons_data->additional_data->pagination->next_start;
				}
			}
			
			while( $more_persons && $next_start ){
				$url = 'https://api.pipedrive.com/v1/persons?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
				$arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					return false;
				}
				$resp_body = wp_remote_retrieve_body( $response );
				$persons_data = json_decode( $resp_body );
				if( isset($persons_data->success) &&
					$persons_data->success &&
					isset($persons_data->data) && 
					is_array($persons_data->data) && 
					count($persons_data->data) > 0 ){
						
					foreach( $persons_data->data as $person_obj ){
						$person_obj_data_to_save = array( 'name' => $person_obj->name );
						if( isset($person_obj->org_id) && 
							isset($person_obj->org_id->name) ){
							$person_obj_data_to_save['org_name'] = $person_obj->org_id->name;
							$person_obj_data_to_save['org_id'] = $person_obj->org_id->value;
						}
						$persons_download[$person_obj->id] = $person_obj_data_to_save;
					}
				}
				if( isset($persons_data->additional_data) && isset($persons_data->additional_data->pagination) && 
					isset($persons_data->additional_data->pagination->more_items_in_collection) ){
					
					$more_persons = $persons_data->additional_data->pagination->more_items_in_collection;
					$next_start = $persons_data->additional_data->pagination->next_start;
				}
			}
			
			uasort( $persons_download, array($this, 'cmp_person') );
			
			update_option( $this->_wpgf2pdcrm_persons_list_option_name, $persons_download );
		}
		
		function cmp_person($a, $b) {
			return strnatcmp( $a['name'], $b['name'] );
		}
		
		function wpgf2pdcrm_get_person_by_email( $token_saved, $person_email ){
			if( $token_saved == "" || $person_email == "" ){
				return false;
			}
			
			$persons_download = array();
			
			$url = 'https://api.pipedrive.com/v1/persons/find?api_token='.$token_saved.'&start=0&limit=500&search_by_email=1&term='.$person_email;
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$persons_data = json_decode( $resp_body );
			if( isset($persons_data->success) &&
				$persons_data->success &&
				isset($persons_data->data) && 
				is_array($persons_data->data) && 
				count($persons_data->data) > 0 ){
					
				return $persons_data->data[0]->id;
			}
			
			return false;
		}
		
		function wpgf2pdcrm_get_org_by_name( $token_saved, $org_name ){
			if( $token_saved == "" || $org_name == "" ){
				return false;
			}
			
			$url = 'https://api.pipedrive.com/v1/organizations/find?api_token='.$token_saved.'&start=0&limit=500&term='.$org_name;
			$arg = array('method' => 'GET');
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$organisation_data = json_decode( $resp_body );

			if( isset($organisation_data->success) &&
				$organisation_data->success &&
				isset($organisation_data->data) && 
				is_array($organisation_data->data) && 
				count($organisation_data->data) > 0 ){
					
				foreach( $organisation_data->data as $org_obj ){
					if( strtoupper($org_obj->name) == strtoupper($org_name) ){
						return $org_obj->id;
					}
				}
			}
			
			return false;
		}
		
		function wpgf2pdcrm_read_activity_types( $token_saved ){
			//read all pipeline first
			if( $token_saved == "" ){
				return false;
			}
			$url = 'https://api.pipedrive.com/v1/activityTypes?api_token='.$token_saved;
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$activities_data = json_decode( $resp_body );
			if( !isset($activities_data->success) || 
				!$activities_data->success ||
				!isset($activities_data->data) || 
				!is_array($activities_data->data) || 
				count($activities_data->data) < 1 ){
					
				return false;
			}
			$activity_types_to_save = array();
			foreach( $activities_data->data as $activity_obj ){
				if( !$activity_obj->active_flag ){
					continue;
				}
				$activity_types_to_save[$activity_obj->key_string] = array( 'name' => $activity_obj->name );
			}
			//no active pipeline
			if( count($activity_types_to_save) < 1 ){
				update_option( $this->_wpgf2pdcrm_activity_types_option_name, '' );
				return false;
			}
			
			update_option( $this->_wpgf2pdcrm_activity_types_option_name, $activity_types_to_save );
			
			return $activity_types_to_save;
		}//end of function
		
		function wpgf2pdcrm_pipedrive_crm_create_activity( $token, $owner_id, $subject, $type, $done, $due_date, $due_time, $duration, $note, $deal_id, $person_id, $org_id, &$error_msg ){
			
			if( $token == "" ){
				$error_msg = 'Please save a Token first';
				return false;
			}
			
			$data_to_post = array( 'subject' => $subject, 'type' => $type, 'done' => intval($done), 
								   'due_date' => $due_date, 'due_time' => $due_time, 'duration' => $duration, 'note' => $note );
			if( $owner_id ){
				$data_to_post['user_id'] = $owner_id;
			}
			if( $deal_id ){
				$data_to_post['deal_id'] = $deal_id;
			}
			if( $person_id ){
				$data_to_post['person_id'] = $person_id;
			}
			if( $org_id ){
				$data_to_post['org_id'] = $org_id;
			}

			$response = wp_remote_post( 'https://api.pipedrive.com/v1/activities?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				$error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						$error_msg = '';
						return $deal_return->data->id;
					}else{
						$error_msg = $deal_return->error;
						
						return false;
					}
				}
			}
			
			return false;
		} //end of function
        
        function wpgf2pdcrm_update_products_list_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			
			$products_download = array();
			
			$url = 'https://api.pipedrive.com/v1/products?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$products_data = json_decode( $resp_body );
			if( isset($products_data->success) &&
				$products_data->success &&
				isset($products_data->data) && 
				is_array($products_data->data) && 
				count($products_data->data) > 0 ){
					
				foreach( $products_data->data as $product_obj ){
					$products_download[$product_obj->id] = array( 'name' => $product_obj->name );
				}
			}
			
			$more_products = false;
			$next_start = 0;
			if( isset($products_data->additional_data) && isset($products_data->additional_data->pagination) && 
				isset($products_data->additional_data->pagination->more_items_in_collection) ){
				
				$more_products = $products_data->additional_data->pagination->more_items_in_collection;
				if( isset($products_data->additional_data->pagination->next_start) ){
					$next_start = $products_data->additional_data->pagination->next_start;
				}
			}
			
			while( $more_products && $next_start ){
				$url = 'https://api.pipedrive.com/v1/products?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
				$arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					return false;
				}
				$resp_body = wp_remote_retrieve_body( $response );
				$products_data = json_decode( $resp_body );
				if( isset($products_data->success) &&
                    $products_data->success &&
                    isset($products_data->data) && 
                    is_array($products_data->data) && 
                    count($products_data->data) > 0 ){

                    foreach( $products_data->data as $product_obj ){
					    $products_download[$product_obj->id] = array( 'name' => $product_obj->name );
                    }
                }
				if( isset($products_data->additional_data) && isset($products_data->additional_data->pagination) && 
					isset($products_data->additional_data->pagination->more_items_in_collection) ){
					
					$more_products = $products_data->additional_data->pagination->more_items_in_collection;
					$next_start = $products_data->additional_data->pagination->next_start;
				}
			}
			
			asort( $products_download );
			
			update_option( $this->_wpgf2pdcrm_products_list_option_name, $products_download );
		}
        
        function wpgf2pdcrm_pipedrive_crm_attach_product( $token, 
                                                                                     $dela_id,       
                                                                                     $product_id, 
                                                                                     $product_price, 
                                                                                     $product_quantity, 
                                                                                     $product_discount, 
                                                                                     &$error_msg ){
            if( $token == "" ){
				$error_msg = 'Please save a Token first';
				return false;
			}
            
            $dela_id = intval( $dela_id );
            if( $dela_id < 1 ){
                $error_msg = 'Invalid deal ID: '.$dela_id;
				return false;
            }
			
			$data_to_post = array( 'product_id' => intval($product_id), 
                                             'item_price' => $product_price + 0, 
                                             'quantity' => intval($product_quantity), 
								             'discount_percentage' => $product_discount + 0 );
			if( $data_to_post['product_id'] < 1 || $data_to_post['item_price'] <= 0 || $data_to_post['quantity'] < 1 ){
                $error_msg = 'Product ID, Price & Quantity are mandatory fields';
                return false;
            }
            
			$response = wp_remote_post( 'https://api.pipedrive.com/v1/deals/'.$dela_id.'/products?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) ) 
									  );
			if( is_wp_error($response) ) {
				$error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
			}else{
				$resp_body = wp_remote_retrieve_body($response);
				if( $resp_body ){
					$deal_return = json_decode( $resp_body );
					if( is_object($deal_return) && isset($deal_return->success) && $deal_return->success && isset($deal_return->data) && is_object($deal_return->data)){
						$error_msg = '';
						return $deal_return->data->id;
					}else{
						$error_msg = $deal_return->error;
						
						return false;
					}
				}
			}
			
			return false;
            
        } //end of function
        
        function wpgf2pdcrm_pipedrive_crm_is_postal_address_enable_for_people(){
            $option = get_option( $this->_wpgf2pdcrm_enable_postal_address_for_people_option, false );
            
            return $option;
        }
        
        /*
         * for Lead
         */
        function wpgf2pdcrm_read_leads_labels_cache( $token_saved ){
			if( $token_saved == "" ){
				return false;
			}
			
			$labels_download = array();
			
			$url = 'https://api.pipedrive.com/v1/leadLabels?api_token='.$token_saved.'&start=0&limit=500';
			$arg = array('method' => 'GET');
			
			$response = wp_remote_post( $url, $arg );
			if( is_wp_error( $response ) ){
				return false;
			}
			$resp_body = wp_remote_retrieve_body( $response );
			$labels_data = json_decode( $resp_body );
			if( isset($labels_data->success) &&
				$labels_data->success &&
				isset($labels_data->data) && 
				is_array($labels_data->data) && 
				count($labels_data->data) > 0 ){
					
				foreach( $labels_data->data as $label_obj ){
					$labels_download[$label_obj->id] = array( 'name' => $label_obj->name );
				}
			}
			
			$more_labels = false;
			$next_start = 0;
			if( isset($labels_data->additional_data) && isset($labels_data->additional_data->pagination) && 
				isset($labels_data->additional_data->pagination->more_items_in_collection) ){
				
				$more_labels = $labels_data->additional_data->pagination->more_items_in_collection;
				if( isset($labels_data->additional_data->pagination->next_start) ){
					$next_start = $labels_data->additional_data->pagination->next_start;
				}
			}
			
			while( $more_labels && $next_start ){
				$url = 'https://api.pipedrive.com/v1/leadLabels?api_token='.$token_saved.'&start='.$next_start.'&limit=500';
				$arg = array('method' => 'GET');
				
				$response = wp_remote_post( $url, $arg );
				if( is_wp_error( $response ) ){
					return false;
				}
				$resp_body = wp_remote_retrieve_body( $response );
				$labels_data = json_decode( $resp_body );
				if( isset($labels_data->success) &&
                    $labels_data->success &&
                    isset($labels_data->data) && 
                    is_array($labels_data->data) && 
                    count($labels_data->data) > 0 ){

                    foreach( $labels_data->data as $product_obj ){
					    $labels_download[$product_obj->id] = array( 'name' => $product_obj->name );
                    }
                }
				if( isset($labels_data->additional_data) && isset($labels_data->additional_data->pagination) && 
					isset($labels_data->additional_data->pagination->more_items_in_collection) ){
					
					$more_labels = $labels_data->additional_data->pagination->more_items_in_collection;
					$next_start = $labels_data->additional_data->pagination->next_start;
				}
			}
			
			asort( $labels_download );
			
            //no labels
			if( count($labels_download) < 1 ){
				update_option( $this->_wpgf2pdcrm_leads_labels_option_name, '' );
				return false;
			}

            update_option( $this->_wpgf2pdcrm_leads_labels_option_name, $labels_download );
			
			return $labels_download;
		}

        function wpgf2pdcrm_pipedrive_crm_create_lead( $token, $data_to_post, &$error_msg ){
			if( $token == "" ){
				return false;
			}
            
            if( isset( $data_to_post['org_id'] ) ){
                $data_to_post['organization_id'] = intval( $data_to_post['org_id'] );
                unset( $data_to_post['org_id'] );
            }
            
            $response = wp_remote_post( 'https://api.pipedrive.com/v1/leads?api_token='.$token, 
										array( 'method' => 'POST',
											   'headers' => array('Content-Type' => 'application/json'), 
											   'timeout' => 15, 
											   'body' => json_encode($data_to_post) )
									  );
			if( is_wp_error($response) ) {
                $error_msg = 'ERROR: WordPress API "wp_remote_post" encountered an ERROR, please try again. '."\n".$response->get_error_message();
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post',  $error_msg );
				
				return false;
			}
			$resp_body = wp_remote_retrieve_body($response);
			if( !$resp_body ){
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post_response', 'ERROR: retrive response body failed.' );
				
				return false;
			}

            $deal_return = json_decode( $resp_body );
			if( !is_object($deal_return) ||  !isset($deal_return->success) || !$deal_return->success || !isset($deal_return->data) || !is_object($deal_return->data)){
				$this->wpgf2pdcrm_debug_push( 'wp_remote_post_response', 'ERROR: post deal failed, return error message: '.$deal_return->error );
				$error_msg = $deal_return->error;
				
				return false;
			}
			//SUCCESS
			$this->wpgf2pdcrm_debug_push( 'create deal success', 'return deal id: '.$deal_return->data->id );
			
			return $deal_return->data->id;
		}//end of function
		
    }//end of class
}
