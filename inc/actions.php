<?php
/**
 * Booking actions: what a "Book a Session" button does.
 *
 * Every Book button has an Action, chosen in Site Settings (site-wide) or on
 * a therapist (overrides the site):
 *   - "Link": a plain link to the Booking URL.
 *   - A custom script: one file per action in assets/js/actions/, e.g.
 *     calendly.js shows up as "Script: Calendly". The button stays a real
 *     link to the Booking URL, so it still works if the script can't run.
 *
 * A script registers its handler with the registry in assets/js/actions.js:
 *
 *   nirvanaActions.register( 'calendly', ( button, context ) => { … } );
 *
 * context: { bookingUrl, therapistId, therapistName, profileUrl } (the
 * therapist keys only on a therapist's button). The script is loaded only on
 * pages that show a button using it.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Custom action scripts: [ 'calendly' => 'Script: Calendly', … ].
 */
function nirvana_action_scripts(): array {
	static $scripts = null;

	if ( null !== $scripts ) {
		return $scripts;
	}

	$scripts = array();
	$files   = glob( get_template_directory() . '/assets/js/actions/*.js' ) ?: array();
	sort( $files );

	foreach ( $files as $file ) {
		$name = basename( $file, '.js' );
		if ( preg_match( '/^[a-z0-9-]+$/', $name ) ) {
			/* translators: %s: action name, e.g. "Calendly". */
			$scripts[ $name ] = sprintf( __( 'Script: %s', 'nirvana-therapy' ), nirvana_icon_label( $name ) );
		}
	}

	return $scripts;
}

/**
 * Loads an action's script (and the registry it needs). Safe to call while
 * the page body renders: the scripts print in the footer.
 */
function nirvana_enqueue_action( string $name ): bool {
	if ( ! isset( nirvana_action_scripts()[ $name ] ) ) {
		return false;
	}

	wp_enqueue_script(
		'nirvana-actions',
		get_template_directory_uri() . '/assets/js/actions.js',
		array(),
		nirvana_asset_version( '/assets/js/actions.js' ),
		true
	);

	$path = '/assets/js/actions/' . $name . '.js';
	wp_enqueue_script(
		'nirvana-action-' . $name,
		get_template_directory_uri() . $path,
		array( 'nirvana-actions' ),
		nirvana_asset_version( $path ),
		true
	);

	return true;
}

/**
 * nirvana_button() args for a Book button: label, href, target, action and
 * the context handed to an action script.
 *
 * Therapist's own action, else Site Settings. The link falls back to the
 * site Booking URL, then (for a therapist) the profile page.
 */
function nirvana_booking_args( ?WP_Post $therapist = null ): array {
	$site_action = (string) nirvana_setting( 'booking_action', 'link' );
	$site_url    = (string) nirvana_setting( 'booking_url' );

	$action = $site_action;
	$url    = $site_url;

	if ( $therapist && function_exists( 'get_field' ) ) {
		$own = (string) get_field( 'booking_action', $therapist->ID );
		if ( '' !== $own ) {
			$action = $own;
			$url    = (string) get_field( 'booking_url', $therapist->ID ) ?: $site_url;
		}
	}

	$context = array( 'bookingUrl' => $url );
	if ( $therapist ) {
		$url     = $url ?: get_permalink( $therapist );
		$context = array(
			'bookingUrl'    => $url,
			'therapistId'   => $therapist->ID,
			'therapistName' => get_the_title( $therapist ),
			'profileUrl'    => get_permalink( $therapist ),
		);
	}

	return array(
		'label'   => nirvana_setting( 'booking_label', __( 'Book a Session', 'nirvana-therapy' ) ),
		'href'    => $url,
		'target'  => '_blank',
		'action'  => 'link' === $action ? '' : $action,
		'context' => $context,
	);
}

/**
 * Fills the "Action" dropdowns (Site Settings and each therapist) with Link
 * plus the scripts in assets/js/actions/.
 */
add_filter( 'acf/load_field/name=booking_action', 'nirvana_booking_action_choices' );
function nirvana_booking_action_choices( array $field ): array {
	if ( 'select' !== ( $field['type'] ?? '' ) ) {
		return $field;
	}

	$choices = array( 'link' => __( 'Link', 'nirvana-therapy' ) ) + nirvana_action_scripts();
	if ( 'field_nv_therapist_booking_action' === ( $field['key'] ?? '' ) ) {
		$choices = array( '' => __( 'Same as Site Settings', 'nirvana-therapy' ) ) + $choices;
	}

	$field['choices']       = $choices;
	$field['allow_null']    = 0;
	$field['multiple']      = 0;
	$field['return_format'] = 'value';

	return $field;
}
