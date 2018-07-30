<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Education_LMS
 */

get_header();

$post_layout = esc_attr( get_theme_mod('post_layout', 'right-sidebar') );
$col = ( $post_layout == 'no-sidebar' ) ? 12 : 9;


$postid   = get_the_ID();
$author   = get_post_meta( $postid, '_lp_course_author' );
$course_author = !empty($author) ? $author[0] : 1;
$_lp_duration = get_post_meta($postid, '_lp_duration' ) ;

$items = wp_cache_get( 'course-' . $post->ID, 'lp-course-items' );

$items = wp_cache_get( 'course-' . $post->ID, 'lp-course-items' );
$number_lessons = $number_quizzes = 0;
if ( $items ) {
	foreach ( $items as $item_id ) {
		if ( get_post_type( $item_id ) == LP_LESSON_CPT ) {
			$number_lessons ++;
		} else if ( get_post_type( $item_id ) == LP_QUIZ_CPT ) {
			$number_quizzes ++;
		}
	}
}

$categories = get_the_terms( $postid, 'course_category' );
$on_draught = '';
if ( $categories && ! is_wp_error( $categories ) ) {
	$draught_links = array();

	foreach ( $categories as $category ) {
		$draught_links[] = $category->name;
	}

	$on_draught = join( ", ", $draught_links );
}
?>

	<div id="primary" class="content-area">
		<div class="container">
			<div class="row">

				<?php
				if ( $post_layout != 'no-sidebar' && $post_layout == 'left-sidebar' ) {
					get_sidebar();
				}
				?>

				<main id="main" class="site-main col-md-<?php echo intval($col) ?>">

					<div class="blog-content">

                        <div class="row">
                            <div class="col-md-10">
                                <div class="course-meta">
                                    <div class="course-author">
		                                <?php echo get_avatar( $course_author, 64 ); ?>
                                        <div class="author-contain">
                                            <label><?php echo esc_html__('Teacher', 'education-lms'); ?></label>
                                            <div class="value" ><?php echo esc_html( get_the_author_meta( 'display_name', $course_author ) ) ?></div>
                                        </div>
                                    </div>
                                    <div class="course-categories">
                                        <label><?php echo esc_html__('Category', 'education-lms'); ?></label>
                                        <div class="value"><?php if ( $on_draught ) { echo esc_html( $on_draught );  } ?></div>
                                    </div>

		                            <?php education_lms_course_ratings() ?>

                                </div>
                            </div>

                            <div class="col-md-2 course-payment text-right">
	                            <?php
	                            learn_press_course_price();
	                            ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 course-thumbnail">
                                <?php the_post_thumbnail('full'); ?>
                            </div>
                        </div>


                        <ul class="list-unstyled course-info row">
                            <li class="col-md-3 col-xs-6"><i class="fa fa-users"></i> <?php learn_press_course_students() ?></li>
                            <li class="col-md-3 col-xs-6"><?php echo sprintf('<i class="fa fa-file-text-o"></i> %1$s '. esc_html__('lessons', 'education-lms'), $number_lessons ); ?> </li>
                            <li class="col-md-3 col-xs-6"><?php echo sprintf('<i class="fa fa-question-circle-o"></i> %1$s '. esc_html__('quizzes', 'education-lms'), $number_quizzes ); ?> </li>
                            <li class="col-md-3 col-xs-6"><?php echo sprintf('<i class="fa fa-clock-o"></i> %1$s '. esc_html__('duration', 'education-lms'), $_lp_duration[0] ); ?></li>
                        </ul>


						<?php
						while ( have_posts() ) :
							the_post();

							the_content();

						endwhile; // End of the loop.
						?>
					</div>

				</main><!-- #main -->

				<?php
				if ( $post_layout != 'no-sidebar' && $post_layout == 'right-sidebar' ) {
					get_sidebar();
				}
				?>

			</div>
		</div>
	</div><!-- #primary -->

<?php

get_footer();
