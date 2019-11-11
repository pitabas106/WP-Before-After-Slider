jQuery( function() {


	jQuery('body').on('click', '.wpbas-upload-img', function(e){
		e.preventDefault();
 
    		var button = jQuery(this),
		    custom_uploader = wp.media({
			// title: 'Insert image',
			library : {
				// uncomment the next line if you want to attach image to the current post
				// uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				// text: 'Use this image' // button label text
			},
			multiple: false // for multiple image selection set to true
		}).on('select', function() { // it also has "open" and "close" events 
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			button.prev('.wpbas-upload-img-id').val(attachment.id);
			button.next('.wpbas-upload-img-preview').html('<img class="wpbas-thumb-prev" src="' + attachment.url + '" />')
		}).open();

	});
 

	jQuery('#wpbas-admin-notice .notice-dismiss').on( 'click', function() {
    	jQuery(this).parent('#wpbas-admin-notice').fadeOut();
    } );


	jQuery("#wpbas-slider-name").on('input', function(key) {
	  	var value = jQuery(this).val();
	  	jQuery(this).val(value.replace(/ /g, '_'));
	});


	/* Create Slider */
	jQuery('.wpbas-add-slider').on('click', function() {

		// jQuery("#new-wpbaslider-form").addClass('wpbas-loading');
		var $_self = jQuery(this);
		$_self.parents('#new-wpbaslider-form:first').addClass('wpbas-loading');
		$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').hide();
		$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice .notice-container').html('');

		var wpbas_slider_name = $_self.parents('tr:first').find( "#wpbas-slider-name" ).val();
		var wpbas_slider_name = ( wpbas_slider_name ) ? wpbas_slider_name : '';
		
		if(!wpbas_slider_name) {
			alert( WPBAS.slider_name_required );
			return;
		}
		var json_data = {
			action		: 'wpbas_create_slider_ajax',
	      	slider_name : wpbas_slider_name,
			security 	: WPBAS.ajax_nonce
		};

	    jQuery.ajax({
			type 		: "POST",
			url 		: WPBAS.ajaxurl,
			data 		: json_data,
			dataType 	: 'json',
			success 	: function( result ) {

	      		jQuery("#new-wpbaslider-form").addClass('wpbas-loading');
	      		$_self.parents('#new-wpbaslider-form:first').addClass('wpbas-loading');
	      		$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').show();
			    if( result.status == 'success' ) {
			    	$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').removeClass('wpbas-notice-error');
			    	$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').removeClass('wpbas-error');
			    	$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').addClass('wpbas-notice-success');
			    	
			    	setTimeout( function() {
			    		window.location.href = WPBAS.wpbaslider_edit_url+result.slider_name;
		    		}, 500);
			    	
			    } else {
			    	$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').removeClass('wpbas-notice-success');
			    	$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice').addClass('wpbas-notice-error');
			    }

		    	setTimeout( function() {
		    		$_self.parents('#new-wpbaslider-form:first').find('#wpbas-admin-notice .notice-container').html( result.msg );
		    	}, 1000);

		    	//Get all sliders
		    	wpbas_app.getAllSliders();
			}
	    });

	});
});

var wpbas_app = {
	getAllSliders: function() {

		var json_data = {
			action			: 'wpbas_get_all_sliders',
			security 		: WPBAS.ajax_nonce,
			get_sliders 	: 'yes'
		};

	    jQuery.ajax({
			type 			: "POST",
			url 			: WPBAS.ajaxurl,
			data 			: json_data,
			dataType 		: 'json',
			success 		: function( result ) {

			    if( result.status == 'success' ) {
			    	jQuery('#wpbas-sliders-body').html('');
			    	jQuery('#wpbas-sliders-body').html(result.data);
			    } else {
			    	jQuery('#wpbas-sliders-body').html('');
			    	jQuery('#wpbas-sliders-body').html(result.data);

			    }

			}
	    });

	},

	/* Add Slide */
	AddSlide: function() {

		var slider_data = jQuery("#wpbas_upload_slide").serialize();
		var form_data 	= "action=wpbas_add_slide&" + slider_data;

		if(jQuery('#wpbas_upload_slide .wpbas-msg').length > 0) 
			jQuery('#wpbas_upload_slide .wpbas-msg').remove();

		//loading image
		if(jQuery('#wpbas-upload-loading-img').length > 0) 
			jQuery('#wpbas-upload-loading-img').remove();

		jQuery("#upload_slide_btn").before('<span id="wpbas-upload-loading-img"></span>');

		jQuery.ajax({
			type 			: "POST",
			url 			: WPBAS.ajaxurl,
			data 			: form_data,
			dataType 		: 'json',
			success 		: function( result ) {

			    if( result.status == 'success' ) {
			    	setTimeout( function() {
			    		jQuery('#wpbas_upload_slide').prepend('<p class="wpbas-success wpbas-msg">'+result.msg+'</p>');
			    	}, 1000);

			    	//Reset form after successfully submitted.
					wpbas_app.slideAddEditFormReset();

			    	//Fetch all the slides
			    	wpbas_app.getAllSlides(result.slider_name, 'all');
			    } else {
			    	setTimeout( function() {
			    		jQuery('#wpbas_upload_slide').prepend('<p class="wpbas-error wpbas-msg">'+result.msg+'</p>');
			    	}, 1000);
			    }

			    jQuery("#wpbas-upload-loading-img").remove();

			},
			error 			: function( result ) {
				jQuery("#wpbas-upload-loading-img").remove();
			}
	    });

	},



	/**
	 * Fetch all the slides of a particular slider
	 *
	 */
	getAllSlides: function(slider_name, type) {
		var slide_type = (type) ? type : 'all';
		var wpbas_slider_name = (slider_name) ? slider_name : '';
		jQuery('#wpbas-all-slides').addClass('wpbas-loading');

		var json_data = {
			action			: 'wpbas_get_all_slides',
			security 		: WPBAS.ajax_nonce,
			slider_type 	: slide_type,
			slider_name 	: slider_name
		};

		jQuery.ajax({
			type 			: "POST",
			url 			: WPBAS.ajaxurl,
			data 			: json_data,
			dataType 		: 'json',
			success 		: function( result ) {
				jQuery('#wpbas-all-slides').removeClass('wpbas-loading');
			    if( result.status == 'success' ) {
			    	if( result.data ) {
			    		jQuery('#wpbas-all-slides').html(result.data);
			    	}
			    } else {
			    	jQuery('#wpbas-all-slides').prepend(result.msg);
			    }
			},
			error 			: function( result ) {
				jQuery('#wpbas-all-slides').removeClass('wpbas-loading');
			}
	    });

	},

	/* Form Reset */
	slideAddEditFormReset: function(){
		jQuery("#wpbas_upload_slide").trigger('reset');
    	jQuery('.wpbas-upload-img-preview').html('');
    	jQuery('.wpbas-upload-img-id').val('');
		jQuery('#wpbas_upload_slide').addClass('wpbas-loading');
		jQuery("#wpbas_upload_slide input[name='slide_update']").val('');
		jQuery("#wpbas_upload_slide input[name='slide_id']").val('');
	},

	/* Get slide data by Slide ID */
	getSingleSlidesByID: function( slide_id ) {
			
		var slide_id 		= ( slide_id ) ? slide_id : 0;
		var slide_json_data = jQuery('#wpbas_item_'+slide_id).find('.wpbas-slide-json-data').val();

		var slide_obj = jQuery.parseJSON(slide_json_data);

		if( !slide_obj ) {
			alert(WPBAS.reload_page_msg);
			return;
		}
		alert(slide_id);

		jQuery('li#wpbas_item_'+slide_id).addClass('wpbas-loading');

    	//Reset Form
		wpbas_app.slideAddEditFormReset();

		/* Before Items */
		jQuery("#wpbas_upload_slide input[name='title']").val(slide_obj.title);
		jQuery("#wpbas_upload_slide input[name='before_thumb']").val(slide_obj.before_thumb_id);
		jQuery("#wpbas_upload_slide input[name='caption_before']").val(slide_obj.caption_before);
		jQuery("#wpbas_upload_slide .wpbas-upload-img-preview.before-preview").html('<img class="wpbas-thumb-prev" src="'+slide_obj.slide_before_img+'">');

		/* After Items */
		jQuery("#wpbas_upload_slide input[name='after_thumb']").val(slide_obj.after_thumb_id);
		jQuery("#wpbas_upload_slide input[name='caption_after']").val(slide_obj.caption_after);
		jQuery("#wpbas_upload_slide .wpbas-upload-img-preview.after-preview").html('<img class="wpbas-thumb-prev" src="'+slide_obj.slide_after_img+'">');

		jQuery("#wpbas_upload_slide input[name='slide_update']").val(1);
		jQuery("#wpbas_upload_slide input[name='slide_id']").val(slide_id);

		jQuery('li#wpbas_item_'+slide_id).removeClass('wpbas-loading');

	},

	/* Delete slide by slide ID */
	deleteSlidesByID: function( slide_id ) {
		var slide_id 		= ( slide_id ) ? slide_id : 0;

		var slider_name = jQuery( "#wpbas_slider_name" ).val();
		var slider_name = ( slider_name ) ? slider_name : '';


		if( !slider_name ) {
			alert(WPBAS.reload_page_msg);
			return;
		}

		jQuery('#wpbas-all-slides li#wpbas_item_'+slide_id).addClass('wpbas-loading');

		var json_data = {
			action			: 'wpbas_delete_slide_by_id',
			security 		: WPBAS.ajax_nonce,
			slide_id 		: slide_id,
			slider_name 	: slider_name
		};

		jQuery.ajax({
			type 			: "POST",
			url 			: WPBAS.ajaxurl,
			data 			: json_data,
			dataType 		: 'json',
			success 		: function( result ) {
				jQuery('#wpbas-all-slides li#wpbas_item_'+slide_id).removeClass('wpbas-loading');
			    if( result.status == 'success' ) {
			    	jQuery('#wpbas-all-slides').prepend(result.msg);
			    	
			    	//Fetch all the slides
			    	wpbas_app.getAllSlides(slider_name, 'all');
			    }
			},
			error 			: function( result ) {
				jQuery('#wpbas-all-slides li#wpbas_item_'+slide_id).removeClass('wpbas-loading');
			}
	    });

	},

	/* Delete slide by slide ID */
	deleteSlidersByName: function( slider_name ) {
		var slider_name = ( slider_name ) ? slider_name : '';

		if( !slider_name ) {
			alert(WPBAS.reload_page_msg);
			return;
		}

		jQuery('#wpbas-sliders-body').addClass('wpbas-loading');

		var json_data = {
			action			: 'wpbas_delete_slider_by_name',
			security 		: WPBAS.ajax_nonce,
			slider_name 	: slider_name
		};

		jQuery.ajax({
			type 			: "POST",
			url 			: WPBAS.ajaxurl,
			data 			: json_data,
			dataType 		: 'json',
			success 		: function( result ) {
				jQuery('#wpbas-sliders-body').removeClass('wpbas-loading');
			    if( result.status == 'success' ) {
			    	jQuery('.wpbas-page-wrap').prepend(result.msg);
			    	
			    	//Get all sliders
		    		wpbas_app.getAllSliders();
			    }
			},
			error 			: function( result ) {
				jQuery('#wpbas-sliders-body').removeClass('wpbas-loading');
			}
	    });

	},

	/* Update Slider settings */
	updateSliderSetting: function() {

	}
	

}

