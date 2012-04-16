(function($){
	$(document).ready(function()
	{
		$('#addimageinfo').click(function()
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
			$('#addimageinfo').before(str);
			//profile_images_count++;
		});	
	});

	function update_postmeta() {
		//$("#ajax_update_postmeta").attr("disabled", true);
		setMessage("<p>"+update_message+"</p>");
		var curr = 0;
		$.ajax({
			url: update_url,
			type: "POST",
			data: "action=get_portfolio_id",
			success: function(result) {
				var list = eval(result);
				
				if (!list) {
					setError( "<p>"+not_found_info+"</p>" );
					$("#ajax_update_postmeta").removeAttr("disabled");
					return;
				}		
				$('#prtfl_loader').show();

				function updatenItem() {
					if (curr >= list.length) {
						$.ajax({
							url: update_url,
							type: "POST",
							data: "action=update_options",
							success: function(result) {
							}
						});
						$("#ajax_update_postmeta").removeAttr("disabled");
						setMessage("<p>"+success+"</p>");
						$('#prtfl_loader').hide();
						return;
					}

					$.ajax({
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
		//$("#ajax_update_images").attr("disabled", true);
		setMessage("<p>"+update_img_message+"</p>");
		var curr = 0;
		$.ajax({
			url: update_img_url,
			type: "POST",
			data: "action=get_all_attachment",
			success: function(result) {
				var list = eval(result);
				
				if (!list) {
					setError( "<p>"+not_found_img_info+"</p>" );
					$("#ajax_update_images").removeAttr("disabled");
					return;
				}		
				$('#prtfl_img_loader').show();

				function updatenImageItem() {
					if (curr >= list.length) {
						$.ajax({
							url: update_img_url,
							type: "POST",
							data: "action=update_options",
							success: function(result) {
							}
						});
						$("#ajax_update_images").removeAttr("disabled");
						setMessage("<p>"+img_success+"</p>");
						$('#prtfl_img_loader').hide();
						return;
					}

					$.ajax({
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
		$(".error").hide();
		$(".updated").html(msg);
		$(".updated").show();
	}

	function setError(msg) {
		$(".updated").hide();
		$(".error").html(msg);
		$(".error").show();
	}
})(jQuery);