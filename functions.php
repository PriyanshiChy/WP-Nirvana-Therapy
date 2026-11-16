<?php
defined( 'ABSPATH' ) || exit;

require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/ui.php';
require_once get_template_directory() . '/inc/actions.php';
require_once get_template_directory() . '/inc/booking-popup.php';
require_once get_template_directory() . '/inc/therapists.php';
require_once get_template_directory() . '/inc/rich-text.php';
require_once get_template_directory() . '/inc/blocks.php';

/**
 * On theme activation, creates the Landing, Terms and Therapists pages if
 * they don't exist yet and makes the landing page the front page.
 *
 * The landing page starts empty, so the block editor opens it with all eight
 * sections ready to fill in (see nirvana_landing_template()). To import the
 * original content instead, run tools/seed-content.php (see that file).
 */
function nirvana_create_core_pages(): void {
	$landing = get_page_by_path( 'nirvana-therapy' );
	if ( ! $landing ) {
		$landing_id = wp_insert_post(
			array(
				'post_title'    => 'Nirvana Therapy',
				'post_name'     => 'nirvana-therapy',
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'page_template' => 'page-landing.php',
			)
		);
	} else {
		$landing_id = $landing->ID;
	}

	if ( ! get_page_by_path( 'terms-and-conditions' ) ) {
		wp_insert_post(
			array(
				'post_title'   => 'Terms and Conditions',
				'post_name'    => 'terms-and-conditions',
				'post_content' => "<!-- wp:paragraph -->\n<p>Write your terms and conditions here.</p>\n<!-- /wp:paragraph -->",
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	}

	if ( ! get_page_by_path( 'therapists' ) ) {
		wp_insert_post(
			array(
				'post_title'    => 'Therapists',
				'post_name'     => 'therapists',
				'post_content'  => "<!-- wp:paragraph -->\n<p>Introduce the team here.</p>\n<!-- /wp:paragraph -->",
				'post_status'   => 'publish',
				'post_type'     => 'page',
				'page_template' => 'page-therapists.php',
			)
		);
	}

	if ( $landing_id && ! is_wp_error( $landing_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $landing_id );
	}
}
add_action( 'after_switch_theme', 'nirvana_create_core_pages' );

/**
 * Makes /therapists/<name>/ work right after activation.
 */
add_action( 'after_switch_theme', 'flush_rewrite_rules', 20 );
