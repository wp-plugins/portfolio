<?php
/**
 * @package Portfolio
 * @version 1
 */
/*
Plugin Name: Portfolio
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin for portfolio.
Author: BestWebSoft
Version: 2.04
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  Copyright 2011  BestWebSoft  ( admin@bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$prtfl_boxes = array ();

if( ! function_exists( 'prtfl_plugin_install' ) ) {
	function prtfl_plugin_install() {
		if ( ! file_exists( get_stylesheet_directory() .'/portfolio.php' ) ) {
			@copy( WP_PLUGIN_DIR .'/portfolio/template/portfolio.php', get_stylesheet_directory() .'/portfolio.php' );
		}
		else {
			@copy( get_stylesheet_directory() .'/portfolio.php', get_stylesheet_directory() .'/portfolio.php.bak' );
			@copy( WP_PLUGIN_DIR .'/portfolio/template/portfolio.php', get_stylesheet_directory() .'/portfolio.php' );
		}
		if ( ! file_exists( get_stylesheet_directory() .'/portfolio-post.php' ) ) {
			@copy( WP_PLUGIN_DIR .'/portfolio/template/portfolio-post.php', get_stylesheet_directory() .'/portfolio-post.php' );
		}
		else {
			@copy( get_stylesheet_directory() .'/portfolio-post.php', get_stylesheet_directory() .'/portfolio-post.php.bak' );
			@copy( WP_PLUGIN_DIR .'/portfolio/template/portfolio-post.php', get_stylesheet_directory() .'/portfolio-post.php' );
		}
	}
}

if( ! function_exists( 'prtfl_admin_error' ) ) {
	function prtfl_admin_error() {
		$post = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : "" ;
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : "" ;
		if ( ( 'portfolio' == get_post_type( $post )  || 'portfolio' == $post_type ) && ( ! file_exists( get_stylesheet_directory() .'/portfolio.php' ) || ! file_exists( get_stylesheet_directory() .'/portfolio-post.php' ) ) ) {
			echo '<div class="error"><p><strong>'.__( 'The following files "portfolio.php" and "portfolio-post.php" were not found in the directory of your theme. Please copy them from the directory `/wp-content/plugins/portfolio/template/` to the directory of your theme for the correct work of the Portfolio plugin', 'portfolio' ).'</strong></p></div>';
		}
	}
}

if( ! function_exists( 'prtfl_plugin_uninstall' ) ) {
	function prtfl_plugin_uninstall() {
		if ( file_exists( get_stylesheet_directory() .'/portfolio.php' ) && ! unlink(get_stylesheet_directory() .'/portfolio.php') ) {
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		}
		if ( file_exists( get_stylesheet_directory() .'/portfolio-post.php' ) && ! unlink(get_stylesheet_directory() .'/portfolio-post.php') ) {
			add_action( 'admin_notices', create_function( '', ' return "Error delete template file";' ) );
		}
		if( get_option( 'prtfl_postmeta_update' ) ) {
			delete_option( 'prtfl_postmeta_update' );
		}
		if( get_option( 'prtfl_tag_update' ) ) {
			delete_option( 'prtfl_tag_update' );
		}
		if( get_option( 'prtfl_options' ) ) {
			delete_option( 'prtfl_options' );
		}		
	}
}

if ( ! function_exists ( 'prtfl_plugin_init' ) ) {
	function prtfl_plugin_init() {
	// Internationalization, first(!)
		load_plugin_textdomain( 'portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		global $prtfl_boxes;
		$prtfl_boxes['Portfolio-Info'] = array (
			array( '_prtfl_short_descr', __( 'Short description', 'portfolio' ), __( 'A short description which you\'d like to be displayed on your portfolio page', 'portfolio' ), '', '' ),
			array( '_prtfl_date_compl', __( 'Date of completion', 'portfolio' ), __( 'The date when the task was completed', 'portfolio' ), '', '' ),
			array( '_prtfl_link', __( 'Link', 'portfolio' ), __( 'A link to the site', 'portfolio' ), '', '' ),
			array( '_prtfl_svn', __( 'SVN', 'portfolio' ), __( 'URL of the SVN', 'portfolio' ), '', '' ),
		);

	}
}

// Create post type for portfolio
if( ! function_exists( 'prtfl_post_type_portfolio' ) ) {
	function prtfl_post_type_portfolio() {
		prtfl_replace_old_post_tag();

		register_post_type( 
			'portfolio',
			array( 
				'labels' => array(
					'name' => __( 'Portfolio', 'portfolio' ),
					'singular_name' => __( 'Portfolio', 'portfolio' ),
					'add_new'				=> __( 'Add New', 'portfolio' ),
					'add_new_item'	=> __( 'Add New Portfolio', 'portfolio' ),
					'edit'					=> __( 'Edit', 'portfolio' ),
					'edit_item'			=> __( 'Edit Portfolio', 'portfolio' ),
					'new_item'			=> __( 'New Portfolio', 'portfolio' ),
					'view'					=> __( 'View Portfolio', 'portfolio' ),
					'view_item'			=> __( 'View Portfolio', 'portfolio' ),
					'search_items'	=> __( 'Search Portfolio', 'portfolio' ),
					'not_found'			=> __( 'No portfolio found', 'portfolio' ),
					'not_found_in_trash' => __( 'No portfolio found in Trash', 'portfolio' ),
					'parent'				=> __( 'Parent Portfolio', 'portfolio' ),
				),
				'description' => __( 'Create a Portfolio.', 'portfolio' ), 
				'public'	=> true,
				'show_ui' => true,
				'publicly_queryable'	=> true,
				'exclude_from_search' => true,
				'menu_position' => 6,
				'hierarchical'	=> true,
				'query_var'			=> true,
				'register_meta_box_cb' => 'prtfl_init_metaboxes',
				'supports' => array (
					'title', //Text input field to create a post title.
					'editor',
					'custom-fields',
					'comments', //Ability to turn on/off comments.
					'thumbnail', //Displays a box for featured image.
					'author' 
				)
			)
		);
	}
}

// Create taxonomy for portfolio - Technologies and Executors Profile
if( ! function_exists( 'prtfl_taxonomy_portfolio' ) ) {
	function prtfl_taxonomy_portfolio() {		
		register_taxonomy(
			'portfolio_executor_profile',
			'portfolio',
			array(
				'hierarchical' => false,
				'update_count_callback' => '_update_post_term_count',
				'labels' => array(
					'name'								=> __( 'Executor Profiles', 'portfolio' ),
					'singular_name'				=> __( 'Executor Profile', 'portfolio' ),
					'search_items'				=> __( 'Search Executor Profiles', 'portfolio' ),
					'popular_items'				=> __( 'Popular Executor Profiles', 'portfolio' ),
					'all_items'						=> __( 'All Executor Profiles', 'portfolio' ),
					'parent_item'					=> __( 'Parent Executor Profile', 'portfolio' ),
					'parent_item_colon'		=> __( 'Parent Executor Profile:', 'portfolio' ),
					'edit_item'						=> __( 'Edit Executor Profile', 'portfolio' ),
					'update_item'					=> __( 'Update Executor Profile', 'portfolio' ),
					'add_new_item'				=> __( 'Add New Executor Profile', 'portfolio' ),
					'new_item_name'				=> __( 'New Executor Profile Name', 'portfolio' ),
					'separate_items_with_commas' => __( 'Separate Executor Profiles with commas', 'portfolio' ),
					'add_or_remove_items' => __( 'Add or remove Executor Profile', 'portfolio' ),
					'choose_from_most_used' => __( 'Choose from the most used Executor Profile', 'portfolio' ),
					'menu_name'						=> __( 'Executor Profiles ', 'portfolio' )
				),
				'sort'					=> true,
				'args'					=> array( 'orderby' => 'term_order' ),
				'rewrite'				=> array( 'slug' => 'executor_profile' ),
				'show_tagcloud' => false
			)
		);

		register_taxonomy( 
			'portfolio_technologies', 
			'portfolio', 
			array(
				'hierarchical' => false,
				'update_count_callback' => '_update_post_term_count',
				'labels' => array(
					'name'								=> __( 'Technologies', 'portfolio' ),
					'singular_name'				=> __( 'Technology', 'portfolio'),
					'search_items'				=> __( 'Search Technologies', 'portfolio' ),
					'popular_items'				=> __( 'Popular Technologies', 'portfolio' ),
					'all_items'						=> __( 'All Technologies', 'portfolio' ),
					'parent_item'					=> __( 'Parent Technology', 'portfolio' ),
					'parent_item_colon'		=> __( 'Parent Technology:', 'portfolio' ),
					'edit_item'						=> __( 'Edit Technology', 'portfolio' ),
					'update_item'					=> __( 'Update Technology', 'portfolio' ),
					'add_new_item'				=> __( 'Add New Technology', 'portfolio' ),
					'new_item_name'				=> __( 'New Technology Name', 'portfolio' ),
					'separate_items_with_commas' => __( 'Separate Technologies with commas', 'portfolio' ),
					'add_or_remove_items' => __( 'Add or remove Technology', 'portfolio' ),
					'choose_from_most_used' => __( 'Choose from the most used Technology', 'portfolio' ),
					'menu_name'						=> __( 'Technologies', 'portfolio' )
				),
				'query_var' => 'technologies',
				'rewrite'		=> array( 'slug' => 'technologies' ),
				'public'		=> true,
				'show_ui'		=> true,
				'_builtin'	=> true,
				'show_tagcloud' => false
			) 
		);
	}
}

if( ! function_exists( 'prtfl_technologies_get_posts' ) ) {
	function prtfl_technologies_get_posts( $query ) {
		if ( isset( $query->query_vars["technologies"] ) )
			$query->set( 'post_type', array( 'portfolio' ) );
		return $query;
	}
}

if( ! function_exists( 'prtfl_register_widget' ) ) {
	function prtfl_register_widget() {
		$control_ops = array('width' => 200, 'height' => 200, 'id_base' => 'portfolio_technologies_widget');

		wp_register_sidebar_widget(
			'portfolio_technologies_widget', 
			__( 'Technologies', 'portfolio' ),
			'prtfl_widget_display',
			array( 
					'description' => __( 'Your most used portfolio technologies in cloud format', 'portfolio' )
			)
		);
		wp_register_widget_control(
			'portfolio_technologies_widget', // your unique widget id
			__( 'Technologies', 'portfolio' ), // widget name
			'prtfl_widget_display_control', // Callback function
			$control_ops, 
			array( 'number' => 1 )
		);
	}
}

if( ! function_exists( 'prtfl_widget_display' ) ) {
	function prtfl_widget_display( $args, $vars = array() ) {
		 // print some HTML for the widget to display here
		extract( $args);
		$options = get_option( 'widget-portfolio_technologies_widget' );
		if ( !empty( $options[1]['title'] ) ) {
			$title = $options[1]['title'];
		} 
		else {
			$title = $widget_name;
		}
		$title = apply_filters( 'widget_title', $title, '', 'portfolio_technologies_widget' );

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array( 'taxonomy' => 'portfolio_technologies', 'number'=>0 ) ) );
		echo "</div>\n";
		echo $after_widget;
	}
}

if( ! function_exists( 'prtfl_widget_display_control' ) ) {
	function prtfl_widget_display_control( $args ) { 

		$options = get_option( 'widget-portfolio_technologies_widget' );
		if( empty( $options ) ) $options = array();
		if( isset( $options[0] ) ) unset( $options[0] );
	 
		// update options array
		if( ! empty( $_POST['widget-portfolio_technologies_widget'] ) && is_array( $_POST ) ){
			foreach( $_POST['widget-portfolio_technologies_widget'] as $widget_number => $values ){
				if( empty( $values ) && isset( $options[$widget_number] ) ) // user clicked cancel
					continue;
	 
				if( ! isset( $options[$widget_number] ) && $args['number'] == -1 ){
					$args['number'] = $widget_number;
				}
				$options[$widget_number] = $values;
			}
		
			// clear unused options and update options in DB. return actual options array
			$options = prtfl_widget_portfolio_technologies_update( 'widget-portfolio_technologies_widget', $options, $_POST['widget-portfolio_technologies_widget'], $_POST['sidebar'], 'widget-portfolio_technologies_widget' );
		}
	 
		// $number - is dynamic number for multi widget, gived by WP
		// by default $number = -1 (if no widgets activated). In this case we should use %i% for inputs
		//   to allow WP generate number automatically
		$number = ( $args['number'] == -1)? '%i%' : $args['number'];
	 
		// now we can output control
		$title = $options[$number]['title'];

	?>
		<p><label for="widget-portfolio_technologies_widget-<?php echo $number; ?>-title"><?php _e( 'Title', 'portfolio' ); ?>:</label>
		<input type="text" value="<?php echo $title; ?>" name="widget-portfolio_technologies_widget[<?php echo $number; ?>][title]" id="widget-portfolio_technologies_widget-<?php echo $number; ?>-title" class="widefat"></p>
	<?php 
	}
}

if( ! function_exists( 'prtfl_widget_portfolio_technologies_update' ) ) {
	function prtfl_widget_portfolio_technologies_update( $id_prefix, $options, $post, $sidebar, $option_name = '' ){
		global $wp_registered_widgets;
		static $updated = false;
 
		// get active sidebar
		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset( $sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();
 
		// search unused options
		foreach ( $this_sidebar as $_widget_id ) {
			if( preg_match( '/' . $id_prefix . '-([0-9]+)/i', $_widget_id, $match ) ){
				$widget_number = $match[1];
 
				// $_POST['widget-id'] contain current widgets set for current sidebar
				// $this_sidebar is not updated yet, so we can determine which was deleted
				if( ! in_array( $match[0], $_POST['widget-id'] ) ){
					unset( $options[$widget_number] );
				}
			}
		}
 
		// update database
		if( ! empty( $option_name ) ) {
			update_option( $option_name, $options );
			$updated = true;
		}
 
		// return updated array
		return $options;
	}
}

// Create custom permalinks for portfolio post type
if( ! function_exists( 'prtfl_custom_permalinks' ) ) {
	function prtfl_custom_permalinks() {
		global $wp_rewrite;
		$wp_rewrite->add_rule( 'portfolio/page/([^/]+)/?$', 'index.php?pagename=portfolio&paged=$matches[1]', 'top' );
		$wp_rewrite->add_rule( 'portfolio/page/([^/]+)?$', 'index.php?pagename=portfolio&paged=$matches[1]', 'top' );
    $wp_rewrite->flush_rules();
	}
}

// Initialization of all metaboxes on the 'Add Portfolio' and Edit Portfolio pages
if ( ! function_exists( 'prtfl_init_metaboxes' ) ) {
	function prtfl_init_metaboxes() {
		add_meta_box( 'Portfolio-Info', __( 'Portfolio Info', 'portfolio' ), 'prtfl_post_custom_box', 'portfolio', 'normal', 'high' ); // Description metaboxe
	}
}

// Create custom meta box for portfolio post type
if ( ! function_exists( 'prtfl_post_custom_box' ) ) {
	function prtfl_post_custom_box( $obj = '', $box = '' ) {
		global $prtfl_boxes;
		static $sp_nonce_flag = false;
		// Run once
		if( ! $sp_nonce_flag ) {
			prtfl_echo_nonce();
			$sp_nonce_flag = true;
		}
		// Generate box contents
		foreach( $prtfl_boxes[ $box[ 'id' ] ] as $prtfl_box ) {
			echo prtfl_text_field( $prtfl_box );
		}
	}
}

// This is the default text field meta box
if( ! function_exists( 'prtfl_text_field' ) ) {
	function prtfl_text_field( $args ) {
		global $post;

		$description	= $args[2];
		if( get_option( 'prtfl_postmeta_update' ) == '1' ) {
			$post_meta = get_post_meta( $post->ID, 'prtfl_information', true);
			$args[ 2 ] = is_array( $post_meta ) ? esc_html ( $post_meta[ $args[0] ] ) : "" ;
		}
		else {
			$args[ 2 ] = esc_html ( get_post_meta( $post->ID, $args[0], true ) );
		}
		$label_format =
			'<div class="portfolio_admin_box">'.
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
			'<p><input style="width: 80%%;" type="text" name="%1$s" id="%1$s" value="%3$s" /></p>'.
			'<p><em>'. $description .'</em></p>'.
			'</div>';
		if( '_prtfl_date_compl' == $args[0] ) {
			echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("#_prtfl_date_compl").simpleDatepicker({ startdate: new Date().getFullYear()-3, enddate: new Date().getFullYear()+3 });});</script>';
		}
		return vsprintf( $label_format, $args );
	}
}

// This is the text area meta box
if( ! function_exists( 'prtfl_prtfl_text_area' ) ) {
	function prtfl_text_area( $args ) {
		global $post;

		$description	= $args[2];
		$args[2]		= esc_html( get_post_meta( $post->ID, $args[0], true ) );
		$label_format =
			'<div class="portfolio_admin_box">'.
			'<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
			'<p><textarea class="theEditor" id="theEditor" style="width: 90%%;color:#000;" name="%1$s">%3$s</textarea></p>'.
			'<p><em>'. $description .'</em></p>'.
			'</div>';
		return vsprintf( $label_format, $args );
	}
}


// Use nonce for verification ...
if( ! function_exists ( 'prtfl_echo_prtfl_nonce' ) ) {
	function prtfl_echo_nonce () {
		echo sprintf(
			'<input type="hidden" name="%1$s" id="%1$s" value="%2$s" />',
			'prtfl_nonce_name',
			wp_create_nonce( plugin_basename(__FILE__) )
		);
	}
}

/* When the post is saved, saves our custom data */
if ( ! function_exists ( 'prtfl_save_postdata' ) ) {
	function prtfl_save_postdata( $post_id, $post ) {
		global $prtfl_boxes;

		if( "portfolio" == $post->post_type && ! wp_is_post_revision( $post_id ) && ! empty( $_POST ) ) { // don't store custom data twice
			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if( ! current_user_can ( 'edit_page', $post->ID ) ) {
				return $post->ID;
			} 
			// We'll put it into an array to make it easier to loop though.
			// The data is already in $prtfl_boxes, but we need to flatten it out.
			foreach( $prtfl_boxes as $prtfl_boxe ) {
				foreach( $prtfl_boxe as $prtfl_fields ) {
					$my_data[ $prtfl_fields[0] ] = $_POST[ $prtfl_fields[0] ];
				}
			}

			// Add values of $my_data as custom fields
			// Let's cycle through the $my_data array!
			if( get_option( 'prtfl_postmeta_update' ) == '1' ) {
				if( get_post_meta( $post->ID, 'prtfl_information', FALSE ) ) {
					// Custom field has a value and this custom field exists in database
					update_post_meta( $post->ID, 'prtfl_information', $my_data );
				} 
				elseif( $value ) {
					// Custom field has a value, but this custom field does not exist in database
					add_post_meta( $post->ID, 'prtfl_information', $my_data );
				}
				else {
					// Custom field does not have a value, but this custom field exists in database
					update_post_meta( $post->ID, 'prtfl_information', $my_data );
				}
			}
			else {
				foreach( $my_data as $key => $value ) {
					// if $value is an array, make it a CSV (unlikely)
					$value = implode( ',', ( array ) $value );
					if( get_post_meta( $post->ID, $key, FALSE ) && $value ) {
						// Custom field has a value and this custom field exists in database
						update_post_meta( $post->ID, $key, $value );
					} 
					elseif( $value ) {
						// Custom field has a value, but this custom field does not exist in database
						add_post_meta( $post->ID, $key, $value );
					}
					else {
						// Custom field does not have a value, but this custom field exists in database
						update_post_meta( $post->ID, $key, $value );
					}
				}
			}
		}
	}
}

