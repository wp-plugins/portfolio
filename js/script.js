function update_postmeta() {
	(function($){
		setMessage("<p>"+update_message+"</p>");
		var curr = 0;
		$.ajax({
			url: '../wp-admin/admin-ajax.php?action=prtfl_update_info',//update_url,
			type: "POST",
			data: "action1=get_portfolio_id",
			success: function(result) {
				var list = eval('('+result+')');
				
				if (!list) {
					setError( "<p>"+not_found_info+"</p>" );
					$("#ajax_update_postmeta").removeAttr("disabled");
					return;
				}		
				$('#prtfl_loader').show();

				function updatenItem() {
					if (curr >= list.length) {
						$.ajax({
							url: '../wp-admin/admin-ajax.php?action=prtfl_update_info',
							type: "POST",
							data: "action1=update_options",
							success: function(result) {
							}
						});
						$("#ajax_update_postmeta").removeAttr("disabled");
						setMessage("<p>"+success+"</p>");
						$('#prtfl_loader').hide();
						return;
					}

					$.ajax({
						url: '../wp-admin/admin-ajax.php?action=prtfl_update_info',
						type: "POST",
						data: "action1=update_info&id="+list[curr].ID,
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
	})(jQuery);
}

function update_images() {
	(function($){
		setMessage("<p>"+update_img_message+"</p>");
		var curr = 0;
		$.ajax({
			url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',//update_img_url,
			type: "POST",
			data: "action1=get_all_attachment",
			success: function(result) {
				var list = eval('('+result+')');
				
				if (!list) {
					setError( "<p>"+not_found_img_info+"</p>" );
					$("#ajax_update_images").removeAttr("disabled");
					return;
				}		
				$('#prtfl_img_loader').show();

				function updatenImageItem() {
					if (curr >= list.length) {
						$.ajax({
							url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
							type: "POST",
							data: "action1=update_options",
							success: function(result) {
							}
						});
						$("#ajax_update_images").removeAttr("disabled");
						setMessage("<p>"+img_success+"</p>");
						$('#prtfl_img_loader').hide();
						return;
					}

					$.ajax({
						url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
						type: "POST",
						data: "action1=update_image&id="+list[curr].ID,
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
	})(jQuery);
}

function setMessage(msg) {
	(function($){
		$(".error").hide();
		$(".updated").html(msg);
		$(".updated").show();
	})(jQuery);
}

function setError(msg) {
	(function($){
		$(".updated").hide();
		$(".error").html(msg);
		$(".error").show();
	})(jQuery);
}
