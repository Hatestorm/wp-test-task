<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package unite-child
 */
?>
	<div id="secondary" class="widget-area col-12 col-md-2" role="complementary">
		<?php
			do_action( 'before_sidebar' );
			$agencies = get_posts(array( 'post_type'=>'agency', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));
			if ($agencies) :
				echo '<ul class="agency-list list-unstyled">';
				foreach( $agencies as $agency ){
					echo '<li class="agency-list__item"><a href="#" data-id="'.$agency->ID.'" class="agency-link">' . $agency->post_title . '</a></li>';
				}
				echo '<li class="agency-list__item"><a href="#" data-id="'.null.'" class="agency-link">All Agencies</a></li></ul>';
			else :
				echo 'No agencies found';
			endif;
		?>
		
	</div><!-- #secondary -->
