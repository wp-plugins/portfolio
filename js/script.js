jQuery(document).ready(function()
{
	jQuery('#addimageinfo').click(function()
	{
		var str = '<div class="portfolio_admin_subbox">'+
			'<p><label for="'+profile_images_key+'_'+profile_images_count+'"><strong>'+profile_images_label+'</strong></label></p>'+
			'<p><input type="file" id="'+profile_images_key+'_'+profile_images_count+'" name="'+profile_images_key+'_'+profile_images_count+'"></p>'+
			'<p><em></em></p>'+
			'<p><label for="'+profile_images_key+'_title_'+profile_images_count+'"><strong>'+profile_images_title_label+'</strong></label></p>'+
			'<p><input style="width: 80%;" type="text" name="'+profile_images_key+'_title_'+profile_images_count+'" id="'+profile_images_key+'_title_'+profile_images_count+'" value="" /></p>'+
			'<p><em>'+profile_images_title_text+'</em></p>'+
			'<p><label for="'+profile_images_key+'_description_'+profile_images_count+'"><strong>'+profile_images_descr_label+'</strong></label></p>'+
			'<p><input style="width: 80%;" type="text" name="'+profile_images_key+'_description_'+profile_images_count+'" id="'+profile_images_key+'_description_'+profile_images_count+'" value="" /></p>'+
			'<p><em>'+profile_images_descr_text+'</em></p>'+
			'</div>';
		jQuery('#addimageinfo').before(str);
		//profile_images_count++;
	});	
});

function update_postmeta() {
	//jQuery("#ajax_update_postmeta").attr("disabled", true);
	setMessage("<p>"+update_message+"</p>");
	var curr = 0;
	jQuery.ajax({
		url: update_url,
		type: "POST",
		data: "action=get_portfolio_id",
		success: function(result) {
			var list = eval(result);
			
			if (!list) {
				setError( "<p>"+not_found_info+"</p>" );
				jQuery("#ajax_update_postmeta").removeAttr("disabled");
				return;
			}		
			jQuery('#prtfl_loader').show();

			function updatenItem() {
				if (curr >= list.length) {
					jQuery.ajax({
						url: update_url,
						type: "POST",
						data: "action=update_options",
						success: function(result) {
						}
					});
					jQuery("#ajax_update_postmeta").removeAttr("disabled");
					setMessage("<p>"+success+"</p>");
					jQuery('#prtfl_loader').hide();
					return;
				}

				jQuery.ajax({
					url: update_url,
					type: "POST",
					data: "action=update_info&id="+list[curr].ID,
					success: function(result) {
						curr = curr + 1;
						updatenItem();
					}
				});
			}

			updatenItem();
		},
		error: function( request, status, error ) {
			setError( "<p>"+error + request.status+"</p>" );
		}
	});
}

function update_images() {
	//jQuery("#ajax_update_images").attr("disabled", true);
	setMessage("<p>"+update_img_message+"</p>");
	var curr = 0;
	jQuery.ajax({
		url: update_img_url,
		type: "POST",
		data: "action=get_all_attachment",
		success: function(result) {
			var list = eval(result);
			
			if (!list) {
				setError( "<p>"+not_found_img_info+"</p>" );
				jQuery("#ajax_update_images").removeAttr("disabled");
				return;
			}		
			jQuery('#prtfl_img_loader').show();

			function updatenImageItem() {
				if (curr >= list.length) {
					jQuery.ajax({
						url: update_img_url,
						type: "POST",
						data: "action=update_options",
						success: function(result) {
						}
					});
					jQuery("#ajax_update_images").removeAttr("disabled");
					setMessage("<p>"+img_success+"</p>");
					jQuery('#prtfl_img_loader').hide();
					return;
				}

				jQuery.ajax({
					url: update_img_url,
					type: "POST",
					data: "action=update_image&id="+list[curr].ID,
					success: function(result) {
						curr = curr + 1;
						updatenImageItem();
					}
				});
			}

			updatenImageItem();
		},
		error: function( request, status, error ) {
			setError( "<p>"+img_error + request.status+"</p>" );
		}
	});
}

function setMessage(msg) {
	jQuery(".error").hide();
	jQuery(".updated").html(msg);
	jQuery(".updated").show();
}

function setError(msg) {
	jQuery(".updated").hide();
	jQuery(".error").html(msg);
	jQuery(".error").show();
}