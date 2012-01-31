<?php 

error_reporting(0);
require "../../../wp-config.php";

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : "";
$id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : "";

switch($action) {
	case 'get_all_attachment':
		$result_parent_id = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'portfolio'", ARRAY_N );
		$array_parent_id = array();
		while(list($key, $val) = each($result_parent_id))
			$array_parent_id[] = $val[0];
		$result_attachment_id = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'attachment' AND post_mime_type LIKE 'image%' AND post_parent IN (".implode( ",", $array_parent_id ).")" );
		echo json_encode( $result_attachment_id );
		break;
	case 'update_image':
		$metadata = wp_get_attachment_metadata( $id );
		$uploads = wp_upload_dir();
		$path = $uploads['basedir']."/".$metadata['file'];
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$metadata_new = prtfl_wp_generate_attachment_metadata( $id, $path, $metadata );
		wp_update_attachment_metadata( $id, array_merge($metadata, $metadata_new) );
		break;
	case 'update_options':
		//add_option( 'prtfl_images_update', '1', '', 'no' );
		break;
}

function prtfl_wp_generate_attachment_metadata( $attachment_id, $file, $metadata ) {
	$attachment = get_post( $attachment_id );
	$prtfl_options = get_option( 'prtfl_options' );

	add_image_size( 'portfolio-thumb', $prtfl_options['prtfl_custom_size_px'][0][0], $prtfl_options['prtfl_custom_size_px'][0][1], true );
	add_image_size( 'portfolio-photo-thumb', $prtfl_options['prtfl_custom_size_px'][1][0], $prtfl_options['prtfl_custom_size_px'][1][1], true );

	//$metadata = array();
	if ( preg_match('!^image/!', get_post_mime_type( $attachment )) && file_is_displayable_image($file) ) {
		$imagesize = getimagesize( $file );
		$metadata['width'] = $imagesize[0];
		$metadata['height'] = $imagesize[1];
		list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
		$metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";

		// Make the file path relative to the upload dir
		$metadata['file'] = _wp_relative_upload_path($file);

		// make thumbnails and other intermediate sizes
		global $_wp_additional_image_sizes;
		
		$image_size = array( 'portfolio-thumb', 'portfolio-photo-thumb' );//get_intermediate_image_sizes();
		
		foreach ( $image_size as $s ) {
			$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => FALSE );
			if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
				$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); // For theme-added sizes
			else
				$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
				$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); // For theme-added sizes
			else
				$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
				$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] ); // For theme-added sizes
			else
				$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
		}

		$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );
		foreach ($sizes as $size => $size_data ) {
			$resized = prtfl_image_make_intermediate_size( $file, $size_data['width'], $size_data['height'], $size_data['crop'] );
			if ( $resized )
				$metadata['sizes'][$size] = $resized;
		}

		// fetch additional metadata from exif/iptc
		$image_meta = wp_read_image_metadata( $file );
		if ( $image_meta )
			$metadata['image_meta'] = $image_meta;

	}

	return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
}

function prtfl_image_make_intermediate_size($file, $width, $height, $crop=false) {
	if ( $width || $height ) {
		$resized_file = prtfl_image_resize($file, $width, $height, $crop);
		if ( !is_wp_error($resized_file) && $resized_file && $info = getimagesize($resized_file) ) {
			$resized_file = apply_filters('image_make_intermediate_size', $resized_file);
			return array(
				'file' => wp_basename( $resized_file ),
				'width' => $info[0],
				'height' => $info[1],
			);
		}
	}
	return false;
}

function prtfl_image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {

	$image = wp_load_image( $file );
	if ( !is_resource( $image ) )
		return new WP_Error( 'error_loading_image', $image, $file );

	$size = @getimagesize( $file );
	if ( !$size )
		return new WP_Error('invalid_image', __('Could not read image size'), $file);
	list($orig_w, $orig_h, $orig_type) = $size;

	$dims = prtfl_image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop);

	if ( !$dims )
		return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions') );
	list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

	$newimage = wp_imagecreatetruecolor( $dst_w, $dst_h );

	imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

	// convert from full colors to index colors, like original PNG.
	if ( IMAGETYPE_PNG == $orig_type && function_exists('imageistruecolor') && !imageistruecolor( $image ) )
		imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );

	// we don't need the original in memory anymore
	imagedestroy( $image );

	// $suffix will be appended to the destination filename, just before the extension
	if ( !$suffix )
		$suffix = "{$dst_w}x{$dst_h}";

	$info = pathinfo($file);
	$dir = $info['dirname'];
	$ext = $info['extension'];
	$name = wp_basename($file, ".$ext");

	if ( !is_null($dest_path) and $_dest_path = realpath($dest_path) )
		$dir = $_dest_path;
	$destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";

	if ( IMAGETYPE_GIF == $orig_type ) {
		if ( !imagegif( $newimage, $destfilename ) )
			return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
	} elseif ( IMAGETYPE_PNG == $orig_type ) {
		if ( !imagepng( $newimage, $destfilename ) )
			return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
	} else {
		// all other formats are converted to jpg
		$destfilename = "{$dir}/{$name}-{$suffix}.jpg";
		if ( !imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
			return new WP_Error('resize_path_invalid', __( 'Resize path invalid' ));
	}

	imagedestroy( $newimage );

	// Set correct file permissions
	$stat = stat( dirname( $destfilename ));
	$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
	@ chmod( $destfilename, $perms );

	return $destfilename;
}

function prtfl_image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

	if ($orig_w <= 0 || $orig_h <= 0)
		return false;
	// at least one of dest_w or dest_h must be specific
	if ($dest_w <= 0 && $dest_h <= 0)
		return false;

	if ( $crop ) {
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if ( !$new_w ) {
			$new_w = intval($new_h * $aspect_ratio);
		}

		if ( !$new_h ) {
			$new_h = intval($new_w / $aspect_ratio);
		}

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = floor( ($orig_w - $crop_w) / 2 );
		$s_y = 0;
	} else {
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
	}

	// if the resulting image would be the same size or larger we don't want to resize it
	if ( $new_w >= $orig_w && $new_h >= $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

}

?>