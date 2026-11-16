<?php
/**
 * The booking popup's steps from the Figma design, for a site with none yet.
 *
 * Run from the WordPress root:
 *   wp eval-file wp-content/themes/nirvana-therapy/tools/seed-booking-popup.php
 *
 * Only runs when Site Settings → Booking Popup has no steps, so it never
 * overwrites edits. Links are left empty (= the Book button's own link) for
 * the real booking links to be filled in. Not shipped in the theme zip.
 */

defined( 'ABSPATH' ) || exit;

if ( get_field( 'booking_steps', 'option' ) ) {
	WP_CLI::error( 'The booking popup already has steps. This script only fills an empty popup, so it never overwrites edits.' );
}

$choice = static fn( string $label, string $then = 'next', string $icon = '', string $caption = '' ): array => array(
	'field_nv_bp_option_label'   => $label,
	'field_nv_bp_option_icon'    => $icon,
	'field_nv_bp_option_caption' => $caption,
	'field_nv_bp_option_then'    => $then,
	'field_nv_bp_option_step'    => '',
	'field_nv_bp_option_url'     => '',
);

$steps = array(
	array(
		'field_nv_bp_step_id'          => 'who',
		'field_nv_bp_step_title'       => 'Who is the session for?',
		'field_nv_bp_step_description' => '',
		'field_nv_bp_step_style'       => 'tiles',
		'field_nv_bp_options'          => array(
			$choice( 'Individual', 'next', 'therapy/individual.svg' ),
			$choice( 'Couple', 'next', 'therapy/couple.svg' ),
		),
		'field_nv_bp_step_footer_then' => 'link',
	),
	array(
		'field_nv_bp_step_id'          => 'first-session',
		'field_nv_bp_step_title'       => 'Is this your first session with us?',
		'field_nv_bp_step_description' => '',
		'field_nv_bp_step_style'       => 'list',
		'field_nv_bp_options'          => array(
			$choice( 'I am new here' ),
			$choice( 'I am a returning client' ),
		),
		'field_nv_bp_step_footer_then' => 'link',
	),
	array(
		'field_nv_bp_step_id'           => 'plan',
		'field_nv_bp_step_title'        => 'Choose your session plan',
		'field_nv_bp_step_description'  => 'Make the most of your therapy journey with a discounted session package.',
		'field_nv_bp_step_style'        => 'list',
		'field_nv_bp_options'           => array(
			$choice( 'Book three sessions', 'link' ),
			$choice( 'Book six sessions', 'link' ),
			$choice( 'Couple + Individual sessions', 'link', '', 'A combination of couple and 2 individual sessions' ),
		),
		'field_nv_bp_step_footer_text'  => 'Prefer to book a single session?',
		'field_nv_bp_step_footer_label' => 'Book a Session',
		'field_nv_bp_step_footer_then'  => 'link',
		'field_nv_bp_step_footer_url'   => '',
	),
);

update_field( 'field_nv_bp_steps', $steps, 'option' );
WP_CLI::success( count( get_field( 'booking_steps', 'option' ) ?: array() ) . ' steps added.' );
