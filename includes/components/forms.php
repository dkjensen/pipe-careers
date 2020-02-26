<?php
/**
 * Pipe_Careers_Setup class file
 * 
 * @package Pipe Careers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter the forms page title
 *
 * @param string $title
 * @return string
 */
function pipecareers_forms_title( $title ) {
    if ( get_query_var( 'forms' ) ) {
        return $title . ' ' . __( 'Forms' );
    }
    
    return $title;
}
add_filter( 'the_title', 'pipecareers_forms_title' );

/**
 * Filter the forms page content
 *
 * @param string $content
 * @return string
 */
function pipecareers_forms_content( $content ) {
    if ( get_query_var( 'forms' ) ) {
        if ( function_exists( 'have_rows' ) && have_rows( 'forms' ) ) {
            ob_start();

            while ( have_rows( 'forms' ) ) { the_row(); 
            ?>

                <h5 style="margin-bottom: 0;"><?php the_sub_field( 'heading' ); ?></h5>
                <p><a href="<?php the_sub_field( 'file_url' ); ?>" target="_blank" rel="nofollow"><?php the_sub_field( 'file_url' ); ?></a></p>

                <?php if ( get_sub_field( 'description' ) ) { ?>

                <p class="description" style="margin-top: -1em;"><small><?php the_sub_field( 'description' ); ?></small></p>

                <hr>

                <?php } ?>

            <?php
            }

            return ob_get_clean();
        }
    }

    return $content;
}
add_filter( 'the_content', 'pipecareers_forms_content' );

/**
 * Register our forms query var
 *
 * @param array $vars
 * @return array
 */
function pipecareers_forms_query_vars( $vars ) {
    $vars[] = 'forms';

    return $vars;
}
add_filter( 'query_vars', 'pipecareers_forms_query_vars' );

/**
 * Set the query var if we are on a forms page
 *
 * @return void
 */
function pipecareers_forms_query_var_check() {
    if ( substr( trailingslashit( $_SERVER['REQUEST_URI'] ), -7 ) === '/forms/' ) {
        set_query_var( 'forms', get_queried_object_id() );
    }
}
add_action( 'wp', 'pipecareers_forms_query_var_check' );

/**
 * Do not index forms pages
 *
 * @param array $meta
 * @return array
 */
function pipecareers_forms_seo_robots_meta( $meta ) {
	if ( get_query_var( 'forms' ) ) {
		$meta['noindex'] = 'noindex';
		$meta['nofollow'] = 'nofollow';
	}

	return $meta;
}
add_filter( 'the_seo_framework_robots_meta_array', 'pipecareers_forms_seo_robots_meta');