// This is pagenation functionality for portfolio post type
if( ! function_exists ( 'prtfl_pagination' ) ) {
	function prtfl_pagination( $pages = '', $range = 2 ) {  
		 $showitems = get_option( 'posts_per_page' );

		 global $paged, $wp_query;
		 if( empty ( $paged ) ) 
			 $paged = 1;
		 if( '' == $pages ) {
			 $pages = $wp_query->max_num_pages;
			 if( ! $pages ) {
				 $pages = 1;
			 }
		 }   

		 if( 1 != $pages ) {
			 echo "<div class='pagination'>";
			 if( 2 < $paged && $paged > $range + 1 && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( 1 ) ."'>&laquo;</a>";
			 if( 1 < $paged && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $paged - 1 ) ."'>&lsaquo;</a>";

			 for ( $i = 1; $i <= $pages; $i++ ) {
				 if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						 echo ( $paged == $i ) ? "<span class='current'>". $i ."</span>":"<a href='". get_pagenum_link( $i) ."' class='inactive' >". $i ."</a>";
				 }
			 }

			 if ( $paged < $pages && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $paged + 1 ) ."'>&rsaquo;</a>";  
			 if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) 
				 echo "<a href='". get_pagenum_link( $pages ) ."'>&raquo;</a>";
			 echo "</div>\n";
		 }
	}
}

