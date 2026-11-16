<?php
/**
 * Landing page section blocks (SCF/ACF blocks in /blocks).
 *
 * Each section is one block that can appear once. On the landing page only
 * these blocks are offered, so the client can reorder, remove and re-add
 * sections but can't put anything else between them. Section IDs are fixed
 * in each render.php, so the CSS and the menu's anchor links keep working
 * in any order.
 */

defined( 'ABSPATH' ) || exit;

/**
 * The landing page sections, in their default order.
 */
function nirvana_landing_blocks(): array {
	return array(
		'nirvana/hero',
		'nirvana/about',
		'nirvana/founder',
		'nirvana/testimonials',
		'nirvana/why-choose-us',
		'nirvana/expertise',
		'nirvana/how-it-works',
		'nirvana/faq',
	);
}

add_action( 'init', 'nirvana_register_blocks' );
function nirvana_register_blocks(): void {
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return; // SCF inactive: the blocks would have nothing to render with.
	}

	foreach ( glob( get_template_directory() . '/blocks/*/block.json' ) ?: array() as $file ) {
		register_block_type( dirname( $file ) );
	}
}

add_filter( 'block_categories_all', 'nirvana_block_category' );
function nirvana_block_category( array $categories ): array {
	array_unshift(
		$categories,
		array(
			'slug'  => 'nirvana',
			'title' => __( 'Nirvana Therapy sections', 'nirvana-therapy' ),
		)
	);

	return $categories;
}

function nirvana_is_landing_post( $post ): bool {
	return $post instanceof WP_Post
		&& 'page' === $post->post_type
		&& 'page-landing.php' === get_page_template_slug( $post );
}

/**
 * Landing page: only the section blocks. Everywhere else: anything but them.
 */
add_filter( 'allowed_block_types_all', 'nirvana_allowed_blocks', 10, 2 );
function nirvana_allowed_blocks( $allowed, $context ) {
	$post = $context->post ?? null;
	if ( ! $post instanceof WP_Post ) {
		return $allowed; // Site editor, widgets.
	}

	if ( nirvana_is_landing_post( $post ) ) {
		return nirvana_landing_blocks();
	}

	if ( true === $allowed ) {
		$allowed = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
	}

	return is_array( $allowed ) ? array_values( array_diff( $allowed, nirvana_landing_blocks() ) ) : $allowed;
}

/**
 * An empty landing page opens with every section in the default order.
 */
add_filter( 'block_editor_settings_all', 'nirvana_landing_template', 10, 2 );
function nirvana_landing_template( array $settings, $context ): array {
	if ( isset( $context->post ) && nirvana_is_landing_post( $context->post ) ) {
		$settings['template'] = array_map(
			static fn( string $name ): array => array( $name ),
			nirvana_landing_blocks()
		);
	}

	return $settings;
}

/**
 * Editor canvas: the theme's stylesheet and fonts, so blocks and page
 * content look as they do on the site.
 *
 * enqueue_block_assets fires three times:
 *   - on the front end, where nirvana_enqueue_assets() already loads these;
 *   - in wp-admin's own page (during admin_enqueue_scripts), where Tailwind's
 *     reset and base rules would restyle the admin UI;
 *   - while WordPress collects the editor iframe's assets
 *     (_wp_get_iframed_editor_assets()). Only this one is wanted.
 *
 * In that iframe pass, WordPress also adds wp-admin's common.css and
 * forms.css plus a "reset" stylesheet for classic themes. They are unlayered,
 * so they beat Tailwind's @layer rules (paragraph margins, fonts, sizes,
 * .hidden…) and the preview stops matching the site. Marking them as already
 * printed keeps them out of the iframe only; the admin page keeps its own copy.
 * Editor-only overrides live in entry.css under .editor-styles-wrapper.
 */
add_action( 'enqueue_block_assets', 'nirvana_editor_canvas_assets' );
function nirvana_editor_canvas_assets(): void {
	if ( ! is_admin() || doing_action( 'admin_enqueue_scripts' ) ) {
		return;
	}

	$styles       = wp_styles();
	$styles->done = array_merge( $styles->done, array( 'wp-reset-editor-styles', 'common', 'forms' ) );

	wp_enqueue_style( 'nirvana-style', get_template_directory_uri() . '/style.css', array(), nirvana_asset_version( '/style.css' ) );
	wp_enqueue_style( 'nirvana-fonts', nirvana_fonts_url(), array(), null );
}

/**
 * Editor UI (outside the canvas): the "Semibold" rich-text button.
 */
add_action( 'enqueue_block_editor_assets', 'nirvana_editor_ui_assets' );
function nirvana_editor_ui_assets(): void {
	wp_enqueue_script(
		'nirvana-editor-semibold',
		get_template_directory_uri() . '/assets/js/editor-semibold.js',
		array( 'wp-rich-text', 'wp-block-editor', 'wp-element' ),
		nirvana_asset_version( '/assets/js/editor-semibold.js' ),
		true
	);
}

/**
 * URL of the hero background chosen in the Hero block, if any. Used to
 * preload it (see inc/setup.php).
 */
function nirvana_hero_background_url(): string {
	$post = get_queried_object();
	if ( ! nirvana_is_landing_post( $post ) || ! has_block( 'nirvana/hero', $post ) ) {
		return '';
	}

	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( 'nirvana/hero' === $block['blockName'] ) {
			$id = (int) ( $block['attrs']['data']['background'] ?? 0 );
			return $id ? (string) wp_get_attachment_image_url( $id, 'full' ) : '';
		}
	}

	return '';
}
