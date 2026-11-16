<?php
/**
 * Booking popup: a step-by-step popup that opens from Book buttons.
 *
 * It is a booking action (assets/js/actions/booking-popup.js), so it runs
 * when Site Settings → Action (or a therapist's own Action) is
 * "Script: Booking Popup". Its steps are edited in wp-admin under
 * Site Settings → Booking Popup (acf-json/group_nirvana_booking_popup.json).
 *
 * Each choice either goes to another step, opens a link, or runs a script
 * from assets/js/actions/. Scripts get the Book button's context plus
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
 * "When clicked" dropdowns: steps (choices only), Link, and the scripts.
 */
add_filter( 'acf/load_field/key=field_nv_bp_option_then', 'nirvana_booking_popup_then_choices' );
add_filter( 'acf/load_field/key=field_nv_bp_step_footer_then', 'nirvana_booking_popup_then_choices' );
function nirvana_booking_popup_then_choices( array $field ): array {
	$choices = array();
	if ( 'field_nv_bp_option_then' === $field['key'] ) {
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
 * The saved steps, cleaned up. Steps without a question, and choices
 * without a label, are left out.
 *
 * @return array[]
 */
function nirvana_booking_popup_steps(): array {
	$rows    = (array) nirvana_setting( 'booking_steps', array() );
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

	$steps = nirvana_booking_popup_steps();
	if ( ! $steps ) {
		return; // The script then lets Book buttons work as plain links.
	}

	foreach ( $steps as $step ) {
		foreach ( array_merge( array_column( $step['options'], 'then' ), array( $step['footer_then'] ) ) as $then ) {
			nirvana_enqueue_action( $then ); // Ignores next / step / link.
		}
	}

	get_template_part( 'parts/booking-popup', null, array( 'steps' => $steps ) );
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
