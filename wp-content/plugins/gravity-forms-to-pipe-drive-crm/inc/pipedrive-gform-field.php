<?php

class WPGravityFormsToPipedriveFormField {
	
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
	var $_wpgf2pdcrm_fields_by_group = array();
	var $_wpgf2pdcrm_custom_fields_type_description = array();
	
    var $_wpgf2pdcrm_organisations_list_option_name = '';
    var $_wpgf2pdcrm_persons_list_option_name = '';
    var $_wpgf2pdcrm_activity_types_cache_option = '';
    var $_wpgf2pdcrm_products_list_option_name = '';
    
    var $_wpgf2pdcrm_enable_cache_organisations_option = '';
    var $_wpgf2pdcrm_enable_cache_people_option = '';
    var $_wpgf2pdcrm_enable_cache_product_list_option = '';
    
	var $_wpgf2pdcrm_deal_title_custom_text_array = '';
	var $_wpgf2pdcrm_deal_title_custom_text_key_prefix = '';
	
	var $_wpgf2pdcrm_plugin_folder_url = '';

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
		$this->_wpgf2pdcrm_activity_types_cache_option = $args['activity_types_cache_option'];
        $this->_wpgf2pdcrm_products_list_option_name = $args['products_list_cache_option'];
        
        $this->_wpgf2pdcrm_enable_cache_organisations_option = $args['enable_cache_organisations_option'];
        $this->_wpgf2pdcrm_enable_cache_people_option = $args['enable_cache_people_option'];
        $this->_wpgf2pdcrm_enable_cache_product_list_option = $args['enable_cache_product_list_option'];
		
		$this->_wpgf2pdcrm_deal_title_custom_text_array = $args['deal_title_custom_text_array_option'];
		$this->_wpgf2pdcrm_deal_title_custom_text_key_prefix = $args['deal_title_custom_text_key_prefix'];
		
		$this->_wpgf2pdcrm_plugin_folder_url = $args['plugin_folder_url'];
					
		add_action( 'gform_field_advanced_settings', array($this, 'wpgf2pdcrm_render_field_advanced_settings'), 10, 2 );
        add_action( 'gform_editor_js', array($this, 'wpgf2pdcrm_render_editor_js') );
		
		// filter to add a new tooltip
        add_filter( 'gform_tooltips', array($this, 'wpgf2pdcrm_add_gf_tooltips') );
		
		//update filed choices
		add_filter( 'gform_pre_render', array($this, 'wpgf2pdcrm_populate_choices' ), 9999, 1 );
		add_filter( 'gform_pre_validation', array($this, 'wpgf2pdcrm_populate_choices' ), 9999, 1 );
		add_filter( 'gform_admin_pre_render', array($this, 'wpgf2pdcrm_populate_choices' ), 9999, 1 );
		add_filter( 'gform_pre_submission_filter', array($this, 'wpgf2pdcrm_populate_choices' ), 9999, 1 );
        
