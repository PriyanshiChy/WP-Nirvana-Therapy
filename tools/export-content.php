<?php
/**
 * Backs up everything edited in wp-admin into tools/content.json, under the
 * "wordpress" key. The original keys (components, index,
 * terms-and-conditions) are left as they are; seed-content.php reads those.
 *
 * Run from the WordPress root (re-run any time; it replaces the "wordpress" key):
 *   wp eval-file wp-content/themes/nirvana-therapy/tools/export-content.php
 *
 * Read-only on the site. Field values are raw (as saved: image fields are
 * attachment IDs), so they can be written back with update_field(). Every
 * referenced image is listed under "media" with its URL and file name; the
 * image files themselves stay in wp-content/uploads.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'get_fields' ) ) {
	WP_CLI::error( 'Secure Custom Fields is not active.' );
}

$file    = __DIR__ . '/content.json';
$content = json_decode( (string) file_get_contents( $file ), true );
if ( ! is_array( $content ) ) {
	WP_CLI::error( 'tools/content.json is missing or not valid JSON.' );
}

$terms = static function ( int $id, string $taxonomy ): array {
	$list = get_the_terms( $id, $taxonomy );
	return is_array( $list ) ? wp_list_pluck( $list, 'name' ) : array();
};

$post = static function ( WP_Post $p ) use ( $terms ): array {
	$data = array(
		'id'             => $p->ID,
		'title'          => $p->post_title,
		'slug'           => $p->post_name,
		'status'         => $p->post_status,
		'parent'         => $p->post_parent,
		'menu_order'     => $p->menu_order,
		'template'       => (string) get_page_template_slug( $p ),
		'excerpt'        => $p->post_excerpt,
		'featured_image' => (int) get_post_thumbnail_id( $p ),
		'modified'       => $p->post_modified_gmt,
		'content'        => $p->post_content, // Block markup, incl. each block's fields.
		'fields'         => get_fields( $p->ID, false ) ?: new stdClass(),
	);

	if ( 'therapist' === $p->post_type ) {
		$data['languages'] = $terms( $p->ID, 'therapist_language' );
		$data['expertise'] = $terms( $p->ID, 'therapist_expertise' );
	}

	return $data;
};

$posts = static fn( string $type ): array => array_map(
	$post,
	get_posts(
		array(
			'post_type'      => $type,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	)
);

// Menus, with the locations they're assigned to.
$locations = get_nav_menu_locations();
$menus     = array();
foreach ( wp_get_nav_menus() as $menu ) {
	$menus[] = array(
		'name'      => $menu->name,
		'slug'      => $menu->slug,
		'locations' => array_keys( $locations, $menu->term_id, true ),
		'items'     => array_map(
			static fn( $item ): array => array(
				'id'        => (int) $item->ID,
				'parent'    => (int) $item->menu_item_parent,
				'title'     => $item->title,
				'url'       => $item->url,
				'type'      => $item->type,
				'object'    => $item->object,
				'object_id' => (int) $item->object_id,
				'target'    => $item->target,
				'classes'   => array_values( array_filter( (array) $item->classes ) ),
			),
			wp_get_nav_menu_items( $menu->term_id ) ?: array()
		),
	);
}

// The media library (URLs and details; the files stay in uploads).
$media = array();
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => -1 ) ) as $a ) {
	$media[ $a->ID ] = array(
		'file'    => basename( (string) get_attached_file( $a->ID ) ),
		'url'     => wp_get_attachment_url( $a->ID ),
		'title'   => $a->post_title,
		'alt'     => (string) get_post_meta( $a->ID, '_wp_attachment_image_alt', true ),
		'caption' => $a->post_excerpt,
	);
}

$taxonomy_terms = static fn( string $taxonomy ): array => array_map(
	static fn( WP_Term $t ): array => array( 'name' => $t->name, 'slug' => $t->slug, 'description' => $t->description ),
	get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) ) ?: array()
);

$content['wordpress'] = array(
	'exported'   => gmdate( 'c' ),
	'site_url'   => home_url( '/' ),
	'note'       => 'Backup of the content edited in wp-admin (tools/export-content.php). Field values are raw: image fields are IDs from "media".',
	'site'       => array(
		'title'         => get_option( 'blogname' ),
		'tagline'       => get_option( 'blogdescription' ),
		'show_on_front' => get_option( 'show_on_front' ),
		'page_on_front' => (int) get_option( 'page_on_front' ),
		'site_icon'     => (int) get_option( 'site_icon' ),
		'permalinks'    => get_option( 'permalink_structure' ),
	),
	'settings'   => get_fields( 'option', false ) ?: new stdClass(), // Site Settings + Booking Popup.
	'pages'      => $posts( 'page' ),
	'therapists' => $posts( 'therapist' ),
	'posts'      => $posts( 'post' ),
	'taxonomies' => array(
		'therapist_language'  => $taxonomy_terms( 'therapist_language' ),
		'therapist_expertise' => $taxonomy_terms( 'therapist_expertise' ),
	),
	'menus'      => $menus,
	'media'      => $media ?: new stdClass(),
);

$json = wp_json_encode( $content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
if ( false === $json || false === file_put_contents( $file, $json . "\n" ) ) {
	WP_CLI::error( 'Could not write tools/content.json.' );
}

$w = $content['wordpress'];
WP_CLI::success(
	sprintf(
		'Backed up to tools/content.json: %d pages, %d therapists, %d posts, %d menus, %d media, %d settings.',
		count( $w['pages'] ),
		count( $w['therapists'] ),
		count( $w['posts'] ),
		count( $w['menus'] ),
		count( (array) $w['media'] ),
		count( (array) $w['settings'] )
	)
);
