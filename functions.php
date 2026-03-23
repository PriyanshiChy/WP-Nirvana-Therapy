<?php
// Require once so render_tag() is available everywhere.
require_once get_template_directory() . "/inc/component.php";
require_once get_template_directory() . "/inc/json.php";
// require_once get_template_directory() . "/inc/license.php";

function nirvana_create_core_pages() {
    // LANDING PAGE
    $landing_title = 'Nirvana Therapy';
    $landing_slug  = 'nirvana-therapy'; // <-- proper slug, no slash

    // Try to find existing page by slug
    $existing_landing = get_page_by_path( $landing_slug );

    if ( ! $existing_landing ) {
        $landing_content = '
            <h2>Welcome to Nirvana Therapy</h2>
            <p>Replace this with your real landing content.</p>
        ';

        $landing_id = wp_insert_post( array(
            'post_title'   => $landing_title,
            'post_name'    => $landing_slug,   // <-- valid slug
            'post_content' => $landing_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ) );

        if ( $landing_id && ! is_wp_error( $landing_id ) ) {
            // Assign custom template if it exists in your theme root
            update_post_meta( $landing_id, '_wp_page_template', 'page-landing.php' );
        }
    } else {
        $landing_id = $existing_landing->ID;
    }

    // TERMS PAGE
    $terms_title = 'Terms and Conditions';
    $terms_slug  = 'terms-and-conditions';

    $existing_terms = get_page_by_path( $terms_slug );

    if ( ! $existing_terms ) {
        $terms_content = '
            <h2>Terms and Conditions</h2>
            <p>Replace this with your legal terms.</p>
        ';

        $terms_id = wp_insert_post( array(
            'post_title'   => $terms_title,
            'post_name'    => $terms_slug,
            'post_content' => $terms_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ) );

        if ( $terms_id && ! is_wp_error( $terms_id ) ) {
            update_post_meta( $terms_id, '_wp_page_template', 'page-terms.php' );
        }
    }

    // Set front page to landing
    if ( isset( $landing_id ) && $landing_id ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $landing_id );
    }
}
add_action( 'after_switch_theme', 'nirvana_create_core_pages' );
