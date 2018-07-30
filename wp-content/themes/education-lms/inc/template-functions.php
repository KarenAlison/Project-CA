<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Education_LMS
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function education_lms_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if (  is_singular('post') ) {
		$post_layout = esc_attr( get_theme_mod( 'post_layout', 'right-sidebar' ) );
		$classes[]   = $post_layout;
	}



	return $classes;
}
add_filter( 'body_class', 'education_lms_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function education_lms_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'education_lms_pingback_header' );


function education_lms_search_form( $form ) {
	$form = '<form role="search" method="get" id="searchform" class="search-form" action="' . esc_url( home_url( '/' ) ) . '" >
    <label for="s">
    	<input type="text" value="' . get_search_query() . '" placeholder="' . esc_attr__( 'Search &hellip;', 'education-lms' ) . '" name="s" id="s" />
    </label>
    <button type="submit" class="search-submit">
        <i class="fa fa-search"></i>
    </button>
    </form>';
	return $form;
}
add_filter( 'get_search_form', 'education_lms_search_form' );

if ( !function_exists('education_lms_titlebar') ) {
	function education_lms_titlebar() {
		global $post;

		$blog_title      = get_theme_mod( 'blog_page_title', 'Blog' );
		$course_page     = absint( education_lms_get_setting( 'courses_page_id' ) );
		$hide_title_bar  = false;
		$hide_breadcrumb = false;
		$page_id         = $post->ID;

	

		if ( ! is_front_page() && ! $hide_title_bar ) {
			?>
            <div class="titlebar">
                <div class="container">

					<?php
					if ( is_home() || is_singular( 'post' ) ) {
						echo '<h2 class="header-title">' . esc_html( $blog_title ) . '</h2>';
					} elseif ( $course_page == $post->ID ) {
						the_title( '<h1 class="header-title">', '</h1>' );
					} elseif ( is_archive() ) {
						the_archive_title( '<h1 class="header-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
					} else the_title( '<h1 class="header-title">', '</h1>' )
					?>
                    <div class="triangled_colored_separator"></div>
                </div>
            </div>
			<?php
		}
		if ( ! is_front_page() && ! $hide_breadcrumb ) {
			education_lms_breadcrumb();
		}
	}
}
add_action('education_lms_header_titlebar', 'education_lms_titlebar');

/* Custom style */
function education_lms_custom_style(){

	$custom_css = '';

	$primary_color   = esc_attr( get_theme_mod( 'primary_color', '#ffb606' ) );

	$titlebar_bg   = esc_attr( get_theme_mod( 'titlbar_bg_color', '#457992' ) );
	$titlebar_pd_top = absint( get_theme_mod( 'padding_top', 5 ) );
	$titlebar_pd_bottom = absint( get_theme_mod( 'padding_botton', 5 ) );

	$custom_css .= "
	        button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"],
		    .titlebar .triangled_colored_separator,
		    .widget-area .widget-title::after,
		    .carousel-wrapper h2.title::after,
		    .course-item .course-thumbnail .price,
		    .site-footer .footer-social,
		    .single-lp_course .lp-single-course ul.learn-press-nav-tabs .course-nav.active, 
		    .single-lp_course .lp-single-course ul.learn-press-nav-tabs .course-nav:hover,
		    .widget_tag_cloud a:hover,
		    .header-top .header-contact-wrapper .btn-secondary
		     { background: $primary_color; }
		  
            a:hover, a:focus, a:active,
            .main-navigation a:hover,
            .entry-title a:hover,
            .main-navigation .current_page_item > a, .main-navigation .current-menu-item > a, .main-navigation .current_page_ancestor > a, .main-navigation .current-menu-ancestor > a,
            .entry-meta span i,
            .site-footer a:hover,
            .blog .entry-header .entry-date, .archive .entry-header .entry-date,
            .site-footer .copyright-area span,
            .breadcrumbs a:hover span,
            .carousel-wrapper .slick-arrow:hover:before,
            .recent-post-carousel .post-item .btn-readmore:hover,
            .recent-post-carousel .post-item .recent-news-meta span i,
            .recent-post-carousel .post-item .entry-title a:hover,
            .single-lp_course .course-info li i,
            .search-form .search-submit,
            .header-top .header-contact-wrapper li .box-icon i
            {
                color: $primary_color;
            }
		       
		     .recent-post-carousel .post-item .btn-readmore:hover,
		    .carousel-wrapper .slick-arrow:hover,
		    .single-lp_course .lp-single-course .course-curriculum ul.curriculum-sections .section-header,
		    .widget_tag_cloud a:hover,
            .readmore a:hover {
                border-color: $primary_color;
            }
		 
	";

	$page_display_cover = false;
	$header_image = get_header_image();
	$page_id = get_the_ID();



    if ( $page_display_cover ) {
	    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' );
	    $header_image = esc_url( $featured_image[0] );
    }

	$custom_css .= "
		 .titlebar { background-color: $titlebar_bg; padding-top: $titlebar_pd_top%; padding-bottom: $titlebar_pd_bottom%; background-image: url($header_image); background-repeat: no-repeat; background-size: cover; background-position: center center;  }
	";



	return $custom_css;
}


if ( !is_admin() ) {
	function education_lms_custom_excerpt_length( $length ) {
		return 50;
	}
	add_filter( 'excerpt_length', 'education_lms_custom_excerpt_length', 999 );
	function education_lms_excerpt_more( $more ) {
		return '&hellip;';
	}
	add_filter( 'excerpt_more', 'education_lms_excerpt_more' );

}

if ( is_admin() ) {
	function education_lms_remove_learnpress_ads() {
		?>
        <style>
            #learn-press-advertisement { display: none; }
        </style>
        <?php
	}
	add_action( 'admin_footer', 'education_lms_remove_learnpress_ads', 100 );

}


add_action( 'tgmpa_register', 'education_lms_register_required_plugins' );
function education_lms_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'      => esc_html__('Elementor', 'education-lms'),
			'slug'      => 'elementor',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('LearnPress - WordPress LMS Plugin', 'education-lms'),
			'slug'      => 'learnpress',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('LearnPress - Course Review', 'education-lms'),
			'slug'      => 'learnpress-course-review',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('One Click Demo Import', 'education-lms'),
			'slug'      => 'one-click-demo-import',
			'required'  => false,
		),


	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'education-lms',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.


	);

	tgmpa( $plugins, $config );
}

