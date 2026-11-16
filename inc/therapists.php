<?php
/**
 * Therapists: the "Therapists" post type in wp-admin, its Languages and
 * Expertise tags, and the helpers the listing and profile templates use.
 *
 * Each therapist is a post at /therapists/<name>/ (its profile page, the
 * "View Profile" link). The listing is a normal page using the "Therapists"
 * template (page-therapists.php). Fields: acf-json/group_nirvana_therapist.json.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'nirvana_register_therapists' );
function nirvana_register_therapists(): void {
	register_post_type(
		'therapist',
		array(
			'labels'        => array(
				'name'               => __( 'Therapists', 'nirvana-therapy' ),
				'singular_name'      => __( 'Therapist', 'nirvana-therapy' ),
				'add_new_item'       => __( 'Add New Therapist', 'nirvana-therapy' ),
				'edit_item'          => __( 'Edit Therapist', 'nirvana-therapy' ),
				'new_item'           => __( 'New Therapist', 'nirvana-therapy' ),
				'view_item'          => __( 'View Profile', 'nirvana-therapy' ),
				'search_items'       => __( 'Search Therapists', 'nirvana-therapy' ),
				'not_found'          => __( 'No therapists found.', 'nirvana-therapy' ),
				'all_items'          => __( 'All Therapists', 'nirvana-therapy' ),
				'featured_image'     => __( 'Photo', 'nirvana-therapy' ),
				'set_featured_image' => __( 'Set photo', 'nirvana-therapy' ),
			),
			'public'        => true,
			'has_archive'   => false, // The listing is a page (page-therapists.php), so its intro is editable.
			'rewrite'       => array(
				'slug'       => 'therapists',
				'with_front' => false,
			),
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 21,
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'revisions' ),
		)
	);

	$taxonomies = array(
		'therapist_language'  => array( __( 'Languages', 'nirvana-therapy' ), __( 'Language', 'nirvana-therapy' ), 'language' ),
		'therapist_expertise' => array( __( 'Expertise', 'nirvana-therapy' ), __( 'Expertise', 'nirvana-therapy' ), 'expertise' ),
	);

	foreach ( $taxonomies as $taxonomy => [ $plural, $singular, $slug ] ) {
		register_taxonomy(
			$taxonomy,
			'therapist',
			array(
				'labels'            => array(
					'name'          => $plural,
					'singular_name' => $singular,
					'add_new_item'  => sprintf( /* translators: %s: Language / Expertise. */ __( 'Add New %s', 'nirvana-therapy' ), $singular ),
				),
				'public'            => false, // No archive pages; they only label therapists.
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'hierarchical'      => false,
				'rewrite'           => false,
				'query_var'         => false,
			)
		);
	}
}

add_action( 'after_setup_theme', 'nirvana_therapist_images' );
function nirvana_therapist_images(): void {
	add_theme_support( 'post-thumbnails', array( 'therapist' ) );
	add_image_size( 'therapist-card', 292, 292, true );    // 146px card photo at 2x.
	add_image_size( 'therapist-profile', 640, 640, true ); // Profile photo at 2x.
}

/**
 * Therapists in the order set in each one's "Order" box, then by name.
 *
 * @return WP_Post[]
 */
function nirvana_get_therapists(): array {
	return get_posts(
		array(
			'post_type'      => 'therapist',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);
}

/**
 * Term names of a therapist's taxonomy, in the order they were added.
 *
 * @return string[]
 */
function nirvana_therapist_terms( WP_Post $therapist, string $taxonomy ): array {
	$terms = get_the_terms( $therapist, $taxonomy );

	return is_array( $terms ) ? wp_list_pluck( $terms, 'name' ) : array();
}

/**
 * A text field of the therapist (raw: escape where printed).
 */
function nirvana_therapist_field( WP_Post $therapist, string $name ): string {
	return function_exists( 'get_field' ) ? trim( (string) get_field( $name, $therapist->ID, false ) ) : '';
}

function nirvana_therapist_role( WP_Post $therapist ): string {
	return nirvana_therapist_field( $therapist, 'role' );
}

/**
 * The line above the profile's Book button: the "Line above the Book
 * button" field, else "Book a session with <first name>!".
 */
function nirvana_therapist_booking_prompt( WP_Post $therapist ): string {
	$prompt = nirvana_therapist_field( $therapist, 'booking_prompt' );
	if ( '' !== $prompt ) {
		return $prompt;
	}

	$first = strtok( get_the_title( $therapist ), ' ' );

	/* translators: %s: therapist's first name. */
	return sprintf( __( 'Book a session with %s!', 'nirvana-therapy' ), (string) $first );
}

/**
 * Profile page banner: the therapist's Banner image, else the default in
 * Site Settings → Therapists, else the theme's background.
 */
function nirvana_therapist_banner_url( WP_Post $therapist ): string {
	$ids = array(
		function_exists( 'get_field' ) ? (int) get_field( 'banner', $therapist->ID ) : 0,
		(int) nirvana_setting( 'therapist_background', 0 ),
	);

	foreach ( $ids as $id ) {
		$url = $id ? (string) wp_get_attachment_image_url( $id, 'full' ) : '';
		if ( '' !== $url ) {
			return $url;
		}
	}

	return nirvana_asset_uri( '/images/bg/tnc.webp' );
}

/**
 * Language tags. Colours alternate mint / peach. $size 'large' is the
 * profile's (14px, centred).
 */
function nirvana_therapist_language_tags( WP_Post $therapist, string $size = 'default' ): string {
	$large = 'large' === $size;
	$html  = '';
	foreach ( nirvana_therapist_terms( $therapist, 'therapist_language' ) as $i => $name ) {
		$html .= sprintf(
			'<li class="therapist-tag%s %s">%s</li>',
			$large ? ' therapist-tag-lg' : '',
			0 === $i % 2 ? 'bg-mint' : 'bg-peach',
			esc_html( $name )
		);
	}

	if ( '' === $html ) {
		return '';
	}

	return sprintf(
		'<ul class="flex flex-wrap gap-2%s" aria-label="%s">%s</ul>',
		$large ? ' justify-center' : '',
		esc_attr__( 'Languages', 'nirvana-therapy' ),
		$html
	);
}

/**
 * "Couple Therapy・Relationship issues・Anxiety".
 */
function nirvana_therapist_expertise( WP_Post $therapist ): string {
	return implode( '・', nirvana_therapist_terms( $therapist, 'therapist_expertise' ) );
}

/**
 * The therapist's photo, or a tinted square when none is set.
 */
function nirvana_therapist_photo( WP_Post $therapist, string $size, string $class ): string {
	if ( has_post_thumbnail( $therapist ) ) {
		$id = get_post_thumbnail_id( $therapist );

		return wp_get_attachment_image(
			$id,
			$size,
			false,
			array(
				'class' => $class . ' object-cover',
				'alt'   => get_post_meta( $id, '_wp_attachment_image_alt', true ) ?: get_the_title( $therapist ),
			)
		);
	}

	return '<div class="' . esc_attr( $class ) . ' bg-primary/15" aria-hidden="true"></div>';
}

/**
 * The therapist's "Book a Session" button. Its action comes from the
 * therapist's own Booking fields, or Site Settings (see nirvana_booking_args()).
 */
function nirvana_therapist_book_button( WP_Post $therapist, array $args = array() ): string {
	return nirvana_get_button(
		array_merge(
			nirvana_booking_args( $therapist ),
			array(
				/* translators: %s: therapist name. */
				'aria_label' => sprintf( __( 'Book a session with %s', 'nirvana-therapy' ), get_the_title( $therapist ) ),
			),
			$args
		)
	);
}
