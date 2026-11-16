<?php
/**
 * Booking popup: a step-by-step popup that opens from Book buttons.
 *
 * It is a booking action (assets/js/actions/booking-popup.js), so it runs
 * when Site Settings → Action (or a therapist's own Action) is
 * "Script: Booking Popup". Its steps are edited in wp-admin under
 * Site Settings → Booking Popup (acf-json/group_nirvana_booking_popup.json).
 *
 * Each therapist can have their own steps (the "Booking popup" box on the
 * therapist, the same fields generated from the Site Settings group); left
 * empty, they use Site Settings → Booking Popup. Each Book button opens its
 * own therapist's popup: one <dialog> is printed per set of steps in use.
 *
 * Each choice either goes to another step, opens a link, or runs a script
 * from assets/js/actions/. A choice can be hidden when the visitor picked
 * certain answers earlier ("Hide when the visitor picked": e.g. Couple). Scripts get the Book button's context plus
 * `answers`: { <step ID>: <chosen label>, … }.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'acf/init', 'nirvana_register_booking_popup_page', 11 );
function nirvana_register_booking_popup_page(): void {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	acf_add_options_sub_page(
		array(
			'page_title'  => __( 'Booking Popup', 'nirvana-therapy' ),
			'menu_title'  => __( 'Booking Popup', 'nirvana-therapy' ),
			'menu_slug'   => 'nirvana-booking-popup',
			'parent_slug' => 'nirvana-site-settings',
			'capability'  => 'edit_theme_options',
		)
	);
}

/**
 * The therapist's own "Booking popup" box: the Site Settings popup fields,
 * re-keyed (field_nv_bp_… → field_nv_tbp_…), so both always match.
 */
add_action( 'acf/init', 'nirvana_register_therapist_popup_fields' );
function nirvana_register_therapist_popup_fields(): void {
	$json = (string) file_get_contents( get_template_directory() . '/acf-json/group_nirvana_booking_popup.json' );
	$site = json_decode( str_replace( 'field_nv_bp_', 'field_nv_tbp_', $json ), true );
	if ( ! function_exists( 'acf_add_local_field_group' ) || empty( $site['fields'] ) ) {
		return;
	}

	$fields                    = $site['fields'];
	$fields[0]['instructions'] = __( "This therapist's own popup, opened by their Book buttons when their Booking action (or Site Settings') is \"Script: Booking Popup\". Empty: the steps in Site Settings → Booking Popup. Links left empty use this therapist's Booking URL.", 'nirvana-therapy' );

	acf_add_local_field_group(
		array(
			'key'                   => 'group_nirvana_therapist_popup',
			'title'                 => __( 'Booking popup', 'nirvana-therapy' ),
			'fields'                => $fields,
			'location'              => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'therapist' ) ) ),
			'position'              => 'normal',
			'menu_order'            => 2,
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
		)
	);
}

/**
 * "When clicked" dropdowns: steps (choices only), Link, and the scripts.
 */
add_filter( 'acf/load_field/key=field_nv_bp_option_then', 'nirvana_booking_popup_then_choices' );
add_filter( 'acf/load_field/key=field_nv_bp_step_footer_then', 'nirvana_booking_popup_then_choices' );
add_filter( 'acf/load_field/key=field_nv_tbp_option_then', 'nirvana_booking_popup_then_choices' );
add_filter( 'acf/load_field/key=field_nv_tbp_step_footer_then', 'nirvana_booking_popup_then_choices' );
function nirvana_booking_popup_then_choices( array $field ): array {
	$choices = array();
	if ( str_ends_with( (string) $field['key'], '_option_then' ) ) {
		$choices = array(
			'next' => __( 'Go to the next step', 'nirvana-therapy' ),
			'step' => __( 'Go to a step…', 'nirvana-therapy' ),
		);
	}

	$scripts = nirvana_action_scripts();
	unset( $scripts['booking-popup'] ); // It can't open itself.

	$field['choices']    = $choices + array( 'link' => __( 'Open a link', 'nirvana-therapy' ) ) + $scripts;
	$field['allow_null'] = 0;
	$field['multiple']   = 0;

	return $field;
}

/**
 * The steps a therapist's Book buttons open: their own, else Site Settings'.
 * Without a therapist, Site Settings'. Cleaned up: steps without a question,
 * and choices without a label, are left out.
 *
 * @return array[]
 */
function nirvana_booking_popup_steps( ?WP_Post $therapist = null ): array {
	$steps = $therapist ? nirvana_booking_popup_clean( get_field( 'booking_steps', $therapist->ID ) ) : array();

	return $steps ?: nirvana_booking_popup_clean( nirvana_setting( 'booking_steps', array() ) );
}

/**
 * Which popup a Book button opens: "booking-popup" (Site Settings' steps)
 * or "booking-popup-<id>" (a therapist with their own steps).
 */
function nirvana_booking_popup_id( ?WP_Post $therapist = null ): string {
	if ( $therapist && nirvana_booking_popup_clean( get_field( 'booking_steps', $therapist->ID ) ) ) {
		return 'booking-popup-' . $therapist->ID;
	}

	return 'booking-popup';
}

/**
 * Remembers the popups this page's Book buttons open, so the footer prints
 * them, and tells each button which one is its own (context.popup).
 */
