jQuery(document).ready( function($) {
	
	$("#wpgf2pdcrm_test_connection_button_id").click(function(){
		
		$("#wpgf2pdcrm_test_connection_ajax_loader_id").css( "display", "inline-block" );
		var data = {
			action: 'wpgf2pdcrm_test_connection'
		};
		$.post( ajaxurl, data, function( response ){
			$("#wpgf2pdcrm_test_connection_ajax_loader_id").css( "display", "none" );
			if( response.indexOf('ERROR') != -1 ){
				alert(response);
			}else{
				alert('A deal has been created into your Pipe Drive CRM');
			}
		});
	});
	
	$("#wpgf2pdcrm_refresh_pipedrive_data_cache_button_id").click(function(){
		
		$("#wpgf2pdcrm_refresh_pipedrive_data_cache_ajax_loader_id").css( "display", "inline-block" );
		var data = {
			action: 'wpgf2pdcrm_refresh_pipedrive_data_cache'
		};
		$.post( ajaxurl, data, function( response ){
			$("#wpgf2pdcrm_refresh_pipedrive_data_cache_ajax_loader_id").css( "display", "none" );
			if( response.indexOf('ERROR') != -1 ){
				alert(response);
			}else{
				alert('Now you have the latest data (Pipeline, Stage, Fields) from your Pipedrive account. Enjoy!');
			}
		});
	});
	
	//for dela title multiple fields
	
	$(".wpgf2pdcrm-deal-title-selector").change(function(){
	
		var deal_title_selector_val = $(this).val();
		var deal_title_selector_text = $(this).children(":selected").text()
		var deal_title_selector_id = $(this).attr("id");
		var deal_title_hidden_text_id = 'wpgf2pdcrm_deal_title_val_' + deal_title_selector_id;
		
		if( deal_title_selector_val == "" ){
			return false;
		}
		
		var deal_title_hidden_val = $("#" + deal_title_hidden_text_id).val();
		var deal_title_hidden_val_array = new Array();
		if( deal_title_hidden_val != "" ){
			deal_title_hidden_val_array = deal_title_hidden_val.split( ',' );
		}
		//check if the selected val exist or not
		if( deal_title_hidden_val_array.length > 0 ){
			for( i = 0; i < deal_title_hidden_val_array.length; i++ ){
				if( deal_title_hidden_val_array[i] == deal_title_selector_val ){
					return false;
				}
			}
		}
		deal_title_hidden_val_array.push( deal_title_selector_val );
		deal_title_hidden_val_appended = deal_title_hidden_val_array.join();

		$("#" + deal_title_hidden_text_id).val( deal_title_hidden_val_appended );
		
		var value_span = '<span style="display:inline-block; margin-right:10px;"><a style="cursor: pointer;" class="wpgf2pd-deal-title-values-list-del-icon" rel="' + deal_title_selector_val + '" relselectorid="' + deal_title_selector_id + '" style="margin-left:0;">X</a>&nbsp;' + deal_title_selector_text + '</span>';
		$(".wpgf2pd-deal-title-values-list-container").append( value_span );
	});
	
    $( ".wpgf2pdcrm-settings-filed-map" ).on( "click", ".wpgf2pd-deal-title-values-list-del-icon", function(){
		if( $(this).hasClass( "deal-title-del-disabled" ) ){
			return false;
		}
		var deal_title_val_to_remove = $(this).attr("rel");
		var deal_title_selector_id = $(this).attr("relselectorid");
		var deal_title_del_icon = $(this);
		
		if( deal_title_val_to_remove == "" ){
			$(this).parent().remove();
		}
		
		if( deal_title_val_to_remove.indexOf( 'custom_text_' ) != -1 ){
			$(".wpgf2pd-deal-title-values-list-del-icon").addClass( "deal-title-del-disabled" );
			$(this).parent().css( 'color', '#CCC' );
			
			var data = {
				action: 'wpgf2pdcrm_deal_title_delete_custom_text',
				key: deal_title_val_to_remove
			};
			
			$.post( ajaxurl, data, function( response ){
				
				if( response.indexOf('ERROR') != -1 ){
					alert( response );
				}else{
					var deal_title_hidden_text_id = 'wpgf2pdcrm_deal_title_val_' + deal_title_selector_id;
					var deal_title_hidden_val = $("#" + deal_title_hidden_text_id).val();
					deal_title_hidden_val_array = deal_title_hidden_val.split( ',' );
					for( i = 0; i < deal_title_hidden_val_array.length; i++ ){
						if( deal_title_hidden_val_array[i] == deal_title_val_to_remove ){
							deal_title_hidden_val_array.splice( i, 1 );
						}
					}
					deal_title_hidden_val_appended = deal_title_hidden_val_array.join();
			
					$("#" + deal_title_hidden_text_id).val( deal_title_hidden_val_appended );
					deal_title_del_icon.parent().remove();
				}
				
				$(".wpgf2pd-deal-title-values-list-del-icon").removeClass( "deal-title-del-disabled" );
			});
		}else{
			var deal_title_hidden_text_id = 'wpgf2pdcrm_deal_title_val_' + deal_title_selector_id;
			var deal_title_hidden_val = $("#" + deal_title_hidden_text_id).val();
			deal_title_hidden_val_array = deal_title_hidden_val.split( ',' );
			for( i = 0; i < deal_title_hidden_val_array.length; i++ ){
				if( deal_title_hidden_val_array[i] == deal_title_val_to_remove ){
					deal_title_hidden_val_array.splice( i, 1 );
				}
			}
			deal_title_hidden_val_appended = deal_title_hidden_val_array.join();
	
			$("#" + deal_title_hidden_text_id).val( deal_title_hidden_val_appended );
			$(this).parent().remove();
		}
	});
	
	if( $(".wpgf2pd-deal-title-values-add-custom-text-input").length > 0 ){
		var deal_title_selector_id = $(".wpgf2pd-deal-title-values-add-custom-text-input").attr( "rel" );
		$(".wpgf2pd-deal-title-values-add-custom-text-input").css( "width", $("#" + deal_title_selector_id).width() );
	}
	
	$(".wpgf2pd-deal-title-values-add-custom-text-button").click(function(){
		var deal_title_selector_id = $(this).attr( "rel" );
		var custom_text = $("#wpgf2pdcrm_deal_title_custom_text_" + deal_title_selector_id).val();
		if( $.trim( custom_text ) == "" ){
			return false;
		}
		var ajax_loader_id = 'wpgf2pdcrm_deal_title_add_custom_text_ajax_loader_id_' + deal_title_selector_id;
		
		var data = {
			action: 'wpgf2pdcrm_deal_title_add_custom_text',
			text: custom_text
		};
		
		$("#" + ajax_loader_id).css( "display", "inline-block" );
		$.post( ajaxurl, data, function( response ){
			
			$("#" + ajax_loader_id).css( "display", "none" );
			
			if( response.indexOf('ERROR') != -1 ){
				alert( response );
			}else{
				//
				var deal_title_hidden_text_id = 'wpgf2pdcrm_deal_title_val_' + deal_title_selector_id;

				var deal_title_hidden_val = $("#" + deal_title_hidden_text_id).val();
				var deal_title_hidden_val_array = new Array();
				if( deal_title_hidden_val != "" ){
					deal_title_hidden_val_array = deal_title_hidden_val.split( ',' );
				}
				
				deal_title_hidden_val_array.push( response );
				deal_title_hidden_val_appended = deal_title_hidden_val_array.join();
		
				$("#" + deal_title_hidden_text_id).val( deal_title_hidden_val_appended );
				
				var value_span = '<span><a class="ntdelbutton wpgf2pd-deal-title-values-list-del-icon" rel="' + response + '" relselectorid="' + deal_title_selector_id + '">X</a>&nbsp;' + custom_text + '</span>';
				$(".wpgf2pd-deal-title-values-list-container").append( value_span );
				
				$("#wpgf2pdcrm_deal_title_custom_text_" + deal_title_selector_id).val( "" );
			}
		});
	});
	
	//for notes multiple fields
	$(".wpgf2pdcrm-deal-notes-selector").change(function(){
		var deal_notes_selector_val = $(this).val();
		var deal_notes_selector_text = $(this).children(":selected").text()
		var deal_notes_selector_id = $(this).attr("id");
		var deal_notes_hidden_text_id = 'wpgf2pdcrm_deal_notes_val_' + deal_notes_selector_id;
		
		if( deal_notes_selector_val == "" ){
			alert( 'bbbb' );
			return false;
		}
		
		var deal_notes_hidden_val = $("#" + deal_notes_hidden_text_id).val();
		var deal_notes_hidden_val_array = new Array();
		if( deal_notes_hidden_val != "" ){
			deal_notes_hidden_val_array = deal_notes_hidden_val.split( ',' );
		}
		//check if the selected val exist or not
		if( deal_notes_hidden_val_array.length > 0 ){
			for( i = 0; i < deal_notes_hidden_val_array.length; i++ ){
				if( deal_notes_hidden_val_array[i] == deal_notes_selector_val ){
					alert( 'cccc' );
					return false;
				}
			}
		}
		deal_notes_hidden_val_array.push( deal_notes_selector_val );
		deal_notes_hidden_val_appended = deal_notes_hidden_val_array.join();

		$("#" + deal_notes_hidden_text_id).val( deal_notes_hidden_val_appended );
		
		var value_span = '<span><a style="cursor: pointer;" class="ntdelbutton wpgf2pd-deal-notes-values-list-del-icon" rel="' + deal_notes_selector_val + '" relselectorid="' + deal_notes_selector_id + '">X</a>&nbsp;' + deal_notes_selector_text + '</span>';
		$(".wpgf2pd-deal-notes-values-list-container").append( value_span );
	});
	
    $( ".wpgf2pdcrm-settings-filed-map" ).on( "click", ".wpgf2pd-deal-notes-values-list-del-icon", function(){
		if( $(this).hasClass( "deal-notes-del-disabled" ) ){
			return false;
		}
		var deal_notes_val_to_remove = $(this).attr("rel");
		var deal_notes_selector_id = $(this).attr("relselectorid");
		var deal_notes_del_icon = $(this);
		
		if( deal_notes_val_to_remove == "" ){
			$(this).parent().remove();
		}
		
		var deal_notes_hidden_text_id = 'wpgf2pdcrm_deal_notes_val_' + deal_notes_selector_id;
		var deal_notes_hidden_val = $("#" + deal_notes_hidden_text_id).val();
		deal_notes_hidden_val_array = deal_notes_hidden_val.split( ',' );
		for( i = 0; i < deal_notes_hidden_val_array.length; i++ ){
			if( deal_notes_hidden_val_array[i] == deal_notes_val_to_remove ){
				deal_notes_hidden_val_array.splice( i, 1 );
			}
		}
		deal_notes_hidden_val_appended = deal_notes_hidden_val_array.join();

		$("#" + deal_notes_hidden_text_id).val( deal_notes_hidden_val_appended );
		$(this).parent().remove();
	});
	
	// for person to associate
	$(".wpgf2pd-deal-person-select-form-field").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_form_field_id = $(this).val();
        
        //enable create new person fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-person-fields").find("select").removeAttr( "disabled" );
		if( selected_form_field_id != "" ){
			$("#wpgf2pd_deal_person_select_exist_person_id_" + rel_data ).val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-person-fields").find("select").attr( "disabled", true );
		}
		$("#wpgf2pdcrm_deal_person_val_" + rel_data ).val( selected_form_field_id + '#' + 'GF' );
	});
	
	$(".wpgf2pd-deal-person-select-exist-person").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_pipedrive_person_id = $(this).val();
        
        //enable create new person fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-person-fields").find("select").removeAttr( "disabled" );
		if( selected_pipedrive_person_id != "" ){
			$("#wpgf2pd_deal_person_select_form_field_id_" + rel_data ).val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-person-fields").find("select").attr( "disabled", true );
		}
		$("#wpgf2pdcrm_deal_person_val_" + rel_data ).val( selected_pipedrive_person_id + '#' + 'PD' );
		
		if( selected_pipedrive_person_id.indexOf( '-' ) != -1 ){
			var person_id_array = selected_pipedrive_person_id.split( '-' );
			var org_id = '';
			if( person_id_array.length > 1 ){
				org_id = person_id_array[1];
			}
			if( org_id ){
				$(".wpgf2pd-deal-org-select-form-field" ).val( "" );
				$(".wpgf2pd-deal-org-select-exist-org" ).val( org_id );
				$(".wpgf2pd-deal-org-select-exist-org" ).each(function(index, element) {
                    var rel_data = $(this).attr( "rel" );
					$("#wpgf2pdcrm_deal_org_val_" + rel_data ).val( org_id + '#' + 'PD' );
                });
			}
		}
	});
    
    $("#pipedrive_map_name, #lead_field_map_name").change(function(){
        var selected_field_id_to_create_person = $(this).val();
        //enable associate person fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("select").removeAttr( "disabled" );
        if( selected_field_id_to_create_person != "" ){
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("select").attr( "disabled", true );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("select").val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("input").val( "" );
        }
    });
    
    if( $("#gform-settings").find("#pipedrive_map_name").val() || $("#gform-settings").find("#lead_field_map_name").val() ){
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("select").attr( "disabled", true );
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("select").val( "" );
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-person").find("input").val( "" );
    }else if( $("#gform-settings").find(".deal-assoicate-exist-person").find("input").val() ){
        $("#gform-settings").find(".deal-create-person-fields").find("select").attr( "disabled", true );
        $("#gform-settings").find(".deal-create-person-fields").find("select").val( "" );
    }
	
	/*
      * for organisation to associate
      */
	$(".wpgf2pd-deal-org-select-form-field").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_form_field_id = $(this).val();
        
        //enable create new organisaton fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").removeAttr( "disabled" );
		if( selected_form_field_id != "" ){
			$("#wpgf2pd_deal_org_select_exist_org_id_" + rel_data ).val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").attr( "disabled", true );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").val( "" );
		}
		$("#wpgf2pdcrm_deal_org_val_" + rel_data ).val( selected_form_field_id + '#' + 'GF' );
	});
	
	$(".wpgf2pd-deal-org-select-exist-org").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_pipedrive_org_id = $(this).val();
        //enable create new organisaton fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").removeAttr( "disabled" );
		if( selected_pipedrive_org_id != "" ){
			$("#wpgf2pd_deal_org_select_form_field_id_" + rel_data ).val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").attr( "disabled", true );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-create-organisations-fields").find("select").val( "" );
		}
		$("#wpgf2pdcrm_deal_org_val_" + rel_data ).val( selected_pipedrive_org_id + '#' + 'PD' );
	});
    
    $("#pipedrive_map_new_org, #lead_field_map_new_org").change(function(){
        var selected_field_id_to_create_org = $(this).val();
        //enable associate organisaton fields
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("select").removeAttr( "disabled" );
        if( selected_field_id_to_create_org != "" ){
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("select").attr( "disabled", true );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("select").val( "" );
            $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("input").val( "" );
        }
    });
    
    if( $("#gform-settings").find("#pipedrive_map_new_org").val() || $("#gform-settings").find("#lead_field_map_new_org").val() ){
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("select").attr( "disabled", true );
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("select").val( "" );
        $(this).parents('.wpgf2pdcrm-settings-filed-map').find(".deal-assoicate-exist-organisations").find("input").val( "" );
    }else if( $("#gform-settings").find(".deal-assoicate-exist-organisations").find("input").val() ){
        $("#gform-settings").find(".deal-create-organisations-fields").find("select").attr( "disabled", true );
        $("#gform-settings").find(".deal-create-organisations-fields").find("select").val( "" );
    }
	
	/*
      *for activity type
      */
	$(".wpgf2pd-activity-type-select-form-field").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_form_field_id = $(this).val();
		if( selected_form_field_id != "" ){
			$("#wpgf2pd_activity_type_select_exist_type_id_" + rel_data ).val( "" );
            $("#wpgf2pd_activity_type_select_exist_type_id_" + rel_data ).attr( "disabled", true );
		}else{
            $("#wpgf2pd_activity_type_select_exist_type_id_" + rel_data ).removeAttr( "disabled" );
        }
        
        $("#wpgf2pdcrm_activity_type_val_" + rel_data ).val( selected_form_field_id + '#' + 'GF' );
	});
	
	$(".wpgf2pd-activity-type-select-exist-type").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_pipedrive_activity_type_id = $(this).val();
		if( selected_pipedrive_activity_type_id != "" ){
			$("#wpgf2pd_activity_type_select_form_field_id_" + rel_data ).val( "" );
            $("#wpgf2pd_activity_type_select_form_field_id_" + rel_data ).attr( "disabled", true );
		}else{
            $("#wpgf2pd_activity_type_select_form_field_id_" + rel_data ).removeAttr( "disabled" );
        }
		$("#wpgf2pdcrm_activity_type_val_" + rel_data ).val( selected_pipedrive_activity_type_id + '#' + 'PD' );
	});
    
    /* 
      * for product to attact
      */
	$(".wpgf2pd-deal-product-select-form-field").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_form_field_id = $(this).val();
		if( selected_form_field_id != "" ){
			$("#wpgf2pd_deal_product_select_exist_product_id_" + rel_data ).val( "" );
		}
		$("#wpgf2pdcrm_deal_product_val_" + rel_data ).val( selected_form_field_id + '#' + 'GF' );
	});
	
	$(".wpgf2pd-deal-product-select-exist-product").change(function(){
		var rel_data = $(this).attr( "rel" );
		var selected_pipedrive_product_id = $(this).val();
		if( selected_pipedrive_product_id != "" ){
			$("#wpgf2pd_deal_product_select_form_field_id_" + rel_data ).val( "" );
		}
		$("#wpgf2pdcrm_deal_product_val_" + rel_data ).val( selected_pipedrive_product_id + '#' + 'PD' );
	});
    
    /*
     * for plugin option
     */
    $("#wpgf2pdcrm_uninstall_plugin_data_check_ID").click( function(){
        var option_value = $("#wpgf2pdcrm_uninstall_plugin_data_check_ID").is( ":checked" ) ? 'YES' : 'NO';
        var nonce_val = $("#wpgf2pdcrm_save_plugin_options_nonce_ID").val();
		var data = {
			action: 'wpgf2pdcrm_action_save_plugin_options',
			option: option_value,
            nonce: nonce_val
		};
		
		$("#wpgf2pdcrm_uninstall_plugin_data_ajax_loader_ID").css( "display", "inline-block" );
		$.post( ajaxurl, data, function( response ){
            $("#wpgf2pdcrm_uninstall_plugin_data_ajax_loader_ID").css( "display", "none" );
            if( response.indexOf( 'ERROR' ) != -1 ){
                alert( response );
            }
        });
    });
});