        add_filter( 'gform_entry_field_value', array($this, 'wpgf2pdcrm_populate_choices_to_entry' ), 10, 4 );
	}

	function wpgf2pdcrm_render_field_advanced_settings( $position, $form_id ){
		$license_key = get_option ('wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		if( !$license_key || $license_key_status != 'valid' ){
			return;
		}
		if($position == 50){
			?>
            <?php if( get_option( $this->_wpgf2pdcrm_enable_cache_organisations_option, false ) == true ){ ?>
            <li class="populate_with_pipedrive_organisations_field_setting field_setting" style="display:list-item;">
				<input type="checkbox" class="toggle_setting" id="field_enable_populate_with_pipedrive_organisations" />
				<label for="field_enable_populate_with_pipedrive_organisations" class="inline">
					<?php _e("Populate with Pipedrive Organisations", "gravityforms"); ?>
				</label>
				<?php gform_tooltip("form_field_pipedrive_organisations") ?><br />
                <div id="pipedrive_organisation_display_what_on_entry_view_Container" style="margin-top: 10px; display: none;">
                    <label>Display what on entry view?</label>
                    <input type="radio" name="pipedrive_organisation_display_what_on_entry_view" id="pipedrive_organisation_display_what_on_entry_view_ID" size="10" value="OPT_ID" onclick="return SetFieldProperty('displayOrganisationOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayOrganisationOptonOnEntryView', jQuery(this).val())">
                    <label for="pipedrive_organisation_display_what_on_entry_view_ID" class="inline">Value</label>
                    <input type="radio" name="pipedrive_organisation_display_what_on_entry_view" id="pipedrive_organisation_display_what_on_entry_view_LABEL" size="10" value="OPT_LABEL" onclick="return SetFieldProperty('displayOrganisationOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayOrganisationOptonOnEntryView', jQuery(this).val());">
                    <label for="pipedrive_organisation_display_what_on_entry_view_LABEL" class="inline">Label</label>
                </div>
			</li>
            <?php } ?>
            <?php if( get_option( $this->_wpgf2pdcrm_enable_cache_people_option, false ) == true ){ ?>
			<li class="populate_with_pipedrive_people_field_setting field_setting" style="display:list-item;">
				<input type="checkbox" class="toggle_setting" id="field_enable_populate_with_pipedrive_people" />
				<label for="field_enable_populate_with_pipedrive_people" class="inline">
					<?php _e("Populate with Pipedrive People", "gravityforms"); ?>
				</label>
				<?php gform_tooltip("form_field_pipedrive_people") ?><br />
                <div id="pipedrive_people_display_what_on_entry_view_Container" style="margin-top: 10px; display: none;">
                    <label>Display what on entry view?</label>
                    <input type="radio" name="pipedrive_people_display_what_on_entry_view" id="pipedrive_people_display_what_on_entry_view_ID" size="10" value="OPT_ID" onclick="return SetFieldProperty('displayPeopleOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayPeopleOptonOnEntryView', jQuery(this).val())">
                    <label for="pipedrive_people_display_what_on_entry_view_ID" class="inline">Value</label>
                    <input type="radio" name="pipedrive_people_display_what_on_entry_view" id="pipedrive_people_display_what_on_entry_view_LABEL" size="10" value="OPT_LABEL" onclick="return SetFieldProperty('displayPeopleOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayPeopleOptonOnEntryView', jQuery(this).val());">
                    <label for="pipedrive_people_display_what_on_entry_view_LABEL" class="inline">Label</label>
                </div>
			</li>
            <?php } ?>
            <?php if( get_option( $this->_wpgf2pdcrm_enable_cache_product_list_option, false ) == true ){ ?>
            <li class="populate_with_pipedrive_products_field_setting field_setting" style="display:list-item;">
				<input type="checkbox" class="toggle_setting" id="populate_with_pipedrive_products" />
				<label for="populate_with_pipedrive_products" class="inline">
					<?php _e("Populate with Products from pipedrive", "gravityforms"); ?>
				</label>
				<?php gform_tooltip("form_field_pipedrive_products") ?><br />
                <div id="pipedrive_product_display_what_on_entry_view_Container" style="margin-top: 10px; display: none;">
                    <label>Display what on entry view?</label>
                    <input type="radio" name="pipedrive_product_display_what_on_entry_view" id="pipedrive_product_display_what_on_entry_view_ID" size="10" value="OPT_ID" onclick="return SetFieldProperty('displayProductOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayProductOptonOnEntryView', jQuery(this).val())">
                    <label for="pipedrive_product_display_what_on_entry_view_ID" class="inline">Value</label>
                    <input type="radio" name="pipedrive_product_display_what_on_entry_view" id="pipedrive_product_display_what_on_entry_view_LABEL" size="10" value="OPT_LABEL" onclick="return SetFieldProperty('displayProductOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayProductOptonOnEntryView', jQuery(this).val());">
                    <label for="pipedrive_product_display_what_on_entry_view_LABEL" class="inline">Label</label>
                </div>
			</li>
            <?php } ?>
            <li class="populate_with_pipedrive_custom_fields_field_setting field_setting" style="display:list-item;">
				<input type="checkbox" class="toggle_setting" id="field_enable_populate_with_pipedrive_custom_fields" />
				<label for="field_enable_populate_with_pipedrive_custom_fields" class="inline">
					<?php _e("Populate with Pipedrive fields options", "gravityforms"); ?>
				</label>
				<?php gform_tooltip("form_field_pipedrive_custom_field") ?><br />
                <select id="field_populate_taxonomy" onchange="SetFieldProperty('populatePipedriveCustomField', jQuery(this).val());" style="margin-top:10px; display:none;" class="field_populate_with_pipedrive_custom_fields_select">
                    <option value="" style="color:#999;">Select a Pipedrive field</option>
                    <optgroup label="Deal ( Custom ) Fields">
                    <?php
                    $deals_built_in_fields = get_option( $this->_wpgf2pdcrm_deal_fields_options_cache_option_name, '' );
                    if( $deals_built_in_fields && is_array($deals_built_in_fields) && count($deals_built_in_fields) > 0 ){
                        foreach ( $deals_built_in_fields as $key => $child_field ) {
                            echo '<option value="DEALBUILTIN_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    $deals_custom_fields = get_option( $this->_wpgf2pdcrm_deal_custom_field_option_name, '' );
                    if( $deals_custom_fields && is_array($deals_custom_fields) && count($deals_custom_fields) > 0 ){
                        foreach ( $deals_custom_fields as $key => $child_field ) {
                            if( $child_field['type'] != 'set' && $child_field['type'] != 'enum' ){
                                continue;
                            }
                            echo '<option value="DEAL_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    ?>
                    </optgroup>
                    <optgroup label="Organisations ( Custom ) Fields">
                    <?php
                    $org_built_in_fields = get_option( $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name, '' );
                    if( $org_built_in_fields && is_array($org_built_in_fields) && count($org_built_in_fields) > 0 ){
                        foreach ( $org_built_in_fields as $key => $child_field ) {
                            echo '<option value="ORGBUILTIN_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    $org_custom_fields = get_option( $this->_wpgf2pdcrm_organisation_custom_field_option_name, '' );
                    if( $org_custom_fields && is_array($org_custom_fields) && count($org_custom_fields) > 0 ){
                        foreach ( $org_custom_fields as $key => $child_field ) {
                            if( $child_field['type'] != 'set' && $child_field['type'] != 'enum' ){
                                continue;
                            }
                            echo '<option value="ORG_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    ?>
                    </optgroup>
                    <optgroup label="People ( Custom ) Fields">
                    <?php
                    $people_built_in_fields = get_option( $this->_wpgf2pdcrm_people_fields_options_cache_option_name, '' );
                    if( $people_built_in_fields && is_array($people_built_in_fields) && count($people_built_in_fields) > 0 ){
                        foreach ( $people_built_in_fields as $key => $child_field ) {
                            echo '<option value="PEOPLEBUILTIN_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    $people_custom_fields = get_option( $this->_wpgf2pdcrm_people_custom_field_option_name, '' );
                    if( $people_custom_fields && is_array($people_custom_fields) && count($people_custom_fields) > 0 ){
                        foreach ( $people_custom_fields as $key => $child_field ) {
                            if( $child_field['type'] != 'set' && $child_field['type'] != 'enum' ){
                                continue;
                            }
                            echo '<option value="PEOPLE_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    ?>
                    </optgroup>
                    <optgroup label="Product ( Custom ) Fields">
                    <?php
                    $product_built_in_fields = get_option( $this->_wpgf2pdcrm_product_fields_options_cache_option_name, '' );
                    if( $product_built_in_fields && is_array($product_built_in_fields) && count($product_built_in_fields) > 0 ){
                        foreach ( $product_built_in_fields as $key => $child_field ) {
                            echo '<option value="PRDBUILTIN_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    $product_custom_fields = get_option( $this->_wpgf2pdcrm_product_custom_field_option_name, '' );
                    if( $product_custom_fields && is_array($product_custom_fields) && count($product_custom_fields) > 0 ){
                        foreach ( $product_custom_fields as $key => $child_field ) {
                            if( $child_field['type'] != 'set' && $child_field['type'] != 'enum' ){
                                continue;
                            }
                            echo '<option value="PRD_'.$key.'">'.$child_field['label'].'</option>';
                        }
                    }
                    ?>
                    </optgroup>
                </select>
                <div id="pipedrive_custom_fields_display_what_on_entry_view_Container" style="margin-top: 10px; display: none;">
                    <label>Display what on entry view?</label>
                    <input type="radio" name="pipedrive_custom_fields_display_what_on_entry_view" id="pipedrive_custom_fields_display_what_on_entry_view_ID" size="10" value="OPT_ID" onclick="return SetFieldProperty('displayPipedriveCustomFieldOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayPipedriveCustomFieldOptonOnEntryView', jQuery(this).val())">
                    <label for="pipedrive_custom_fields_display_what_on_entry_view_ID" class="inline">Value</label>
                    <input type="radio" name="pipedrive_custom_fields_display_what_on_entry_view" id="pipedrive_custom_fields_display_what_on_entry_view_LABEL" size="10" value="OPT_LABEL" onclick="return SetFieldProperty('displayPipedriveCustomFieldOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayPipedriveCustomFieldOptonOnEntryView', jQuery(this).val());">
                    <label for="pipedrive_custom_fields_display_what_on_entry_view_LABEL" class="inline">Label</label>
                </div>
			</li>
            <li class="populate_with_pipedrive_activity_type_field_setting field_setting" style="display:list-item;">
				<input type="checkbox" class="toggle_setting" id="field_enable_populate_with_pipedrive_activity_type" />
				<label for="field_enable_populate_with_pipedrive_activity_type" class="inline">
					<?php _e("Populate with Pipedrive Activity types", "gravityforms"); ?>
				</label>
				<?php gform_tooltip("form_field_pipedrive_activity_type") ?><br />
                <div id="pipedrive_activity_display_what_on_entry_view_Container" style="margin-top: 10px; display: none;">
                    <label>Display what on entry view?</label>
                    <input type="radio" name="pipedrive_activity_display_what_on_entry_view" id="pipedrive_activity_display_what_on_entry_view_ID" size="10" value="OPT_ID" onclick="return SetFieldProperty('displayActivityOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayActivityOptonOnEntryView', jQuery(this).val())">
                    <label for="pipedrive_activity_display_what_on_entry_view_ID" class="inline">Value</label>
                    <input type="radio" name="pipedrive_activity_display_what_on_entry_view" id="pipedrive_activity_display_what_on_entry_view_LABEL" size="10" value="OPT_LABEL" onclick="return SetFieldProperty('displayActivityOptonOnEntryView', jQuery(this).val());" onkeypress="return SetFieldProperty('displayActivityOptonOnEntryView', jQuery(this).val());">
                    <label for="pipedrive_activity_display_what_on_entry_view_LABEL" class="inline">Label</label>
                </div>
			</li>
			<?php
		}
	}
	
	/*
	 * render some custom JS to get the settings to work
	 */
	function wpgf2pdcrm_render_editor_js(){
		$license_key = get_option( 'wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		if( !$license_key || $license_key_status != 'valid' ){
			return;
		}
		?>
		<script type='text/javascript'>

			jQuery(document).bind("gform_load_field_settings", function(event, field, form){
				//only show taxonomy for selects and radios
				var valid_types = new Array( 'select', 'radio', 'checkbox', 'multiselect', 'chainedselect' );

				if(jQuery.inArray(field['type'], valid_types) != -1) {
					
					//for people
                    if( jQuery(".populate_with_pipedrive_people_field_setting").length > 0 ){
                        var $taxonomy_setting_container = jQuery(".populate_with_pipedrive_people_field_setting");
                        if( $taxonomy_setting_container ){
                            //show the setting container!
                            $taxonomy_setting_container.show();

                            //get the saved taxonomy
                            var populateTaxonomy = (typeof field['populatePipedrivePeople'] != 'undefined' && field['populatePipedrivePeople'] != '') ? field['populatePipedrivePeople'] : false;

                            if (populateTaxonomy != false) {
                                //check the checkbox if previously checked
                                $taxonomy_setting_container.find("input:checkbox").attr("checked", "checked");
                                
                                $taxonomy_setting_container.find("#pipedrive_people_display_what_on_entry_view_Container").show();
                            } else {
                                $taxonomy_setting_container.find("input:checkbox").removeAttr("checked");
                                $taxonomy_setting_container.find("#pipedrive_people_display_what_on_entry_view_Container").hide();
                            }
                            
                            //display waht on entry view
                            var displayWhat = (typeof field['displayPeopleOptonOnEntryView'] != 'undefined' && field['displayPeopleOptonOnEntryView'] != '') ? field['displayPeopleOptonOnEntryView'] : false;
                            if( displayWhat == 'OPT_LABEL' ){
                                $taxonomy_setting_container.find("#pipedrive_people_display_what_on_entry_view_Container").find("#pipedrive_people_display_what_on_entry_view_LABEL").attr("checked", "checked");
                            }else{
                                $taxonomy_setting_container.find("#pipedrive_people_display_what_on_entry_view_Container").find("#pipedrive_people_display_what_on_entry_view_ID").attr("checked", "checked");
                            }
                        }
                    }
					
					
					//for organisations
                    if( jQuery(".populate_with_pipedrive_organisations_field_setting").length > 0 ){
                        var $taxonomy_setting_container = jQuery(".populate_with_pipedrive_organisations_field_setting");
                        if( $taxonomy_setting_container ){
                            //show the setting container!
                            $taxonomy_setting_container.show();

                            //get the saved taxonomy
                            var populateTaxonomy = (typeof field['populatePipedriveOrganisations'] != 'undefined' && field['populatePipedriveOrganisations'] != '') ? field['populatePipedriveOrganisations'] : false;

                            if (populateTaxonomy != false) {
                                //check the checkbox if previously checked
                                $taxonomy_setting_container.find("input:checkbox").attr("checked", "checked");
                                
                                $taxonomy_setting_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").show();
                            } else {
                                $taxonomy_setting_container.find("input:checkbox").removeAttr("checked");
                                $taxonomy_setting_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").hide();
                            }
                            
                            //display waht on entry view
                            var displayWhat = (typeof field['displayOrganisationOptonOnEntryView'] != 'undefined' && field['displayOrganisationOptonOnEntryView'] != '') ? field['displayOrganisationOptonOnEntryView'] : false;
                            if( displayWhat == 'OPT_LABEL' ){
                                $taxonomy_setting_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").find("#pipedrive_organisation_display_what_on_entry_view_LABEL").attr("checked", "checked");
                            }else{
                                $taxonomy_setting_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").find("#pipedrive_organisation_display_what_on_entry_view_ID").attr("checked", "checked");
                            }
                        }
                    }
					
                    
                    //for products
                    if( jQuery(".populate_with_pipedrive_products_field_setting").length > 0 ){
                        var $taxonomy_setting_container = jQuery(".populate_with_pipedrive_products_field_setting");
                        if( $taxonomy_setting_container ){
                            //show the setting container!
                            $taxonomy_setting_container.show();

                            //get the saved taxonomy
                            var populateTaxonomy = (typeof field['populatePipedriveProducts'] != 'undefined' && field['populatePipedriveProducts'] != '') ? field['populatePipedriveProducts'] : false;

                            if (populateTaxonomy != false) {
                                //check the checkbox if previously checked
                                $taxonomy_setting_container.find("input:checkbox").attr("checked", "checked");
                                
                                $taxonomy_setting_container.find("#pipedrive_product_display_what_on_entry_view_Container").show();
                            } else {
                                $taxonomy_setting_container.find("input:checkbox").removeAttr("checked");
                                $taxonomy_setting_container.find("#pipedrive_product_display_what_on_entry_view_Container").hide();
                            }
                            
                            //display waht on entry view
                            var displayWhat = (typeof field['displayProductOptonOnEntryView'] != 'undefined' && field['displayProductOptonOnEntryView'] != '') ? field['displayProductOptonOnEntryView'] : false;
                            if( displayWhat == 'OPT_LABEL' ){
                                $taxonomy_setting_container.find("#pipedrive_product_display_what_on_entry_view_Container").find("#pipedrive_product_display_what_on_entry_view_LABEL").attr("checked", "checked");
                            }else{
                                $taxonomy_setting_container.find("#pipedrive_product_display_what_on_entry_view_Container").find("#pipedrive_product_display_what_on_entry_view_ID").attr("checked", "checked");
                            }
                        }
                    }
                    
					
					//for custom fields
					var $taxonomy_setting_container = jQuery(".populate_with_pipedrive_custom_fields_field_setting");
					if( $taxonomy_setting_container ){
						//show the setting container!
						$taxonomy_setting_container.show();
	
						//get the saved taxonomy
						var populateTaxonomy = (typeof field['populatePipedriveCustomField'] != 'undefined' && field['populatePipedriveCustomField'] != '') ? field['populatePipedriveCustomField'] : false;
	
						if (populateTaxonomy != false) {
							//check the checkbox if previously checked
							$taxonomy_setting_container.find("input:checkbox").attr("checked", "checked");
							//set the select and show
							$taxonomy_setting_container.find("select").val(populateTaxonomy).show();
                            
                            $taxonomy_setting_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").show();
						} else {
							$taxonomy_setting_container.find("input:checkbox").removeAttr("checked");
							$taxonomy_setting_container.find("select").val('').hide();
                            $taxonomy_setting_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").hide();
						}
                        
                        //display waht on entry view
                        var displayWhat = (typeof field['displayPipedriveCustomFieldOptonOnEntryView'] != 'undefined' && field['displayPipedriveCustomFieldOptonOnEntryView'] != '') ? field['displayPipedriveCustomFieldOptonOnEntryView'] : false;
                        if( displayWhat == 'OPT_LABEL' ){
                            $taxonomy_setting_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").find("#pipedrive_custom_fields_display_what_on_entry_view_LABEL").attr("checked", "checked");
                        }else{
                            $taxonomy_setting_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").find("#pipedrive_custom_fields_display_what_on_entry_view_ID").attr("checked", "checked");
                        }
					}
					
					//for activity type
					var $taxonomy_setting_container = jQuery(".populate_with_pipedrive_activity_type_field_setting");
                    if( $taxonomy_setting_container ){
                        //show the setting container!
                        $taxonomy_setting_container.show();

                        //get the saved taxonomy
                        var populateTaxonomy = (typeof field['populatePipedriveActivityType'] != 'undefined' && field['populatePipedriveActivityType'] != '') ? field['populatePipedriveActivityType'] : false;

                        if (populateTaxonomy != false) {
                            //check the checkbox if previously checked
                            $taxonomy_setting_container.find("input:checkbox").attr("checked", "checked");
                            
                            $taxonomy_setting_container.find("#pipedrive_activity_display_what_on_entry_view_Container").show();
                        } else {
                            $taxonomy_setting_container.find("input:checkbox").removeAttr("checked");
                            $taxonomy_setting_container.find("#pipedrive_activity_display_what_on_entry_view_Container").hide();
                        }
                        
                        //display waht on entry view
                        var displayWhat = (typeof field['displayActivityOptonOnEntryView'] != 'undefined' && field['displayActivityOptonOnEntryView'] != '') ? field['displayActivityOptonOnEntryView'] : false;
                        if( displayWhat == 'OPT_LABEL' ){
                            $taxonomy_setting_container.find("#pipedrive_activity_display_what_on_entry_view_Container").find("#pipedrive_activity_display_what_on_entry_view_LABEL").attr("checked", "checked");
                        }else{
                            $taxonomy_setting_container.find("#pipedrive_activity_display_what_on_entry_view_Container").find("#pipedrive_activity_display_what_on_entry_view_ID").attr("checked", "checked");
                        }
                    }
				}
			});

			jQuery(".populate_with_pipedrive_people_field_setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var $select = jQuery(this).parent(".populate_with_pipedrive_people_field_setting:first").find("select");
				if(checked){
					$select.slideDown();
					SetFieldProperty('populatePipedrivePeople', true);
					
					//uncheck organisations
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_organisations_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveOrganisations','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").slideUp();
					
					//uncheck customf fields
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_custom_fields_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					var $pt_div = $pt_container.find("select");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveCustomField','');
						$pt_div.slideUp();
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
					
					//uncheck activity type
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_activity_type_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveActivityType','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_activity_display_what_on_entry_view_Container").slideUp();
					
                    //uncheck product
                    var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_products_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveProducts','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_product_display_what_on_entry_view_Container").slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_people_display_what_on_entry_view_Container").slideDown();
				} else {
					SetFieldProperty('populatePipedrivePeople','');
					$select.slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_people_display_what_on_entry_view_Container").slideUp();
				}
			});
			
			jQuery(".populate_with_pipedrive_organisations_field_setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var $select = jQuery(this).parent(".populate_with_pipedrive_organisations_field_setting:first").find("select");
				if(checked){
					$select.slideDown();
					SetFieldProperty('populatePipedriveOrganisations', true);
					
					//uncheck people
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_people_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedrivePeople','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_people_display_what_on_entry_view_Container").slideUp();
					
					//uncheck customf fields
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_custom_fields_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					var $pt_div = $pt_container.find("select");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveCustomField','');
						$pt_div.slideUp();
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
					
					//uncheck activity type
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_activity_type_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveActivityType','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_activity_display_what_on_entry_view_Container").slideUp();
                    
                    //uncheck product
                    var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_products_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveProducts','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_product_display_what_on_entry_view_Container").slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_organisation_display_what_on_entry_view_Container").slideDown();
				} else {
					SetFieldProperty('populatePipedriveOrganisations','');
					$select.slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_organisation_display_what_on_entry_view_Container").slideUp();
				}
			});
			
			jQuery(".populate_with_pipedrive_custom_fields_field_setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var $select = jQuery(this).parent(".populate_with_pipedrive_custom_fields_field_setting:first").find("select");
				if(checked){
					$select.slideDown();
                    
                    if( jQuery(this).val() != '' ){
                        jQuery(this).parent().find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideDown();
                    }
					
					//uncheck people
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_people_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedrivePeople','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_people_display_what_on_entry_view_Container").slideUp();
					
					//uncheck organisations
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_organisations_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveOrganisations','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").slideUp();
					
					//uncheck activity type
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_activity_type_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveActivityType','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_activity_display_what_on_entry_view_Container").slideUp();
                    
                    //uncheck product
                    var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_products_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveProducts','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_product_display_what_on_entry_view_Container").slideUp();
				} else {
					SetFieldProperty('populatePipedriveCustomField','');
					$select.slideUp();
                    jQuery(this).parent().find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
				}
			});
			
			jQuery(".populate_with_pipedrive_activity_type_field_setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var $select = jQuery(this).parent(".populate_with_pipedrive_activity_type_field_setting:first").find("select");
				if(checked){
					$select.slideDown();
					SetFieldProperty('populatePipedriveActivityType', true);
					
					//uncheck people
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_people_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedrivePeople','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_people_display_what_on_entry_view_Container").slideUp();
					
					//uncheck customf fields
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_custom_fields_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					var $pt_div = $pt_container.find("select");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveCustomField','');
						$pt_div.slideUp();
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
					
					//uncheck organisations
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_organisations_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveOrganisations','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").slideUp();
                    
                    //uncheck product
                    var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_products_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveProducts','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_product_display_what_on_entry_view_Container").slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_activity_display_what_on_entry_view_Container").slideDown();
				} else {
					SetFieldProperty('populatePipedriveActivityType','');
					$select.slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_activity_display_what_on_entry_view_Container").slideUp();
				}
			});
            
            jQuery(".populate_with_pipedrive_products_field_setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var $select = jQuery(this).parent(".populate_with_pipedrive_products_field_setting:first").find("select");
				if(checked){
					$select.slideDown();
					SetFieldProperty('populatePipedriveProducts', true);
					
					//uncheck people
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_people_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedrivePeople','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_people_display_what_on_entry_view_Container").slideUp();
					
					//uncheck customf fields
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_custom_fields_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					var $pt_div = $pt_container.find("select");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveCustomField','');
						$pt_div.slideUp();
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
					
					//uncheck organisations
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_organisations_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveOrganisations','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_organisation_display_what_on_entry_view_Container").slideUp();
                    
                    //uncheck activity type
					var $pt_container = jQuery(this).parents("ul:first").find(".populate_with_pipedrive_activity_type_field_setting:first");
					var $pt_check = $pt_container.find("input.toggle_setting");
					if ($pt_check.is(":checked")) {
						SetFieldProperty('populatePipedriveActivityType','');
						$pt_check.removeAttr('checked');
					}
                    $pt_container.find("#pipedrive_activity_display_what_on_entry_view_Container").slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_product_display_what_on_entry_view_Container").slideDown();
				} else {
					SetFieldProperty('populatePipedriveProducts','');
					$select.slideUp();
                    
                    jQuery(this).parent().find("#pipedrive_product_display_what_on_entry_view_Container").slideUp();
				}
			});
            
            jQuery(".field_populate_with_pipedrive_custom_fields_select").on( 'change', function() {
                if( jQuery(this).val() != '' ){
                    jQuery(this).parent().find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideDown();
                }else{
                    jQuery(this).parent().find("#pipedrive_custom_fields_display_what_on_entry_view_Container").slideUp();
                }
            });
            
			
		</script>
		<?php
	}
	
	/*
     * Add tooltips for the new field values
	 */
	function wpgf2pdcrm_add_gf_tooltips($tooltips){
		$license_key = get_option ('wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		if( !$license_key || $license_key_status != 'valid' ){
			return $tooltips;
		}
		
		$tooltips["form_field_pipedrive_people"] = "<h6>Populate with PipeDrive People</h6>Check this box to populate this field from PipeDrive People.";
		$tooltips["form_field_pipedrive_organisations"] = "<h6>Populate with PipeDrive Organisations</h6>Check this box to populate this field from PipeDrive Organisations.";
		$tooltips["form_field_pipedrive_custom_field"] = "<h6>Populate with PipeDrive custom fields</h6>Check this box to populate this field from PipeDrive custom fields.";
		$tooltips["form_field_pipedrive_activity_type"] = "<h6>Populate with PipeDrive Activity types</h6>Check this box to populate this field from PipeDrive Activity types.";
        $tooltips["form_field_pipedrive_products"] = "<h6>Populate with Products from pipedrive</h6>Check this box to populate this field from PipeDrive products.";
        
		
		return $tooltips;
	}	
	
	function wpgf2pdcrm_populate_choices( $form ) {
		
		$license_key = get_option ('wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		if( !$license_key || $license_key_status != 'valid' ){
			return $form;
		}

		foreach ( $form['fields'] as &$field ) {
			
			$choices = array();

			if ( $field->populatePipedrivePeople ) {
				//organise people
				$people_list = get_option( $this->_wpgf2pdcrm_persons_list_option_name );
				if( $people_list && is_array($people_list) && count($people_list) > 0 ){
					foreach( $people_list as $person_id => $person_data_node ){
						$choices[] = array( 'text' => $person_data_node['name'], 'value' => $person_id );
					}
				}
				$field['choices'] = $choices;
			}else if ( $field->populatePipedriveOrganisations ) {
				//organise organisation
				$organisations_list = get_option( $this->_wpgf2pdcrm_organisations_list_option_name );
				if( $organisations_list && is_array($organisations_list) && count($organisations_list) > 0 ){
					foreach( $organisations_list as $org_id => $org_data_node ){
						$choices[] = array( 'text' => $org_data_node['name'], 'value' => $org_id );
					}
				}
				$field['choices'] = $choices;
			}else if ( $field->populatePipedriveCustomField ) {
				//organise people
				$custom_field_option_name = '';
				$custom_field_key = '';
                if( strpos( $field->populatePipedriveCustomField, 'DEALBUILTIN_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_deal_fields_options_cache_option_name;
					$custom_field_key = str_replace( 'DEALBUILTIN_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'PEOPLEBUILTIN_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_people_custom_field_option_name;
					$custom_field_key = str_replace( 
                                                     'PEOPLEBUILTIN_', 
                                                     '', 
                                                     $field->_wpgf2pdcrm_people_fields_options_cache_option_name 
                                                   );
				}else if( strpos( $field->populatePipedriveCustomField, 'ORGBUILTIN_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name;
					$custom_field_key = str_replace( 'ORGBUILTIN_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'PRDBUILTIN_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_product_fields_options_cache_option_name;
					$custom_field_key = str_replace( 'PRDBUILTIN_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'DEAL_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_deal_custom_field_option_name;
					$custom_field_key = str_replace( 'DEAL_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'PEOPLE_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_people_custom_field_option_name;
					$custom_field_key = str_replace( 'PEOPLE_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'ORG_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_organisation_custom_field_option_name;
					$custom_field_key = str_replace( 'ORG_', '', $field->populatePipedriveCustomField );
				}else if( strpos( $field->populatePipedriveCustomField, 'PRD_' ) !== false ){
					$custom_field_option_name = $this->_wpgf2pdcrm_product_custom_field_option_name;
					$custom_field_key = str_replace( 'PRD_', '', $field->populatePipedriveCustomField );
				}
                
                if( $custom_field_option_name && $custom_field_key ){
					$custom_field_cache = get_option( $custom_field_option_name );
					if( $custom_field_cache && is_array($custom_field_cache) && count($custom_field_cache) > 0 &&
						isset($custom_field_cache[$custom_field_key]) && isset($custom_field_cache[$custom_field_key]['options']) && 
						is_array($custom_field_cache[$custom_field_key]['options']) && count($custom_field_cache[$custom_field_key]['options']) > 0 ){
						foreach( $custom_field_cache[$custom_field_key]['options'] as $option_obj ){
							$choices[] = array( 'text' => $option_obj->label, 'value' => $option_obj->id );
						}
					}
				}
				$field['choices'] = $choices;
			}else if ( $field->populatePipedriveActivityType ) {
				//organise organisation
				$activity_types_list = get_option( $this->_wpgf2pdcrm_activity_types_cache_option );
				if( $activity_types_list && is_array($activity_types_list) && count($activity_types_list) > 0 ){
					foreach( $activity_types_list as $type_id => $type_data ){
						$choices[] = array( 'text' => $type_data['name'], 'value' => $type_id );
					}
				}
				$field['choices'] = $choices;
			}else if ( $field->populatePipedriveProducts ) {
				//organise products
				$products_list = get_option( $this->_wpgf2pdcrm_products_list_option_name );
				if( $products_list && is_array($products_list) && count($products_list) > 0 ){
					foreach( $products_list as $prouct_id => $product_obj ){
						$choices[] = array( 'text' => $product_obj['name'], 'value' => $prouct_id );
					}
				}
				$field['choices'] = $choices;
			}
            
		}
	 
		return $form;
	}
    
    function wpgf2pdcrm_populate_choices_to_entry( $value, $field, $entry, $form ) {
        $license_key = get_option ('wpgf2pdcrm_license_key', '' );
		$license_key_status = get_option( 'wpgf2pdcrm_license_key_status', '' );
		if( !$license_key || $license_key_status != 'valid' ){
			return $value;
		}

        $return_value = $value;
        if ( $field->populatePipedrivePeople ) {
            
            if( !isset($field->displayPeopleOptonOnEntryView) ||
                $field->displayPeopleOptonOnEntryView != 'OPT_LABEL' ){
            
                return $value;
            }
            
            //organise people
            $people_list = get_option( $this->_wpgf2pdcrm_persons_list_option_name );
            if( $people_list && is_array($people_list) && count($people_list) > 0 ){
                foreach( $people_list as $person_id => $person_data_node ){
                    if( $person_id == $value ){
                        $return_value = $person_data_node['name'];
                        break;
                    }
                }
            }
        }else if ( $field->populatePipedriveOrganisations ) {
            
            if( !isset($field->displayOrganisationOptonOnEntryView) ||
                $field->displayOrganisationOptonOnEntryView != 'OPT_LABEL' ){
            
                return $value;
            }
            
            //organise organisation
            $organisations_list = get_option( $this->_wpgf2pdcrm_organisations_list_option_name );
            if( $organisations_list && is_array($organisations_list) && count($organisations_list) > 0 ){
                foreach( $organisations_list as $org_id => $org_data_node ){
                    $choices[] = array( 'text' => $org_data_node['name'], 'value' => $org_id );
                    if( $org_id == $value ){
                        $return_value = $org_data_node['name'];
                        break;
                    }
                }
            }
        }else if ( $field->populatePipedriveCustomField ) {
            
            if( !isset($field->displayPipedriveCustomFieldOptonOnEntryView) ||
                $field->displayPipedriveCustomFieldOptonOnEntryView != 'OPT_LABEL' ){
            
                return $value;
            }
            
            //organise people
            $custom_field_option_name = '';
            $custom_field_key = '';
            if( strpos( $field->populatePipedriveCustomField, 'DEALBUILTIN_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_deal_fields_options_cache_option_name;
                $custom_field_key = str_replace( 'DEALBUILTIN_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'PEOPLEBUILTIN_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_people_custom_field_option_name;
                $custom_field_key = str_replace( 
                                                 'PEOPLEBUILTIN_', 
                                                 '', 
                                                 $field->_wpgf2pdcrm_people_fields_options_cache_option_name 
                                               );
            }else if( strpos( $field->populatePipedriveCustomField, 'ORGBUILTIN_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_organisation_fields_options_cache_option_name;
                $custom_field_key = str_replace( 'ORGBUILTIN_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'PRDBUILTIN_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_product_fields_options_cache_option_name;
                $custom_field_key = str_replace( 'PRDBUILTIN_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'DEAL_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_deal_custom_field_option_name;
                $custom_field_key = str_replace( 'DEAL_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'PEOPLE_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_people_custom_field_option_name;
                $custom_field_key = str_replace( 'PEOPLE_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'ORG_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_organisation_custom_field_option_name;
                $custom_field_key = str_replace( 'ORG_', '', $field->populatePipedriveCustomField );
            }else if( strpos( $field->populatePipedriveCustomField, 'PRD_' ) !== false ){
                $custom_field_option_name = $this->_wpgf2pdcrm_product_custom_field_option_name;
                $custom_field_key = str_replace( 'PRD_', '', $field->populatePipedriveCustomField );
            }

            if( $custom_field_option_name && $custom_field_key ){
                $custom_field_cache = get_option( $custom_field_option_name );
                if( $custom_field_cache && is_array($custom_field_cache) && count($custom_field_cache) > 0 &&
                    isset($custom_field_cache[$custom_field_key]) && isset($custom_field_cache[$custom_field_key]['options']) && 
                    is_array($custom_field_cache[$custom_field_key]['options']) && count($custom_field_cache[$custom_field_key]['options']) > 0 ){
                    foreach( $custom_field_cache[$custom_field_key]['options'] as $option_obj ){
                        if( $option_obj->id == $value ){
                            $return_value = $option_obj->label;
                            break;
                        }
                    }
                }
            }
        }else if ( $field->populatePipedriveActivityType ) {
            
            if( !isset($field->displayActivityOptonOnEntryView) ||
                $field->displayActivityOptonOnEntryView != 'OPT_LABEL' ){
            
                return $value;
            }
            
            //organise organisation
            $activity_types_list = get_option( $this->_wpgf2pdcrm_activity_types_cache_option );
            if( $activity_types_list && is_array($activity_types_list) && count($activity_types_list) > 0 ){
                foreach( $activity_types_list as $type_id => $type_data ){
                    if( $type_id == $value ){
                        $return_value = $type_data['name'];
                        break;
                    }
                }
            }
        }else if ( $field->populatePipedriveProducts ) {
            
            if( !isset($field->displayProductOptonOnEntryView) ||
                $field->displayProductOptonOnEntryView != 'OPT_LABEL' ){
            
                return $value;
            }
            
            //organise products
            $products_list = get_option( $this->_wpgf2pdcrm_products_list_option_name );
            if( $products_list && is_array($products_list) && count($products_list) > 0 ){
                foreach( $products_list as $prouct_id => $product_obj ){
                    if( $prouct_id == $value ){
                        $return_value = $product_obj['name'];
                        break;
                    }
                }
            }
        }
        
        return $return_value;
    }
}
