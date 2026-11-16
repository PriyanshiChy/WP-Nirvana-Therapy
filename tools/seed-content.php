<?php
/**
 * One-time import of tools/content.json (the theme's original content) into
 * WordPress. The theme itself no longer reads it.
 *
 * Run from Local's "Open Site Shell":
 *   wp eval-file wp-content/themes/nirvana-therapy/tools/seed-content.php
 *   wp eval-file wp-content/themes/nirvana-therapy/tools/seed-content.php force   # overwrite
 *
 * Without "force", anything already imported is left alone.
 *
 * Imports: Site Settings, menus, the landing page blocks, the terms page.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}

if ( ! function_exists( 'update_field' ) ) {
	WP_CLI::error( 'Secure Custom Fields is not active.' );
}

$force   = in_array( 'force', $args ?? array(), true );
$content = json_decode( (string) file_get_contents( __DIR__ . '/content.json' ), true );

if ( empty( $content['components'] ) ) {
	WP_CLI::error( 'tools/content.json is missing or empty.' );
}

$components = $content['components'];

// ---------------------------------------------------------------------------
// Site Settings
// ---------------------------------------------------------------------------

if ( get_option( 'nirvana_seeded_settings' ) && ! $force ) {
	WP_CLI::log( 'Site Settings already imported. Skipping (pass "force" to overwrite).' );
} else {
	$buttons  = $components['buttons'] ?? array();
	$sections = $components['footer']['sections'] ?? array();
	$address  = $sections[1] ?? array();
	$socials  = $sections[3]['socials'] ?? array();
	$contacts = $sections[4]['contacts'] ?? array();

	$phone = '';
	$email = '';
	foreach ( $contacts as $item ) {
		if ( str_starts_with( $item['href'] ?? '', 'tel:' ) ) {
			$phone = $item['label'];
		} elseif ( str_starts_with( $item['href'] ?? '', 'mailto:' ) ) {
			$email = $item['label'];
		}
	}

	$social_rows = array();
	foreach ( $socials as $social ) {
		$network = basename( $social['icon'] ?? '', '.svg' );
		if ( isset( nirvana_social_networks()[ $network ] ) ) {
			$social_rows[] = array(
				'field_nv_social_network' => $network,
				'field_nv_social_url'     => $social['href'],
			);
		}
	}

	$copyright = $components['footer']['copyright'] ?? '';
	$copyright = preg_replace( '/\b(19|20)\d{2}\b/', '{year}', $copyright, 1 );

	$values = array(
		'field_nv_booking_label'  => $buttons['book-session']['label'] ?? '',
		'field_nv_booking_url'    => $buttons['book-session']['href'] ?? '',
		'field_nv_whatsapp_label' => $buttons['contact-us']['label'] ?? '',
		'field_nv_whatsapp_url'   => $buttons['contact-us']['href'] ?? '',
		'field_nv_reviews_label'  => $buttons['read-reviews']['title'] ?? '',
		'field_nv_reviews_url'    => $buttons['read-reviews']['href'] ?? '',
		'field_nv_phone'          => $phone,
		'field_nv_email'          => $email,
		'field_nv_address'        => $address['text'] ?? '',
		'field_nv_maps_label'     => $address['button']['title'] ?? '',
		'field_nv_maps_url'       => $address['button']['href'] ?? '',
		'field_nv_socials'        => $social_rows,
		'field_nv_footer_closing' => $components['footer']['closing'] ?? '',
		'field_nv_copyright'      => $copyright,
	);

	foreach ( $values as $key => $value ) {
		update_field( $key, $value, 'option' );
	}

	// Tagline: the second half of the old landing <title>, so the title stays
	// "Nirvana Therapy | Online Therapy & Mental Wellness in India".
	$head_title = explode( ' | ', (string) ( $content['index']['head']['title'] ?? '' ), 2 );
	if ( isset( $head_title[1] ) ) {
		update_option( 'blogdescription', $head_title[1] );
	}

	update_option( 'nirvana_seeded_settings', time(), false );
	WP_CLI::success( sprintf( 'Imported %d Site Settings fields.', count( $values ) ) );
}

// ---------------------------------------------------------------------------
// Menus
// ---------------------------------------------------------------------------

/**
 * Menu item args for a content.json link. Links to a page become page items,
 * so they follow the page if its slug changes; anchors stay custom links.
 */
