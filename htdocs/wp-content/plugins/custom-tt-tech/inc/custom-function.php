<?php 
/**
 *  
 * Allow Description category save HTML
 * 
 * */
add_action("after_setup_theme",'customCategory');

function customCategory(){

	foreach ( array( 'pre_term_description' ) as $filter ) {

		remove_filter( $filter, 'wp_filter_kses' );

	}

	foreach ( array( 'term_description' ) as $filter ) {

		remove_filter( $filter, 'wp_kses_data' );

	}

}

function my_custom_gallery_shortcode( $output = '', $atts, $instance ) {
	

	$return = $output; // fallback

	$my_result = '<div class="custom_gallery grid_gallery clearfix">
					<div class="grid_gallery_inner">';

	if( !empty($atts['ids']) ){
		foreach (explode(',',$atts['ids']) as $key => $id_image){
			
			$img_url = wp_get_attachment_url( $id_image,'full'); //get img URL

			$my_result .= '<figure class="gallery_item featured-thumbnail thumbnail single-gallery-item">
								<a href="'.wp_get_attachment_url($id_image).'" class="image-wrap" rel="prettyPhoto[gallery'. ($instance).']" title="'.get_the_title($id_image).'">
									<img style="background-image: url(\''.$img_url.'\')">
								<span class="zoom-icon"></span>
								</a>
								<p class="title-image">'.get_the_title($id_image).'</p>
							</figure>';
		}
	}

	$my_result .= '	</div>
				</div>';// End section custom gallery

	// boolean false = empty, see http://php.net/empty
	if( !empty( $my_result ) ) {
		$return = $my_result;
	}

	return $return;
}

add_filter( 'post_gallery', 'my_custom_gallery_shortcode', 10, 3 );

/*
<div class="caption caption__portfolio">
				
									<p><a href="http://perfectrent.tt-tech.ngotinh/portfolio-view/feugiat-vitae-leo/" class="btn btn-primary">More</a></p>
							</div>
*/