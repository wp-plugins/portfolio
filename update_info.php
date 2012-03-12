<?php 
error_reporting(0);
require "../../../wp-config.php";

global $wpdb;
$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : "";
$id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : "";
switch($action) {
	case 'get_portfolio_id':
		$result = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'portfolio'" );
		echo json_encode( $result );
		break;
	case 'update_info':
		if( $id != "" && is_numeric( $id ) ) {
			$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key LIKE '_prtf%' AND post_id = " . $id );
			if( ! empty( $result ) ) {
				$new_post_meta = array();
				foreach( $result as $value ) {
					$new_post_meta[ $value->meta_key ] = $value->meta_value;
				}
				add_post_meta($id, 'prtfl_information', $new_post_meta);
				$wpdb->query( "DELETE FROM " . $wpdb->postmeta . " WHERE meta_key LIKE '_prtf%' AND post_id = " . $id );
			}
		}
		break;
	case 'update_options':
		add_option( 'prtfl_postmeta_update', '1', '', 'no' );
		break;
}
?>