add_filter( 'nirvana_booking_args', 'nirvana_booking_popup_args', 10, 2 );
function nirvana_booking_popup_args( array $args, ?WP_Post $therapist ): array {
	if ( 'booking-popup' !== $args['action'] ) {
		return $args;
	}

	$id                       = nirvana_booking_popup_id( $therapist );
	$args['context']['popup'] = $id;
	nirvana_booking_popups_used( $id, $therapist );

	return $args;
}

/**
 * The popups to print: [ 'booking-popup' => null, 'booking-popup-78' => WP_Post ].
 */
function nirvana_booking_popups_used( string $id = '', ?WP_Post $therapist = null ): array {
	static $used = array();
	if ( '' !== $id && ! array_key_exists( $id, $used ) ) {
		$used[ $id ] = 'booking-popup' === $id ? null : $therapist;
	}

	return $used;
}

/**
 * Saved repeater rows → the steps the popup prints.
 *
 * @param mixed $rows get_field() of a "booking_steps" repeater.
 * @return array[]
 */
function nirvana_booking_popup_clean( $rows ): array {
	$rows    = is_array( $rows ) ? $rows : array();
	$scripts = nirvana_action_scripts();
	$then    = static function ( string $value, array $allowed, string $default ) use ( $scripts ): string {
		return in_array( $value, $allowed, true ) || ( isset( $scripts[ $value ] ) && 'booking-popup' !== $value ) ? $value : $default;
	};

	$steps = array();
	foreach ( $rows as $i => $row ) {
		$title = trim( (string) ( $row['title'] ?? '' ) );
		if ( '' === $title ) {
			continue;
		}

		$options = array();
		foreach ( (array) ( $row['options'] ?? array() ) as $option ) {
			$label = trim( (string) ( $option['label'] ?? '' ) );
			if ( '' === $label ) {
				continue;
			}
			$options[] = array(
				'label'   => $label,
				'icon'    => (string) ( $option['icon'] ?? '' ),
				'caption' => trim( (string) ( $option['caption'] ?? '' ) ),
				// "Couple, Returning" → [ 'couple', 'returning' ]: hidden when one was picked.
				'hide_for' => array_values( array_filter( array_map( static fn( $a ) => mb_strtolower( trim( $a ) ), explode( ',', (string) ( $option['hide_for'] ?? '' ) ) ) ) ),
				'then'    => $then( (string) ( $option['then'] ?? '' ), array( 'next', 'step', 'link' ), 'next' ),
				'step'    => sanitize_title( (string) ( $option['step'] ?? '' ) ),
				'url'     => (string) ( $option['url'] ?? '' ),
			);
		}

		$steps[] = array(
			'id'           => sanitize_title( (string) ( $row['id'] ?? '' ) ) ?: 'step-' . ( $i + 1 ),
			'title'        => $title,
			'description'  => trim( (string) ( $row['description'] ?? '' ) ),
			'style'        => 'tiles' === ( $row['style'] ?? '' ) ? 'tiles' : 'list',
			'options'      => $options,
			'footer_text'  => trim( (string) ( $row['footer_text'] ?? '' ) ),
			'footer_label' => trim( (string) ( $row['footer_label'] ?? '' ) ),
			'footer_then'  => $then( (string) ( $row['footer_then'] ?? '' ), array( 'link' ), 'link' ),
			'footer_url'   => (string) ( $row['footer_url'] ?? '' ),
		);
	}

	return $steps;
}

/**
 * Prints the popup (hidden) on pages with a Book button that opens it, and
 * loads the scripts its choices use. Runs before the footer scripts print.
 */
add_action( 'wp_footer', 'nirvana_booking_popup_render', 5 );
function nirvana_booking_popup_render(): void {
	if ( ! wp_script_is( 'nirvana-action-booking-popup', 'enqueued' ) ) {
		return;
	}

	foreach ( nirvana_booking_popups_used() as $id => $therapist ) {
		$steps = nirvana_booking_popup_steps( $therapist );
		if ( ! $steps ) {
			continue; // The script then lets these Book buttons work as plain links.
		}

		foreach ( $steps as $step ) {
			foreach ( array_merge( array_column( $step['options'], 'then' ), array( $step['footer_then'] ) ) as $then ) {
				nirvana_enqueue_action( $then ); // Ignores next / step / link.
			}
		}

		get_template_part( 'parts/booking-popup', null, array( 'id' => $id, 'steps' => $steps ) );
	}
}

/**
 * Attributes for a choice or bottom button: what it does when clicked.
 * Links with no URL of their own get the Book button's link when the popup opens.
 */
function nirvana_booking_popup_target( string $then, string $step, string $url ): array {
	if ( 'next' === $then || 'step' === $then ) {
		return array(
			'tag'   => 'button',
			'attrs' => sprintf( ' type="button" data-then="%s"%s', esc_attr( $then ), 'step' === $then ? ' data-target="' . esc_attr( $step ) . '"' : '' ),
		);
	}

	if ( 'link' === $then ) {
		return array(
			'tag'   => 'a',
			'attrs' => sprintf(
				' href="%s" target="_blank" rel="noopener noreferrer" data-then="link"%s',
				esc_url( $url ?: '#' ),
				'' === $url ? ' data-fallback' : ''
			),
		);
	}

	return array(
		'tag'   => 'button',
		'attrs' => sprintf( ' type="button" data-then="script" data-script="%s" data-url="%s"', esc_attr( $then ), esc_url( $url ) ),
	);
}