$nirvana_menu_item = static function ( array $link ): array {
	$href = $link['href'] ?? '';
	$path = trim( (string) wp_parse_url( $href, PHP_URL_PATH ), '/' );
	$page = ( '' !== $path && ! str_contains( $href, '#' ) ) ? get_page_by_path( $path ) : null;

	if ( $page ) {
		return array(
			'menu-item-title'     => $link['label'],
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
		);
	}

	return array(
		'menu-item-title'  => $link['label'],
		'menu-item-url'    => $href,
		'menu-item-type'   => 'custom',
		'menu-item-status' => 'publish',
	);
};

$menus = array(
	'primary'        => array( 'Header', $components['navbar']['links'] ?? array() ),
	'footer-explore' => array( 'Footer – Explore', $components['footer']['sections'][0]['links'] ?? array() ),
	'footer-legal'   => array( 'Footer – Legal', $components['footer']['sections'][2]['links'] ?? array() ),
);

$locations = get_theme_mod( 'nav_menu_locations', array() );

foreach ( $menus as $location => list( $name, $links ) ) {
	$menu = wp_get_nav_menu_object( $name );

	if ( $menu && ! $force ) {
		WP_CLI::log( "Menu \"{$name}\" already exists. Skipping." );
		$menu_id = $menu->term_id;
	} else {
		if ( $menu ) {
			wp_delete_nav_menu( $menu->term_id );
		}

		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			WP_CLI::warning( "Could not create menu \"{$name}\": " . $menu_id->get_error_message() );
			continue;
		}

		foreach ( $links as $position => $link ) {
			wp_update_nav_menu_item( $menu_id, 0, $nirvana_menu_item( $link ) + array( 'menu-item-position' => $position + 1 ) );
		}

		WP_CLI::success( sprintf( 'Created menu "%s" with %d items.', $name, count( $links ) ) );
	}

	$locations[ $location ] = $menu_id;
}

set_theme_mod( 'nav_menu_locations', $locations );
WP_CLI::success( 'Menus assigned to their locations.' );

// ---------------------------------------------------------------------------
// Landing page blocks
// ---------------------------------------------------------------------------

/**
 * Block data in the flat form SCF stores: name => value, _name => field key,
 * repeaters as name => row count plus name_0_sub, name_1_sub…
 */
function nirvana_seed_block_data( array $fields, array $values, string $prefix = '' ): array {
	$data = array();

	foreach ( $fields as $field ) {
		$name = $field['name'] ?? '';
		if ( '' === $name || ! array_key_exists( $name, $values ) ) {
			continue;
		}

		$path = $prefix . $name;

		if ( 'repeater' === $field['type'] ) {
			$rows                = array_values( (array) $values[ $name ] );
			$data[ $path ]       = count( $rows );
			$data[ '_' . $path ] = $field['key'];

			foreach ( $rows as $i => $row ) {
				$data += nirvana_seed_block_data( $field['sub_fields'], $row, "{$path}_{$i}_" );
			}
			continue;
		}

		$data[ $path ]       = $values[ $name ];
		$data[ '_' . $path ] = $field['key'];
	}

	return $data;
}

/**
 * Imports a theme image into the Media Library once; returns its ID.
 */
