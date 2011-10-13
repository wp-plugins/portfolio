<?php
/*
Template Name: Portfolio template
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
			
			<?php global $post;
		global $display_script;

		global $wp_query;
		if( 'portfolio' == $post->post_type ) {
			$content = "";
			if( is_null( $display_script ) ) {
				$content .= "<script type='text/javascript'>".
				"var base_url = '". WP_PLUGIN_URL ."/portfolio';".
				"jQuery(document).ready(function(){".
						"jQuery('a[rel=\"lightbox\"]').colorbox({transition:'fade'});".
					"});".
				"</script>";
				$display_script = true;
			} ?>

			<div class="portfolio_content">
			<div class="item_title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></div>
			<div class="entry">
			
			<?php // Get meta value for post
			$meta_values = get_post_custom( $post->ID );		
			
			$thumb			= array();
			$images			= array();
			$upload_dir = wp_upload_dir();
			$image_alt	= "";
			$thumb_url	=	"";
			$featured_image_url = "";
			$thumb_featured_image_url = "";
			
			// If isset featured images, display this value 
			if( array_key_exists( '_thumbnail_id', $meta_values ) ) {
				$thumb			= wp_get_attachment_metadata( $meta_values['_thumbnail_id'][0] );
				$thumb_url	= $upload_dir["baseurl"] ."/". substr($thumb['file'], 0, 8) . $thumb['sizes']['medium']['file'];
				$featured_image_url = $upload_dir["baseurl"] ."/". $thumb["file"];
			}
			
			// Display all images from post gallery
			$post_attachments = get_posts( 'post_type=attachment&post_parent='. $post->ID );
			$count = 0;
			foreach($post_attachments as $attachment) {
				$images[$count]['metadata'] = wp_get_attachment_metadata( $attachment->ID );
				$images[$count]['alldata']	= $attachment;
				$count++;
			}
			
			// If not isset featured images, display one image from the gallery
			if( 0 == count( $thumb ) ) {
				if( 0 < count( $images ) ) {
					$thumb_url					= ( isset( $images[0]['metadata']['sizes']["medium"]['file'] ) ? $upload_dir["baseurl"] ."/". substr($images[0]["metadata"]["file"], 0, 8) . $images[0]['metadata']['sizes']["medium"]['file'] : $images[0]['alldata']->guid );
					$featured_image_url = $upload_dir["baseurl"] ."/". $images[0]['metadata']["file"];
					$image_alt					= get_post_custom( $images[0]['alldata']->ID );
					$image_alt					= $image_alt["_wp_attachment_image_alt"][0];
				}
				else {
					$thumb_url					= "";
					$featured_image_url = "";
					$image_alt					= "";
				}
			}

			$thumb_featured_image_url = $thumb_url;
			
			// Create html for display portfolio post
			$content .= '<p><a class="lightbox" rel="lightbox" href="'. $featured_image_url .'"><img src="'. $thumb_url .'" width="240" /></a></p>';
			$content .= '<p><span class="lable">Date of completion</span>: '. $meta_values["_prtf_date_compl"][0] .'</p>';
			$user_id = get_current_user_id();
			if ( 0 == $user_id ) {
				$content .= '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			else {
				if( false !== parse_url( $meta_values["_prtf_link"][0] ) )
					$content .= '<p><span class="lable">Link</span>: <a href="'. $meta_values["_prtf_link"][0] .'">'. $meta_values["_prtf_link"][0] .'</a></p>';
				else
					$content .= '<p><span class="lable">Link</span>: '. $meta_values["_prtf_link"][0] .'</p>';
			}
			$content .= '<p><span class="lable">Description</span>: '. $meta_values["_prtf_descr"][0] .'</p>';
			if ( 0 != $user_id ) {
				$executors_profile = wp_get_object_terms( $post->ID, 'portfolio_executor_profile' );

				$content .= '<p><span class="lable">SVN</span>: '. $meta_values["_prtf_svn"][0] .'</p>';
				$content .= '<p><span class="lable">Executors Profile</span>: ';

				$count = 0;
				foreach($executors_profile as $profile) {
					if($count > 0)
						$content .= ', ';
					$content .= '<a href="'. $profile->description .'" title="'. $profile->name .' profile" target="_blank">'. $profile->name .'</a>';
					$count++;
				}
				$content .= '</p>';
			}
			$content .= '<p class="portfolio_images_block">';
			
			$count = 0;
			// Display images from gallery
			for( $i = 0; $i < count( $images ); $i++ ) {
				$thumb_url = $upload_dir["baseurl"] ."/". substr($images[$i]["metadata"]["file"], 0, 8) . $images[$i]['metadata']['sizes']["medium"]['file'];
				if( $thumb_featured_image_url == $thumb_url)
					continue;
				$image_url = $images[$i]['alldata']->guid;

				if( ! isset( $images[$i]['metadata']['sizes']["medium"]['file'] ) )
					$thumb_url = $image_url;

				$images_alt = get_post_custom( $images[$i]['alldata']->ID );
				$images_alt = $images_alt["_wp_attachment_image_alt"][0];

				if( 0 == $count )
					$content .= "<span class=\"lable\">More screnshots</span>: <div class=\"portfolio_images_rows\">";

				$content .= '<div class="portfolio_images_gallery"><a class="lightbox" rel="lightbox" href="'. $image_url .'" title="'. $images[$i]['alldata']->post_title .'"><img src="'. $thumb_url .'" width="240" alt="'. $images_alt .'" /></a><br />'. $images[$i]['alldata']->post_content .'</div>';
				$count++;

				if( 0 == $count % 3 && 0 != $count ) {
					$content .= '</div><div class="portfolio_images_rows">';
				}
			}
			if( 0 < $count )
				$content .= '</div>';
			$content .= '</p>';
			
			// Display post tag - technologies
			$tags = wp_get_object_terms( $post->ID, 'portfolio_technologies' ) ;
			
			if ( $tags ) {
				if( 0 < count( $tags ) )
					$content .= '<div class="portfolio_terms">Technologies: ';
				foreach ( $tags as $tag ) {
					$url = get_term_link($tag->slug, 'portfolio_technologies');
					if( false !== $pos = strpos( $url, "?" ) )
					{
						$url = substr($url, 0, $pos+1)."post_type=portfolio&".substr($url, $pos+1);
					}
					
					$content .= '<a href="'. $url . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . $tag->name.'</a>, ';
				}
				$content = substr( $content, 0, strlen( $content ) -2 );
				if( 0 < count ( $tags ) )
					$content .= '</div>';
			}
		}
		echo $content;?>
			</div>
			</div>
			</div><!-- #content -->

			<?php portfolio_pagination(); ?>

		</div><!-- #container -->
		<div id="jquery-overlay"></div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>