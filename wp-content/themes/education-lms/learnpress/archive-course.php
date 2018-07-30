<?php
/**
 * archive course page
 * @package Education_LMS
 */
get_header();
?>

	<div id="primary" class="content-area">
		<div class="container">
			<div class="row">
				<main id="main" class="site-main col-md-9">
					<?php
					while ( have_posts() ) :
						the_post();

                        the_content();

					endwhile; // End of the loop.
					?>

				</main><!-- #main -->

                <?php get_sidebar(); ?>

			</div>
		</div>

	</div><!-- #primary -->

<?php

get_footer();