function nirvana_seed_image( string $asset_path ): int {
	$existing = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_nirvana_seed_source',
			'meta_value'  => $asset_path,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( basename( $asset_path ) );
	copy( get_template_directory() . '/assets' . $asset_path, $tmp );

	$id = media_handle_sideload( array( 'name' => basename( $asset_path ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( "Could not import {$asset_path}: " . $id->get_error_message() );
		return 0;
	}

	update_post_meta( $id, '_nirvana_seed_source', $asset_path );
	return (int) $id;
}

$icon       = static fn( string $path ): string => (string) preg_replace( '#^/?images/#', '', $path );
$paragraphs = static fn( array $list ): string => implode( "\n\n", $list );

$landing = get_post( (int) get_option( 'page_on_front' ) );
$index   = $content['index'] ?? array();

if ( ! nirvana_is_landing_post( $landing ) ) {
	WP_CLI::warning( 'The front page is not using the Landing template. Skipping landing blocks.' );
} elseif ( has_blocks( $landing->post_content ) && str_contains( $landing->post_content, '<!-- wp:nirvana/' ) && ! $force ) {
	WP_CLI::log( 'Landing page already has section blocks. Skipping (pass "force" to overwrite).' );
} else {
	$founder = $index['meet-the-founder'] ?? array();
	$how     = $index['how-it-works'] ?? array();

	$sections = array(
		'hero'          => array(
			'title'    => $index['hero']['title'] ?? '',
			'subtitle' => $index['hero']['subtitle'] ?? '',
		),
		'about'         => array(
			'title' => $index['about-us']['title'] ?? '',
			'body'  => $paragraphs( $index['about-us']['description'] ?? array() ),
		),
		'founder'       => array(
			'title'         => $founder['title'] ?? '',
			'name'          => $founder['heading'] ?? '',
			'role'          => $founder['role'] ?? '',
			'qualification' => $founder['qualification'] ?? '',
			'bio'           => $paragraphs( $founder['description'] ?? array() ),
			'photo'         => ! empty( $founder['image'] ) ? nirvana_seed_image( $founder['image'] ) : '',
			'card'          => $founder['card'] ?? '',
			'stats'         => array_map(
				static function ( array $stat ): array {
					preg_match( '/^(\d+)(.*)$/', (string) $stat['title'], $m );
					return array(
						'number' => (int) ( $m[1] ?? 0 ),
						'suffix' => $m[2] ?? '',
						'label'  => $stat['subtitle'] ?? '',
					);
				},
				$founder['stats'] ?? array()
			),
		),
		'testimonials'  => array(
			'title'        => $index['testimonials']['title'] ?? '',
			'testimonials' => array_map( static fn( $q ) => array( 'quote' => $q ), $index['testimonials']['testimonials'] ?? array() ),
		),
		'why-choose-us' => array(
			'title' => $index['choose-us']['title'] ?? '',
			'items' => array_map(
				static fn( array $item ) => array(
					'icon'  => $icon( $item['icon'] ?? '' ),
					'title' => $item['title'] ?? '',
					'body'  => $paragraphs( $item['description'] ?? array() ),
				),
				$index['choose-us']['description'] ?? array()
			),
		),
		'expertise'     => array(
			'title'     => $index['areas-of-expertise']['title'] ?? '',
			'items'     => array_map(
				static fn( array $item ) => array(
					'icon'  => $icon( $item['image'] ?? '' ),
					'title' => $item['title'] ?? '',
					'body'  => implode( "\n", $item['description'] ?? array() ),
				),
				$index['areas-of-expertise']['description'] ?? array()
			),
			'cta_label' => 'Start a Conversation',
		),
		'how-it-works'  => array(
			'title'         => $how['title'] ?? '',
			'modes'         => array_map(
				static fn( array $item ) => array(
					'icon'  => $icon( $item['image'] ?? '' ),
					'title' => $item['title'] ?? '',
					'text'  => $item['description'] ?? '',
				),
				$how['description'] ?? array()
			),
			'opening_hours' => $how['opening_hours'] ?? '',
			'steps_heading' => $how['heading'] ?? '',
			'steps'         => array_map(
				static fn( array $item ) => array(
					'icon'  => $icon( $item['image'] ?? '' ),
					'title' => $item['title'] ?? '',
					'text'  => $item['description'] ?? '',
				),
				$how['steps'] ?? array()
			),
			'closing'       => $how['closing'] ?? '',
		),
		'faq'           => array(
			'heading'    => $index['faq']['heading'] ?? '',
			'subheading' => $index['faq']['subheading'] ?? '',
			'items'      => array_map(
				static fn( array $item ) => array(
					'question' => $item['question'] ?? '',
					// The textarea turns new lines back into <br /> on output.
					'answer'   => preg_replace( '#\s*<br\s*/?>\s*#i', "\n", $item['answer'] ?? '' ),
				),
				$index['faq']['description'] ?? array()
			),
			'closing'    => $index['faq']['closing'] ?? '',
		),
	);

	$markup = array();
	foreach ( $sections as $slug => $values ) {
		$group  = 'group_nirvana_block_' . str_replace( '-', '_', $slug );
		$fields = acf_get_fields( $group );
		if ( ! $fields ) {
			WP_CLI::error( "Field group {$group} not found. Is acf-json/ in the theme?" );
		}

		$markup[] = serialize_block(
			array(
				'blockName'    => "nirvana/{$slug}",
				'attrs'        => array(
					'name' => "nirvana/{$slug}",
					'data' => nirvana_seed_block_data( $fields, $values ),
					'mode' => 'preview',
				),
				'innerBlocks'  => array(),
				'innerHTML'    => '',
				'innerContent' => array(),
			)
		);
	}

	// Trusted input from the theme: don't let kses rewrite it on save.
	kses_remove_filters();
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $landing->ID,
				'post_content' => implode( "\n\n", $markup ),
				// Used as the meta description when no SEO plugin is active.
				'post_excerpt' => $index['head']['description'] ?? '',
			)
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		WP_CLI::error( 'Could not update the landing page: ' . $result->get_error_message() );
	}

	WP_CLI::success( sprintf( 'Landing page built from %d section blocks.', count( $markup ) ) );
}

