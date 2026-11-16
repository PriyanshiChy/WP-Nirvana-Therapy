<?php
/**
 * Asset helpers. Paths resolve against the parent theme
 * (get_template_directory) so they keep working if a child theme is used.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Public URL of a file in /assets. $path starts with a slash, e.g. "/images/logo.svg".
 */
function nirvana_asset_uri( string $path ): string {
	return get_template_directory_uri() . '/assets' . $path;
}

/**
 * Inline SVG markup for a file in /assets, cached per request.
 *
 * Decorative by default: adds aria-hidden="true" and focusable="false" to the
 * root <svg> so screen readers skip it. Pass false when the SVG itself carries
 * meaning and has its own <title>.
 */
function nirvana_svg( string $path, bool $decorative = true ): string {
	static $cache = array();

	$key = $path . ( $decorative ? ':d' : '' );
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$file = get_template_directory() . '/assets' . $path;
	$svg  = is_readable( $file ) ? (string) file_get_contents( $file ) : '';

	if ( $decorative && '' !== $svg && ! preg_match( '/<svg\b[^>]*\baria-hidden=/i', $svg ) ) {
		$svg = preg_replace( '/<svg\b/i', '<svg aria-hidden="true" focusable="false"', $svg, 1 );
	}

	$cache[ $key ] = $svg;
	return $svg;
}
