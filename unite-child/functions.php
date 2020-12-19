<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'bootstrap','unite-icons' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION


// ENQUEUE SCRIPTS
add_action( 'wp_enqueue_scripts', 'wp_ajax_enqueue' );
function wp_ajax_enqueue(){
	wp_enqueue_script(
		'filter', 
		get_stylesheet_directory_uri() . '/js/filter.js', 
		array( 'jquery' )
	);
	
	wp_localize_script(
        'filter', 
        'ajax_obj', 
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ), // Элемент массива, содержащий путь к admin-ajax.php
            'nonce' => wp_create_nonce('wplb-nonce') 
        )
    );
	
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/style.css');
}

add_action('add_meta_boxes', function () {
	add_meta_box( 'agency', 'Agency', 'agency_metabox', 'real_estate', 'side', 'low'  );
}, 1);

// METABOX WITH AGENCY SELECTOR FOR REAL_ESTATE POST TYPE
function agency_metabox( $post ){
	$agencies = get_posts(array( 'post_type'=>'agency', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));

	if( $agencies ){

		echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';

		foreach( $agencies as $agency ){
			echo '
			<li><label>
				<input type="radio" name="post_parent" value="'. $agency->ID .'" '. checked($agency->ID, $post->post_parent, 0) .'> '. esc_html($agency->post_title) .'
			</label></li>
			';
		}

		echo '
			</ul>
		</div>';
	}
	else
		echo 'No Agencies...';
}

// GET AND CACHE REAL ESTATE META FIELDS
function get_real_estate_info( $post_id ){
	
	$cached_fields = get_transient('fields-' . $post_id);
	
	if ($cached_fields === false) {
		$fields = get_fields($post_id);
		set_transient( 'fields-' . $post_id, $fields, 12 * HOUR_IN_SECONDS );
	}
	
	$out = "<ul class='entry-data list-unstyled'>
		<li>Area: <span>" . $cached_fields['area'] . "</li>
		<li>Cost: <span>" . $cached_fields['cost'] . "</li>
		<li>Address: <span>" . $cached_fields['address'] . "</li>
		<li>Living Area: <span>" . $cached_fields['living_area'] . "</li>
		<li>Floor: <span>" . $cached_fields['floor'] . "</li>
		</ul>";

	return $out;
}

//FILTER REAL ESTATE BY AGENCIES
add_action('wp_ajax_filter', 'filter_by_agency');
add_action('wp_ajax_nopriv_filter', 'filter_by_agency');

function filter_by_agency() {
	$agency_id = $_POST['id'];
	
	if ($agency_id == null) {
		$real_estate = get_posts(array( 'post_type'=>'real_estate', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));
	} else {
		$real_estate = get_posts(array( 'post_type'=>'real_estate', 'post_parent'=>$agency_id, 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));
	}
	
	$html= "";
	
	if($real_estate) :
		foreach ($real_estate as $post) { 	
			
			$html .= "<div class='card-container col-sm-3'><div class='card'>" . get_the_post_thumbnail($post->ID, 'unite-featured', array( 'class' => 'card-img-top' )). "<div class='card-body'><h5 class='card-title'>". $post->post_title."</h5>".get_real_estate_info($post->ID)."<a href='".get_post_permalink($post->ID)."' class='btn btn-primary'>Show more</a></div></div></div>";
		}
	else :
		$html .= "No Real Estate found";
	endif;
	
	echo $html;
 
	die;
}