// ---------------------------------------------------------------------------
// Terms page (page.php: subtitle field + paragraph blocks)
// ---------------------------------------------------------------------------

$terms_page = get_page_by_path( 'terms-and-conditions' );
$terms      = $content['terms-and-conditions'] ?? array();

if ( ! $terms_page ) {
	WP_CLI::warning( 'No page with the slug "terms-and-conditions". Skipping the terms.' );
} elseif ( str_contains( $terms_page->post_content, '<!-- wp:' ) && ! $force ) {
	WP_CLI::log( 'Terms page already has blocks. Skipping (pass "force" to overwrite).' );
} else {
	$paragraph = static function ( string $html, array $attrs = array() ): string {
		$classes = array();
		if ( isset( $attrs['align'] ) ) {
			$classes[] = 'has-text-align-' . $attrs['align'];
		}
		if ( isset( $attrs['fontSize'] ) ) {
			$classes[] = 'has-' . $attrs['fontSize'] . '-font-size';
		}
		$open = $classes ? '<p class="' . implode( ' ', $classes ) . '">' : '<p>';

		return serialize_block(
			array(
				'blockName'    => 'core/paragraph',
				'attrs'        => $attrs,
				'innerBlocks'  => array(),
				'innerHTML'    => "\n{$open}{$html}</p>\n",
				'innerContent' => array( "\n{$open}{$html}</p>\n" ),
			)
		);
	};

	$blocks = array_map( $paragraph, $terms['description'] ?? array() );
	if ( ! empty( $terms['closing'] ) ) {
		$blocks[] = $paragraph( $terms['closing'], array( 'align' => 'center', 'fontSize' => 'small' ) );
	}

	// The old page-terms.php template is gone; switch to the default page.php
	// first, or wp_update_post() rejects the update as an invalid template.
	delete_post_meta( $terms_page->ID, '_wp_page_template' );

	kses_remove_filters();
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $terms_page->ID,
				'post_title'   => $terms['title'] ?? $terms_page->post_title,
				'post_content' => implode( "\n\n", $blocks ),
			)
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		WP_CLI::error( 'Could not update the terms page: ' . $result->get_error_message() );
	}

	update_field( 'field_nv_page_subtitle', $terms['subtitle'] ?? '', $terms_page->ID );

	WP_CLI::success( sprintf( 'Terms page built from %d paragraph blocks.', count( $blocks ) ) );
}
