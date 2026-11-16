<?php
/**
 * Theme setup: supports, assets, <head> metadata.
 */

defined( 'ABSPATH' ) || exit;

// ---------------------------------------------------------------------------
// Theme supports
// ---------------------------------------------------------------------------

add_action( 'after_setup_theme', 'nirvana_theme_setup' );
function nirvana_theme_setup(): void {
	// WordPress (or an SEO plugin) now owns the <title> tag.
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'script', 'style', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
}

// ---------------------------------------------------------------------------
// Assets
// ---------------------------------------------------------------------------

/**
 * File modification time as the asset version, so browsers fetch the new file
 * after every change instead of serving a stale cached copy.
 */
function nirvana_asset_version( string $relative_path ): string {
	$file = get_template_directory() . $relative_path;
	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme( get_template() )->get( 'Version' );
}

function nirvana_fonts_url(): string {
	return 'https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Reenie+Beanie&display=swap';
}

add_action( 'wp_enqueue_scripts', 'nirvana_enqueue_assets' );
function nirvana_enqueue_assets(): void {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'nirvana-style', $uri . '/style.css', array(), nirvana_asset_version( '/style.css' ) );

	wp_enqueue_style( 'nirvana-fonts', nirvana_fonts_url(), array(), null ); // No ?ver= on the Google Fonts URL.

	wp_enqueue_script(
		'nirvana-main',
		$uri . '/assets/js/main.js',
		array(),
		nirvana_asset_version( '/assets/js/main.js' ),
		array(
			'in_footer' => false,
			'strategy'  => 'defer',
		)
	);

	// Therapist profiles: the floating "Back to top" button.
	if ( is_singular( 'therapist' ) ) {
		wp_enqueue_script(
			'nirvana-back-to-top',
			$uri . '/assets/js/back-to-top.js',
			array(),
			nirvana_asset_version( '/assets/js/back-to-top.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}

add_filter( 'wp_resource_hints', 'nirvana_resource_hints', 10, 2 );
function nirvana_resource_hints( array $urls, string $relation ): array {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}

/**
 * Preload only the background image visible at the top of the current page.
 */
add_filter( 'wp_preload_resources', 'nirvana_preload_resources' );
function nirvana_preload_resources( array $resources ): array {
	$images = array(
		'index' => '/images/bg/hero.webp',
		'page'  => '/images/bg/tnc.webp',
	);

	$key  = nirvana_current_page_key();
	$href = 'index' === $key ? nirvana_hero_background_url() : '';
	if ( '' === $href && $key && isset( $images[ $key ] ) ) {
		$href = nirvana_asset_uri( $images[ $key ] );
	}

	if ( $href ) {
		$resources[] = array(
			'href' => $href,
			'as'   => 'image',
		);
	}

	return $resources;
}

/**
 * Adds a "js" class to <html> before anything renders. CSS only hides the
 * fade-in content under .js, so with JavaScript off nothing stays invisible.
 *
 * Fail-safe: if main.js hasn't run within 4s (blocked, 404, error), the class
 * is removed again so the content shows without the animation.
 */
add_action( 'wp_head', 'nirvana_js_detection', 0 );
function nirvana_js_detection(): void {
	wp_print_inline_script_tag(
		"document.documentElement.classList.add('js');" .
		"setTimeout(function(){if(!window.nirvanaReady){document.documentElement.classList.remove('js');}},4000);"
	);
}

// ---------------------------------------------------------------------------
// <head> metadata
// ---------------------------------------------------------------------------

/**
 * Which kind of page the current request is rendering: 'index' (landing),
 * 'page' (terms, therapists and other pages, therapist profiles), or null.
 */
function nirvana_current_page_key(): ?string {
	if ( is_page_template( 'page-landing.php' ) ) {
		return 'index';
	}
	if ( is_page() || is_singular( 'therapist' ) ) {
		return 'page';
	}

	return null;
}

/**
 * Meta description when no SEO plugin is active: the page's excerpt (the
 * "Excerpt" panel in the editor, or the start of its text), else the tagline.
 */
function nirvana_meta_description(): string {
	$description = is_singular() ? get_the_excerpt( get_queried_object() ) : '';
	if ( '' === trim( $description ) ) {
		$description = get_bloginfo( 'description' );
	}

	return trim( wp_strip_all_tags( $description ) );
}

/**
 * When an SEO plugin is active, let it own title/description/social tags.
 */
function nirvana_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'The_SEO_Framework\Load' );
}

/**
 * Pages can have an excerpt, used as their meta description.
 */
add_action( 'init', 'nirvana_page_excerpts' );
function nirvana_page_excerpts(): void {
	add_post_type_support( 'page', 'excerpt' );
}

/**
 * "Nirvana Therapy | Tagline", "Terms and Conditions | Nirvana Therapy".
 */
add_filter( 'document_title_separator', 'nirvana_title_separator' );
function nirvana_title_separator( string $separator ): string {
	return nirvana_seo_plugin_active() ? $separator : '|';
}

add_action( 'wp_head', 'nirvana_meta_tags', 1 );
function nirvana_meta_tags(): void {
	?>
	<meta name="creator" content="Priyanshi Choudhury &lt;priyanshichoudhury.work@gmail.com&gt;" />
	<meta name="author" content="Nirvana Therapy" />
	<?php
	if ( nirvana_seo_plugin_active() ) {
		return;
	}

	$description = nirvana_meta_description();
	$title       = html_entity_decode( wp_strip_all_tags( wp_get_document_title() ), ENT_QUOTES, 'UTF-8' );
	$url         = is_singular() ? get_permalink() : home_url( '/' );
	$base        = get_template_directory_uri() . '/assets/images';
	?>
	<?php if ( '' !== $description ) : ?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
	<?php endif; ?>

	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
	<?php if ( '' !== $description ) : ?>
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
	<?php endif; ?>
	<meta property="og:image" content="<?php echo esc_url( $base . '/og-image.png' ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />

	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
	<?php if ( '' !== $description ) : ?>
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
	<?php endif; ?>
	<meta name="twitter:image" content="<?php echo esc_url( $base . '/twitter-card.png' ); ?>" />
	<?php
}

/**
 * Theme favicons + manifest. Skipped if a Site Icon is set in the Customizer,
 * because WordPress prints its own icon tags in that case.
 */
add_action( 'wp_head', 'nirvana_favicons', 2 );
function nirvana_favicons(): void {
	if ( has_site_icon() ) {
		return;
	}

	$base = get_template_directory_uri() . '/favicon';
	?>
	<link rel="icon" href="<?php echo esc_url( $base . '/favicon.ico' ); ?>" sizes="any" />
	<link rel="icon" type="image/png" href="<?php echo esc_url( $base . '/favicon-32x32.png' ); ?>" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?php echo esc_url( $base . '/favicon-16x16.png' ); ?>" sizes="16x16" />
	<link rel="apple-touch-icon" href="<?php echo esc_url( $base . '/apple-touch-icon.png' ); ?>" />
	<link rel="manifest" href="<?php echo esc_url( $base . '/site.webmanifest' ); ?>" />
	<?php
}
