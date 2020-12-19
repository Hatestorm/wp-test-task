<?php

    if ( get_option( 'show_on_front' ) == 'posts' ) {
        get_template_part( 'index' );
    } elseif ( 'page' == get_option( 'show_on_front' ) ) {

 get_header(); ?>

	<div id="primary" class="content-area col-12 col-md-10">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content mb-3">
						<div class="filtered-content row">
							<?php 
								$real_estate = get_posts(array( 'post_type'=>'real_estate', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));
								if($real_estate) :
									foreach ($real_estate as $post) { ?>	
									<div class="card-container col-sm-3">
										<div class="card">
										  <?php the_post_thumbnail( 'unite-featured', array( 'class' => 'card-img-top' )) ?>
										  <div class="card-body">
											<h5 class="card-title"><?php echo $post->post_title; ?></h5>
											<?php echo get_real_estate_info( $post->ID ); ?>
											<a href="<?php echo get_post_permalink()?>" class="btn btn-primary">Show more</a>
										  </div>
										</div>
									</div>
								<?php 
								  } 
								else : ?>
									<p>No Real Estate Found</p>
							<?php 
								endif;
							?>
						</div>
						<?php
							wp_link_pages( array(
								'before' => '<div class="page-links">' . __( 'Pages:', 'unite' ),
								'after'  => '</div>',
							) );
						?>
					</div><!-- .entry-content -->
					<?php edit_post_link( __( 'Edit', 'unite' ), '<footer class="entry-meta"><i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span></footer>' ); ?>
				</article><!-- #post-## -->

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	get_sidebar('front_page');
	get_footer();
}
?>