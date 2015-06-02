function update_images() {
	(function($){
		setMessage( "<p>" + prtfl_var.update_img_message + "</p>" );
		var curr = 0;
		$.ajax({
			url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
			type: "POST",
			data: "action1=get_all_attachment" + '&prtfl_ajax_nonce_field=' + prtfl_var.prtfl_nonce,
			success: function( result ) {
				var list = eval( '(' + result + ')' );				
				if ( ! list ) {
					setError( "<p>" + prtfl_var.not_found_img_info + "</p>" );
					$( "#ajax_update_images" ).removeAttr( "disabled" );
					return;
				}		
				$( '#prtfl_img_loader' ).show();

				function updatenImageItem() {
					if ( curr >= list.length ) {
						$.ajax({
							url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
							type: "POST",
							data: "action1=update_options" + '&prtfl_ajax_nonce_field=' + prtfl_var.prtfl_nonce,
							success: function( result ) {
								/**/
							}
						});
						$( "#ajax_update_images" ).removeAttr( "disabled" );
						setMessage("<p>" + prtfl_var.img_success + "</p>");
						$( '#prtfl_img_loader' ).hide();
						return;
					}
					$.ajax({
						url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
						type: "POST",
						data: "action1=update_image&id=" + list[ curr ].ID + '&prtfl_ajax_nonce_field=' + prtfl_var.prtfl_nonce,
						success: function( result ) {
							curr = curr + 1;
							updatenImageItem();
						}
					});
				}

				updatenImageItem();
			},
			error: function( request, status, error ) {
				setError( "<p>" + prtfl_var.img_error + request.status + "</p>" );
			}
		});
	})(jQuery);
}

function setMessage( msg ) {
	(function($){
		$( ".error" ).hide();
		$( ".updated" ).html( msg );
		$( ".updated" ).show();
		$( '#prtfl_settings_notice' ).hide();
	})(jQuery);
}

function setError( msg ) {
	(function($){
		$( ".updated" ).hide();
		$( ".error" ).html( msg );
		$( ".error" ).show();
	})(jQuery);
}

(function($) {
	$(document).ready( function() {
		$( '#prtfl_form_image_size input' ).bind( "change click select", function() {
			if ( $( this ).attr( 'type' ) != 'submit' ) {
				$( '.updated.fade' ).css( 'display', 'none' );
				$( '#prtfl_settings_notice' ).css( 'display', 'block' );
			};
		});
	});
})(jQuery);