if( ! function_exists ( 'prtfl_register_plugin_links' ) ) {
	function prtfl_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[] = '<a href="http://wordpress.org/extend/plugins/portfolio/faq/" target="_blank">' . __('FAQ','portfolio') . '</a>';
			$links[] = '<a href="Mailto:plugin@bestwebsoft.com">' . __('Support','portfolio') . '</a>';
		}
		return $links;
	}
}

if( ! function_exists ( 'prtfl_replace_old_post_tag' ) ) {
	function prtfl_replace_old_post_tag() {
		global $wpdb;
		if( false === get_option( 'prtfl_tag_update' ) ) {
			$tag_id_array = $wpdb->get_results( "SELECT term_taxonomy_id FROM $wpdb->posts, $wpdb->term_relationships WHERE post_type = 'portfolio' AND $wpdb->posts.ID = $wpdb->term_relationships.object_id" );
			while(list( $key, $val) = each( $tag_id_array)){
				$wpdb->update( 
					$wpdb->term_taxonomy, 
					array( 
						'taxonomy' => 'portfolio_technologies'
					), 
					array( 
						'taxonomy' => 'post_tag' , 
						'term_taxonomy_id' => $val->term_taxonomy_id
					), 
					array( 
						'%s'
					), 
					array( 
						'%s', 
						'%d' 
					) 
				);
			}
			$wpdb->query( "UPDATE $wpdb->posts, $wpdb->postmeta SET $wpdb->posts.post_content = $wpdb->postmeta.meta_value WHERE $wpdb->postmeta.post_id = $wpdb->posts.ID AND $wpdb->posts.post_type = 'portfolio' AND	$wpdb->postmeta.meta_key = '_prtfl_descr' " );
			$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = '_prtfl_descr'"	);
			$wpdb->query( "UPDATE $wpdb->postmeta SET meta_key = REPLACE(meta_key, 'prtf_', 'prtfl_') WHERE meta_key LIKE '_prtf_%'" );
			add_option( 'prtfl_tag_update', '1', '', 'no' );
		}
		$postmeta = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE '%_short_descr%' LIMIT 0 , 1" );
		if( empty( $postmeta ) && ! get_option( 'prtfl_postmeta_update' ) ) {
			add_option( 'prtfl_postmeta_update', 1 );
		}
	}
}

if( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $title;
		$active_plugins = get_option('active_plugins');
		$all_plugins		= get_plugins();

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://wordpress.org/extend/plugins/captcha/', 'http://bestwebsoft.com/plugin/captcha-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Captcha+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=captcha.php' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://wordpress.org/extend/plugins/contact-form-plugin/', 'http://bestwebsoft.com/plugin/contact-form/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Contact+Form+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=contact_form.php' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://wordpress.org/extend/plugins/facebook-button-plugin/', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Facebook+Like+Button+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=facebook-button-plugin.php' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://wordpress.org/extend/plugins/twitter-plugin/', 'http://bestwebsoft.com/plugin/twitter-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Twitter+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=twitter.php' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://wordpress.org/extend/plugins/portfolio/', 'http://bestwebsoft.com/plugin/portfolio-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Portfolio+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'gallery-plugin\/gallery-plugin.php', 'Gallery', 'http://wordpress.org/extend/plugins/gallery-plugin/', 'http://bestwebsoft.com/plugin/gallery-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Gallery+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'adsense-plugin\/adsense-plugin.php', 'Google AdSense Plugin', 'http://wordpress.org/extend/plugins/adsense-plugin/', 'http://bestwebsoft.com/plugin/google-adsense-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Adsense+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=adsense-plugin.php' ),
			array( 'custom-search-plugin\/custom-search-plugin.php', 'Custom Search Plugin', 'http://wordpress.org/extend/plugins/custom-search-plugin/', 'http://bestwebsoft.com/plugin/custom-search-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Search+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_search.php' ),
			array( 'quotes-and-tips\/quotes-and-tips.php', 'Quotes and Tips', 'http://wordpress.org/extend/plugins/quotes-and-tips/', 'http://bestwebsoft.com/plugin/quotes-and-tips/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Quotes+and+Tips+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=quotes-and-tips.php' ),
			array( 'google-sitemap-plugin\/google-sitemap-plugin.php', 'Google sitemap plugin', 'http://wordpress.org/extend/plugins/google-sitemap-plugin/', 'http://bestwebsoft.com/plugin/google-sitemap-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Google+sitemap+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=google-sitemap-plugin.php' )
		);
		foreach($array_plugins as $plugins) {
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link']	= $plugins[2];
				$array_activate[$count_activate]['href']	= $plugins[3];
				$array_activate[$count_activate]['url']	= $plugins[5];
				$count_activate++;
			}
			else if( array_key_exists(str_replace("\\", "", $plugins[0]), $all_plugins) ) {
				$array_install[$count_install]['title'] = $plugins[1];
				$array_install[$count_install]['link']	= $plugins[2];
				$array_install[$count_install]['href']	= $plugins[3];
				$count_install++;
			}
			else {
				$array_recomend[$count_recomend]['title'] = $plugins[1];
				$array_recomend[$count_recomend]['link']	= $plugins[2];
				$array_recomend[$count_recomend]['href']	= $plugins[3];
				$array_recomend[$count_recomend]['slug']	= $plugins[4];
				$count_recomend++;
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<?php if( 0 < $count_activate ) { ?>
			<div>
				<h3><?php _e( 'Activated plugins', 'portfolio' ); ?></h3>
				<?php foreach( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin['title']; ?></div> <p><a href="<?php echo $activate_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'portfolio'); ?></a> <a href="<?php echo $activate_plugin['url']; ?>"><?php echo __( "Settings", 'portfolio'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install ) { ?>
			<div>
				<h3><?php _e( 'Installed plugins', 'portfolio' ); ?></h3>
				<?php foreach($array_install as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin['title']; ?></div> <p><a href="<?php echo $install_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'portfolio'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend ) { ?>
			<div>
				<h3><?php _e( 'Recommended plugins', 'portfolio' ); ?></h3>
				<?php foreach( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin['title']; ?></div> <p><a href="<?php echo $recomend_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'portfolio'); ?></a> <a href="<?php echo $recomend_plugin['href']; ?>" target="_blank"><?php echo __( "Download", 'portfolio'); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin['slug']; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin['title'] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'portfolio' ) ?></a></p>
				<?php } ?>
				<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via plugin@bestwebsoft.com or fill in our contact form on our site', 'portfolio' ); ?> <a href="http://bestwebsoft.com/contact/">http://bestwebsoft.com/contact/</a></span>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

if( ! function_exists( 'add_prtfl_admin_menu' ) ) {
	function add_prtfl_admin_menu() {
		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 ); 
		add_submenu_page( 'bws_plugins', __( 'Portfolio', 'portfolio' ), __( 'Portfolio', 'portfolio' ), 'manage_options', "portfolio.php", 'prtfl_settings_page' );

		//call register settings function
		add_action( 'admin_init', 'register_prtfl_settings' );
	}
}

// register settings function
if( ! function_exists( 'register_prtfl_settings' ) ) {
	function register_prtfl_settings() {
		global $wpmu;
		global $prtfl_options;

		$prtfl_option_defaults = array(
			'prtfl_custom_size_name'	=> array( 'portfolio-thumb', 'portfolio-photo-thumb' ),
			'prtfl_custom_size_px'		=> array( array( 280, 300 ), array( 240, 260 ) ),
			'prtfl_custom_image_row_count'	=> 3,
			'prtfl_svn_additional_field' => 1,
			'prtfl_executor_additional_field' => 1
		);

		// install the option defaults
		if ( 1 == $wpmu ) {
			if( !get_site_option( 'prtfl_options' ) ) {
				add_site_option( 'prtfl_options', $prtfl_option_defaults, '', 'yes' );
			}
		} 
		else {
			if( ! get_option( 'prtfl_options' ) )
				add_option( 'prtfl_options', $prtfl_option_defaults, '', 'yes' );
		}

		// get options from the database
		if ( 1 == $wpmu )
		 $prtfl_options = get_site_option( 'prtfl_options' ); // get options from the database
		else
		 $prtfl_options = get_option( 'prtfl_options' );// get options from the database

		if( isset( $prtfl_options['prtfl_prettyPhoto_style'] ) )
			unset( $prtfl_options['prtfl_prettyPhoto_style'] );

		// array merge incase this version has added new options
		$prtfl_options = array_merge( $prtfl_option_defaults, $prtfl_options );

		update_option( 'prtfl_options', $prtfl_options );

		if ( function_exists( 'add_image_size' ) ) { 
			add_image_size( 'portfolio-thumb', $prtfl_options['prtfl_custom_size_px'][0][0], $prtfl_options['prtfl_custom_size_px'][0][1], true );
			add_image_size( 'portfolio-photo-thumb', $prtfl_options['prtfl_custom_size_px'][1][0], $prtfl_options['prtfl_custom_size_px'][1][1], true );
		}
	}
}

if( ! function_exists( 'prtfl_settings_page' ) ) {
	function prtfl_settings_page() {
		global $prtfl_options;
		$error = "";
		
		// Save data for settings page
		if( isset( $_REQUEST['prtfl_form_submit'] ) ) {
			$prtfl_request_options = array();
			$prtfl_request_options["prtfl_custom_size_name"] = $prtfl_options["prtfl_custom_size_name"];

			$prtfl_request_options["prtfl_custom_size_px"] = array( 
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_album'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_album'] ) ) ), 
				array( intval( trim( $_REQUEST['prtfl_custom_image_size_w_photo'] ) ), intval( trim( $_REQUEST['prtfl_custom_image_size_h_photo'] ) ) ) 
			);
			$prtfl_request_options["prtfl_custom_image_row_count"] =  intval( trim( $_REQUEST['prtfl_custom_image_row_count'] ) );
			if( $prtfl_request_options["prtfl_custom_image_row_count"] == "" || $prtfl_request_options["prtfl_custom_image_row_count"] < 1 )
				$prtfl_request_options["prtfl_custom_image_row_count"] = 1;

			$prtfl_request_options["prtfl_svn_additional_field"] = isset( $_REQUEST["prtfl_svn_additional_field"] ) ? $_REQUEST["prtfl_svn_additional_field"] : 0;
			$prtfl_request_options["prtfl_executor_additional_field"] = isset( $_REQUEST["prtfl_executor_additional_field"] ) ? $_REQUEST["prtfl_executor_additional_field"] : 0;

			// array merge incase this version has added new options
			$prtfl_options = array_merge( $prtfl_options, $prtfl_request_options );

			// Check select one point in the blocks Arithmetic actions and Difficulty on settings page
			update_option( 'prtfl_options', $prtfl_options, '', 'yes' );
			$message = __( "Options saved.", 'portfolio' );
		}

		if ( ! file_exists( get_stylesheet_directory() .'/portfolio.php' ) || ! file_exists( get_stylesheet_directory() .'/portfolio-post.php' ) ) {
			$error .= __( 'The following files "portfolio.php" and "portfolio-post.php" were not found in the directory of your theme. Please copy them from the directory `/wp-content/plugins/portfolio/template/` to the directory of your theme for the correct work of the Portfolio plugin', 'portfolio' );
		}

		// Display form on the setting page
	?>
	<div class="wrap">
		<div class="icon32 icon32-bws" id="icon-options-general"></div>
		<h2><?php _e( 'Portfolio Options', 'portfolio' ); ?></h2>
		<div class="updated fade" <?php if( ! isset( $_REQUEST['prtfl_form_submit'] ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
		<div class="error" <?php if( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
		<form method="post" action="admin.php?page=portfolio.php" id="prtfl_form_image_size">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('The size of the cover album for portfolio', 'portfolio' ); ?> </th>
					<td>
						<label for="prtfl_custom_image_size_name"><?php _e( 'Image size name', 'portfolio' ); ?></label> <?php echo $prtfl_options["prtfl_custom_size_name"][0]; ?><br />
						<label for="prtfl_custom_image_size_w"><?php _e( 'Width (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_w_album" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][0]; ?>" /><br />
						<label for="prtfl_custom_image_size_h"><?php _e( 'Height (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_h_album" value="<?php echo $prtfl_options["prtfl_custom_size_px"][0][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Size for portfolio image', 'portfolio' ); ?> </th>
					<td>
						<label for="prtfl_custom_image_size_name"><?php _e( 'Image size name', 'portfolio' ); ?></label> <?php echo $prtfl_options["prtfl_custom_size_name"][1]; ?><br />
						<label for="prtfl_custom_image_size_w"><?php _e( 'Width (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_w_photo" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][0]; ?>" /><br />
						<label for="prtfl_custom_image_size_h"><?php _e( 'Height (in px)', 'portfolio' ); ?></label> <input type="text" name="prtfl_custom_image_size_h_photo" value="<?php echo $prtfl_options["prtfl_custom_size_px"][1][1]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2"><span style="color: #888888;font-size: 10px;"><?php _e( 'WordPress will create a copy of the post thumbnail with the specified dimensions when you upload a new photo. It is necessary to click on the button Update images at the bottom of this page in order to generate new images according to new dimensions ', 'portfolio' ); ?></span></th>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Count images in row', 'portfolio' ); ?> </th>
					<td>
						<input type="text" name="prtfl_custom_image_row_count" value="<?php echo $prtfl_options["prtfl_custom_image_row_count"]; ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Display additional fields', 'portfolio' ); ?> </th>
					<td>
						<input type="checkbox" name="prtfl_svn_additional_field" value="1" id="prtfl_svn_additional_field" <?php if( 1 == $prtfl_options['prtfl_svn_additional_field'] ) echo "checked=\"checked\""; ?> /><label for="prtfl_svn_additional_field" style="float:none;"><?php _e( 'SVN', 'portfolio' ); ?></label>
						<input type="checkbox" name="prtfl_executor_additional_field" value="1" id="prtfl_executor_additional_field" <?php if( 1 == $prtfl_options['prtfl_executor_additional_field'] ) echo "checked=\"checked\""; ?> /><label for="prtfl_executor_additional_field" style="float:none;"><?php _e( 'Executor', 'portfolio' ); ?></label>
					</td>
				</tr>
			</table>    
			<input type="hidden" name="prtfl_form_submit" value="submit" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		<?php global $wpdb; 
		if( get_option( 'prtfl_tag_update' ) == '1' )
			$prefix = '_prtfl';
		else
			$prefix = '_prtf';
		if ( $wpdb->get_var( "SELECT meta_id FROM ".$wpdb->postmeta." WHERE meta_key = '".$prefix."_short_descr' LIMIT 1" ) != NULL ) { ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Update the way storage your post_meta information for portfolio', 'portfolio' ); ?> </th>
				<td style="position:relative">
					<input type="button" value="<?php _e( 'Update All Info' ); ?>" id="ajax_update_postmeta" name="ajax_update_postmeta" class="button" onclick="javascript:update_postmeta();"> <div id="prtfl_loader"><img src="<?php echo plugins_url( 'images/ajax-loader.gif', __FILE__ ); ?>" alt="loader" /></div>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
			var update_message = "<?php _e( 'Update post_meta information...', 'portfolio' ) ?>";
			var update_url = "<?php echo plugins_url( 'update_info.php', __FILE__ ); ?>";
			var not_found_info = "<?php _e( 'No informations found.', 'portfolio'); ?>";
			var success = "<?php _e( 'All information was updated.', 'portfolio' ); ?>";
			var error = "<?php _e( 'Error.', 'portfolio' ); ?>";
		</script>
		<?php } ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e( 'Update images for portfolio', 'portfolio' ); ?> </th>
				<td style="position:relative">
					<input type="button" value="<?php _e( 'Update images' ); ?>" id="ajax_update_images" name="ajax_update_images" class="button" onclick="javascript:update_images();"> <div id="prtfl_img_loader"><img src="<?php echo plugins_url( 'images/ajax-loader.gif', __FILE__ ); ?>" alt="loader" /></div>
				</td>
			</tr>
		</table>
		<script type="text/javascript">
			var update_img_message = "<?php _e( 'Update images...', 'portfolio' ) ?>";
			var update_img_url = "<?php echo plugins_url( 'update_images.php', __FILE__ ); ?>";
			var not_found_img_info = "<?php _e( 'No images found.', 'portfolio'); ?>";
			var img_success = "<?php _e( 'All images was updated.', 'portfolio' ); ?>";
			var img_error = "<?php _e( 'Error.', 'portfolio' ); ?>";
		</script>
	</div>
	<?php } 
}

if ( ! function_exists( 'prtfl_template_redirect' ) ) {
	function prtfl_template_redirect() 
	{ 
		global $wp_query, $post, $posts;
		if( 'portfolio' == get_post_type() && "" == $wp_query->query_vars["s"] && ! isset( $wp_query->query_vars["technologies"] ) ) {
			include( get_stylesheet_directory() . '/portfolio-post.php' );
			exit(); 
		}
		else if( 'portfolio' == get_post_type() && isset( $wp_query->query_vars["technologies"] ) ) {
			include( get_stylesheet_directory() . '/portfolio.php' );
			exit(); 
		}
	}
}

if ( ! function_exists ( 'prtfl_add_template_in_new_theme' ) ) {
	function prtfl_add_template_in_new_theme() {
		if( ! file_exists( get_stylesheet_directory() .'/portfolio.php' ) || ! file_exists( get_stylesheet_directory() .'/portfolio-post.php' ) )
			prtfl_plugin_install();
	}
}

if ( ! function_exists ( 'prtfl_plugin_action_links' ) ) {
	function prtfl_plugin_action_links( $links, $file ) {
			//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename( __FILE__ );

		if ( $file == $this_plugin ){
				 $settings_link = '<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
				 array_unshift( $links, $settings_link );
			}
		return $links;
	} // end function cptch_plugin_action_links
}

if ( ! function_exists ( 'prtfl_register_plugin_links' ) ) {
	function prtfl_register_plugin_links($links, $file) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=portfolio.php">' . __( 'Settings', 'portfolio' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/extend/plugins/portfolio/faq/" target="_blank">' . __( 'FAQ', 'portfolio' ) . '</a>';
			$links[] = '<a href="Mailto:plugin@bestwebsoft.com">' . __( 'Support', 'portfolio' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists( 'prtfl_add_portfolio_ancestor_to_menu' ) ) {
	function prtfl_add_portfolio_ancestor_to_menu( $classes, $item ) {
		
		if ( is_singular( 'portfolio' ) ) {
			global $wpdb, $post;
			$parent = $wpdb->get_var( "SELECT $wpdb->posts.post_name FROM $wpdb->posts, $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'portfolio.php' AND (post_status = 'publish' OR post_status = 'private') AND $wpdb->posts.ID = $wpdb->postmeta.post_id" );	
			
			if ( in_array( 'menu-item-' . $item->ID, $classes ) && $parent == strtolower( $item->title ) ) {
				$classes[] = 'current-page-ancestor';
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'prtfl_latest_items' ) ) {
	function prtfl_latest_items( $atts ) {
		$content = '<div class="prtfl_portfolio_block">';
		$args = array(
			'post_type'					=> 'portfolio',
			'post_status'				=> 'publish',
			'orderby'						=> 'data',
			'order'							=> 'ASC',
			'posts_per_page'		=> $atts['count'],
			);
		query_posts( $args );
				
		while ( have_posts() ) : the_post(); 
			$content .= '
			<div class="portfolio_content">
				<div class="entry">';
					global $post;
					$meta_values				= get_post_custom($post->ID);
					$post_thumbnail_id	= get_post_thumbnail_id( $post->ID );
					if( empty ( $post_thumbnail_id ) ) {
						$args = array(
							'post_parent' => $post->ID,
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'numberposts' => 1
						);
						$attachments				= get_children( $args );
						$post_thumbnail_id	= key($attachments);
					}
					$image						= wp_get_attachment_image_src( $post_thumbnail_id, $atts['thumbnail'] );
					$image_alt				= get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
					$image_desc 			= get_post($post_thumbnail_id);
					$image_desc				= $image_desc->post_content;
					if( get_option( 'prtfl_postmeta_update' ) == '1' ) {
						$post_meta		= get_post_meta( $post->ID, 'prtfl_information', true);
						$date_compl		= $post_meta['_prtfl_date_compl'];
						if( ! empty( $date_compl ) && 'in progress' != $date_compl) {
							$date_compl		= explode( "/", $date_compl );
							$date_compl		= date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
						}
						$link					= $post_meta['_prtfl_link'];
						$short_descr	= $post_meta['_prtfl_short_descr'];
					}
					else{
						$date_compl		= get_post_meta( $post->ID, '_prtfl_date_compl', true );
						if( ! empty( $date_compl ) && 'in progress' != $date_compl) {
							$date_compl		= explode( "/", $date_compl );
							$date_compl		= date( get_option( 'date_format' ), strtotime( $date_compl[1]."-".$date_compl[0].'-'.$date_compl[2] ) );
						}
						$link					= get_post_meta($post->ID, '_prtfl_link', true);
						$short_descr	= get_post_meta($post->ID, '_prtfl_short_descr', true); 
					} 

					$content .= '<div class="portfolio_thumb" style="width:165px">
							<img src="'.$image[0].'" width="'.$image[1].'" alt="'.$image_alt.'" />
					</div>
					<div class="portfolio_short_content">
						<div class="item_title">
							<p>
								<a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a>
							</p>
						</div> <!-- .item_title -->';
						$content .= '<p>'.$short_descr.'</p>
					</div> <!-- .portfolio_short_content -->
				</div> <!-- .entry -->
				<div class="read_more">
					<a href="'.get_permalink().'" rel="bookmark">'.__( 'Read more', 'portfolio' ).'</a>
				</div> <!-- .read_more -->
				<div class="portfolio_terms">';
				$terms = wp_get_object_terms( $post->ID, 'portfolio_technologies' ) ;			
				if ( is_array( $terms ) && count( $terms ) > 0) { 
					$content .= __( 'Technologies', 'portfolio' ).':';
					$count = 0;
					foreach ( $terms as $term ) {
						if( $count > 0 ) 
							$content .= ', '; 
						$content .= '<a href="'. get_term_link( $term->slug, 'portfolio_technologies') . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a>';
						$count++;
					}
				}
				else {
					$content .= '&nbsp;';
				}
				$content .= '</div><!-- .portfolio_terms -->';
			$content .= '<div class="prtfl_clear"></div></div> <!-- .portfolio_content -->';
		endwhile; 		
		$content .= '</div> <!-- .prtfl_portfolio_block -->';
		wp_reset_query();
		return $content;
	}
}


/* Register style and script files */
if ( ! function_exists ( 'prtfl_admin_head' ) ) {
	function prtfl_admin_head() {
		wp_enqueue_style( 'portfolioStylesheet', plugins_url( 'css/stylesheet.css', __FILE__ ) );
		wp_enqueue_style( 'portfolioDatepickerStylesheet', plugins_url( 'datepicker/datepicker.css', __FILE__ ) );
		wp_enqueue_script( 'portfolioScript', plugins_url( 'js/script.js', __FILE__ ) );
		wp_enqueue_script( 'portfolioDatepickerScript', plugins_url( 'datepicker/datepicker.js', __FILE__ ) );  
	}
}

if ( ! function_exists ( 'prtfl_wp_head' ) ) {
	function prtfl_wp_head() {
		wp_enqueue_style( 'portfolioLightboxStylesheet', plugins_url( 'fancybox/jquery.fancybox-1.3.4.css', __FILE__ ) );
		wp_enqueue_style( 'portfolioStylesheet', plugins_url( 'css/stylesheet.css', __FILE__ ) );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'portfolioScript', plugins_url( 'js/script.js', __FILE__ ) );
		wp_enqueue_script( 'portfolioFancyboxMousewheelJs', plugins_url( 'fancybox/jquery.mousewheel-3.0.4.pack.js', __FILE__ ), array( 'jquery' ) ); 
		wp_enqueue_script( 'portfolioFancyboxJs', plugins_url( 'fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__ ), array( 'jquery' ) ); 
	}
}

register_activation_hook( __FILE__, 'prtfl_plugin_install'); // activate plugin
register_uninstall_hook( __FILE__, 'prtfl_plugin_uninstall' ); // deactivate plugin

// adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'prtfl_plugin_action_links', 10, 2 );
add_filter( 'nav_menu_css_class', 'prtfl_add_portfolio_ancestor_to_menu', 10, 2 );

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'prtfl_register_plugin_links', 10, 2 );

add_action( 'admin_init', 'prtfl_admin_error' );

add_action( 'admin_menu', 'add_prtfl_admin_menu' ); // add portfolio settings page in admin menu
add_action( 'init', 'prtfl_plugin_init' ); // add language file

add_action( 'init', 'prtfl_taxonomy_portfolio' ); // register taxonomy for portfolio
add_action( 'init', 'prtfl_post_type_portfolio' ); // register post type
add_action( 'init', 'prtfl_register_widget' ); // add widget for portfolio technologies
add_action( 'save_post', 'prtfl_save_postdata', 1, 2 ); // save custom data from admin 
add_filter( 'pre_get_posts', 'prtfl_technologies_get_posts' ); // display tachnologies taxonomy
add_action( 'template_redirect', 'prtfl_template_redirect' ); // add template for single gallery page
add_action( 'init', 'prtfl_custom_permalinks' ); // add custom permalink for portfolio


add_action( 'after_setup_theme', 'prtfl_add_template_in_new_theme' ); // add template in theme after activate new theme

//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'prtfl_register_plugin_links', 10, 2 );

add_shortcode('latest_portfolio_items', 'prtfl_latest_items');

add_action( 'admin_enqueue_scripts', 'prtfl_admin_head' );
add_action( 'wp_enqueue_scripts', 'prtfl_wp_head' );

?>