function education_lms_ocdi_import_files() {
	return array(
		array(
			'import_file_name'             => 'Education LMS Demo Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'assets/dummy-data/demo-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'assets/dummy-data/widgets.wie',
			'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'assets/dummy-data/customizer.dat'
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'education_lms_ocdi_import_files' );

function education_lms_footer_info() {

   
	    echo sprintf( esc_html__( 'Copyright &copy; %1$s %2$s - %3$s theme by %4$s', 'education-lms' ), date_i18n( __( 'Y', 'education-lms' ) ), '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '">' . esc_html( get_bloginfo( 'name', 'display' ) ) . '</a>', '<a target="_blank" href="https://www.filathemes.com/downloads/education-lms">Education LMS</a>', '<span>FilaThemes</span>' );
    
}
add_action( 'education_lms_footer_copyright', 'education_lms_footer_info' );


if ( !function_exists('education_lms_header_1') ) {
	function education_lms_header_1() {
		$show_topbar   = esc_attr( get_theme_mod( 'show_topbar', '1' ) );
		$show_login    = esc_attr( get_theme_mod( 'show_login', '1' ) );
		$show_register = esc_attr( get_theme_mod( 'show_register', '1' ) );
		$show_logout   = esc_attr( get_theme_mod( 'show_logout', '1' ) );
		if ( $show_topbar != 1 ) {
			?>
            <div class="topbar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 text-left">
							<?php if ( is_active_sidebar( 'topbar-left' ) ) : dynamic_sidebar( 'topbar-left' ); endif; ?>
                        </div>
                        <div class="col-sm-6 text-right hidden-xs">
							<?php if ( is_active_sidebar( 'topbar-right' ) ) : dynamic_sidebar( 'topbar-right' ); endif; ?>
                            <div class="header_login_url">
								<?php if ( ! is_user_logged_in() ) { ?>
									<?php if ( $show_login != 1 ) { ?>
                                        <a href="<?php echo esc_url( get_theme_mod( 'login_url' ) ) ?>"><i
                                                    class="fa fa-user"></i><?php echo esc_html__( 'Login', 'education-lms' ) ?>
                                        </a>
                                        <span class="vertical_divider"></span>
									<?php } ?>
									<?php if ( $show_register != 1 ) { ?>
                                        <a href="<?php echo esc_url( get_theme_mod( 'register_url' ) ) ?>"><?php echo esc_html__( 'Register', 'education-lms' ) ?></a>
									<?php } ?>
								<?php } else {
									if ( $show_logout != 1 ) {
										echo '<a href="' . esc_url( wp_logout_url( home_url() ) ) . '">' . esc_html__( 'Logout', 'education-lms' ) . ' <i class="fa fa-sign-out"></i> </a>';
									}
								} ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?php } ?>

        <div class="header-default">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-lg-4">
                        <div class="site-branding">

                            <div class="site-logo">
								<?php the_custom_logo(); ?>
                            </div>

                            <div>
								<?php
								if ( is_front_page() || is_home() ) :
									?>
                                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                                              rel="home"><?php bloginfo( 'name' ); ?></a></h1>
									<?php
								else :
									?>
                                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                                             rel="home"><?php bloginfo( 'name' ); ?></a></p>
									<?php
								endif;
								$education_lms_description = get_bloginfo( 'description', 'display' );
								if ( $education_lms_description || is_customize_preview() ) :
									?>
                                    <p class="site-description"><?php echo $education_lms_description; /* WPCS: xss ok. */ ?></p>
								<?php endif; ?>
                            </div>

                        </div><!-- .site-branding -->
                    </div>

                    <div class="col-lg-8 pull-right">
                        <a href="#" id="mobile-open"><i class="fa fa-bars"></i></a>
                        <nav id="site-navigation" class="main-navigation">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'pull-right'
							) );
							?>
                        </nav><!-- #site-navigation -->
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}

    function education_lms_header_2() {
        ?>
        <div class="header-type2">
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 col-lg-4">
                            <div class="site-branding">

                                <div class="site-logo">
				                    <?php the_custom_logo(); ?>
                                </div>

                                <div>
				                    <?php
				                    if ( is_front_page() || is_home() ) :
					                    ?>
                                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                                                  rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					                    <?php
				                    else :
					                    ?>
                                        <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                                                                 rel="home"><?php bloginfo( 'name' ); ?></a></p>
					                    <?php
				                    endif;
				                    $education_lms_description = get_bloginfo( 'description', 'display' );
				                    if ( $education_lms_description || is_customize_preview() ) :
					                    ?>
                                        <p class="site-description"><?php echo $education_lms_description; /* WPCS: xss ok. */ ?></p>
				                    <?php endif; ?>
                                </div>

                            </div><!-- .site-branding -->
                        </div>

                        <div class="col-lg-8 col-xs-12 pull-right">
                            <?php dynamic_sidebar('header-right'); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="header-bottom">
                <div class="container">
                    <a href="#" id="mobile-open"><i class="fa fa-bars"></i></a>
                    <nav id="site-navigation" class="main-navigation">
		                <?php
		                wp_nav_menu( array(
			                'theme_location' => 'menu-1',
			                'menu_id'        => 'primary-menu'
		                ) );
		                ?>
                    </nav><!-- #site-navigation -->

                    <div class="header-socials">
                        <?php education_lms_social_media(); ?>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }
