<?php
/**
 * UI helpers: buttons and content icons.
 */

defined( 'ABSPATH' ) || exit;

// ---------------------------------------------------------------------------
// Buttons
// ---------------------------------------------------------------------------

/**
 * Prints a link styled as a button. See nirvana_get_button() for $args.
 */
function nirvana_button( array $args ): void {
	echo nirvana_get_button( $args ); // Escaped inside.
}

/**
 * Markup for a link styled as a button.
 *
 * $args:
 *   preset         'book' | 'whatsapp' | 'reviews'. Fills label, href, target and
 *                  icon from Site Settings. Any other arg overrides the preset.
 *   variant        'primary' | 'outlined' | 'glow' | 'underlined'
 *   label          Plain text. Escaped here.
 *   href           URL. Nothing is printed when it is empty (unless an action is set).
 *   target         e.g. '_blank' (adds rel="noopener noreferrer").
 *   icon           'whatsapp' | 'external-link'
 *   icon_position  'before' | 'after'
 *   icon_only      Hides the label visually; it becomes the aria-label.
 *   size           'default' | 'small' (48px tall, 16px text: therapist cards).
 *   aria_label     Accessible name when the label alone is ambiguous
 *                  ("View Profile" on every card).
 *   action         A custom script from assets/js/actions/ (see inc/actions.php).
 *                  The href stays as the fallback; without one, a <button>.
 *   context        Array handed to the action script as JSON.
 */
function nirvana_get_button( array $args ): string {
	$preset = $args['preset'] ?? '';
	unset( $args['preset'] );

	$args = wp_parse_args(
		$args,
		nirvana_button_preset( $preset ) + array(
			'variant'       => 'primary',
			'label'         => '',
			'href'          => '',
			'target'        => '',
			'icon'          => '',
			'icon_position' => 'before',
			'icon_only'     => false,
			'size'          => 'default',
			'aria_label'    => '',
			'action'        => '',
			'context'       => array(),
		)
	);

	$action = '' !== (string) $args['action'] && nirvana_enqueue_action( (string) $args['action'] ) ? (string) $args['action'] : '';

	if ( '' === (string) $args['href'] && '' === $action ) {
		return '';
	}

	$variants = array( 'primary', 'outlined', 'glow', 'underlined' );
	$variant  = in_array( $args['variant'], $variants, true ) ? $args['variant'] : 'primary';
	$classes  = array( 'link', $variant );
	if ( $args['icon_only'] ) {
		$classes[] = 'icon';
	}
	if ( 'small' === $args['size'] ) {
		$classes[] = 'small';
	}

	$label = '<p>' . esc_html( $args['label'] ) . '</p>';
	$icon  = nirvana_button_icon( (string) $args['icon'] );
	$inner = 'after' === $args['icon_position'] ? $label . $icon : $icon . $label;

	$href  = (string) $args['href'];
	$tag   = '' === $href ? 'button' : 'a';
	$attrs = sprintf( ' class="%s"', esc_attr( implode( ' ', $classes ) ) );
	if ( 'a' === $tag ) {
		$attrs .= sprintf( ' href="%s"', esc_url( $href ) );
		if ( '' !== $args['target'] ) {
			$attrs .= sprintf( ' target="%s"', esc_attr( $args['target'] ) );
		}
		if ( '_blank' === $args['target'] ) {
			$attrs .= ' rel="noopener noreferrer"';
		}
	} else {
		$attrs .= ' type="button"';
	}

	$aria_label = '' !== $args['aria_label'] ? $args['aria_label'] : ( $args['icon_only'] ? $args['label'] : '' );
	if ( '' !== $aria_label ) {
		$attrs .= sprintf( ' aria-label="%s"', esc_attr( $aria_label ) );
	}

	if ( '' !== $action ) {
		$attrs .= sprintf( ' data-nirvana-action="%s"', esc_attr( $action ) );
		if ( $args['context'] ) {
			$attrs .= sprintf( ' data-nirvana-context="%s"', esc_attr( wp_json_encode( $args['context'] ) ) );
		}
	}

	return sprintf( '<%1$s%2$s><div>%3$s</div></%1$s>', $tag, $attrs, $inner );
}

/**
 * Defaults for the buttons that appear across the site, from Site Settings.
 */
function nirvana_button_preset( string $preset ): array {
	switch ( $preset ) {
		case 'book':
			return nirvana_booking_args(); // Label, link and action from Site Settings.

		case 'whatsapp':
			return array(
				'label'  => nirvana_setting( 'whatsapp_label', __( 'Contact Us', 'nirvana-therapy' ) ),
				'href'   => nirvana_setting( 'whatsapp_url' ),
				'target' => '_blank',
				'icon'   => 'whatsapp',
			);

		case 'reviews':
			return array(
				'label'         => nirvana_setting( 'reviews_label', __( 'Read more Reviews', 'nirvana-therapy' ) ),
				'href'          => nirvana_setting( 'reviews_url' ),
				'target'        => '_blank',
				'variant'       => 'underlined',
				'icon'          => 'external-link',
				'icon_position' => 'after',
			);
	}

	return array();
}

/**
 * Icon markup for a button. Only the theme's own icons; no user input.
 */
function nirvana_button_icon( string $icon ): string {
	switch ( $icon ) {
		case 'whatsapp':
			return '<svg class="size-6 sm:size-8 text-foreground" aria-hidden="true" focusable="false"><use href="#icon-whatsapp"></use></svg>';

		case 'external-link':
			return nirvana_svg( '/images/icons/external-link.svg' );
	}

	return '';
}

// ---------------------------------------------------------------------------
// Content icons (editable "Icon" dropdowns)
// ---------------------------------------------------------------------------

/**
 * Folders under assets/images that content icons can be picked from.
 */
function nirvana_icon_folders(): array {
	return array(
		'areas-of-expertise' => __( 'Areas of expertise', 'nirvana-therapy' ),
		'icons'              => __( 'General', 'nirvana-therapy' ),
		'therapy'            => __( 'Therapy', 'nirvana-therapy' ),
	);
}

/**
 * Interface icons in assets/images/icons that are not offered as content icons.
 */
function nirvana_icon_excluded(): array {
	return array(
		'icons/email.svg',
		'icons/external-link.svg',
		'icons/hamburger.svg',
		'icons/instagram.svg',
		'icons/left-button.svg',
		'icons/linkedin.svg',
		'icons/phone.svg',
		'icons/quote.svg',
		'icons/right-button.svg',
		'icons/see-less.svg',
		'icons/see-more.svg',
		'icons/whatsapp.svg',
	);
}

/**
 * Every pickable icon, grouped by folder:
 * [ 'Areas of expertise' => [ 'areas-of-expertise/grief.svg' => 'Grief', … ], … ]
 *
 * Dropping a new SVG into one of the folders makes it available.
 */
function nirvana_icon_choices(): array {
	static $choices = null;

	if ( null !== $choices ) {
		return $choices;
	}

	$choices  = array();
	$base     = get_template_directory() . '/assets/images/';
	$excluded = nirvana_icon_excluded();

	foreach ( nirvana_icon_folders() as $folder => $group ) {
		$files = glob( $base . $folder . '/*.svg' ) ?: array();
		sort( $files );

		foreach ( $files as $file ) {
			$value = $folder . '/' . basename( $file );
			if ( in_array( $value, $excluded, true ) ) {
				continue;
			}

			$choices[ $group ][ $value ] = nirvana_icon_label( basename( $file, '.svg' ) );
		}
	}

	return $choices;
}

/**
 * Dropdown label for an icon file: "life-transition" → "Life Transition",
 * with overrides where the filename reads badly.
 */
function nirvana_icon_label( string $slug ): string {
	$labels = array(
		'anxiety-overthinking'  => 'Anxiety & Overthinking',
		'body-img'              => 'Body Image',
		'friendship-peer'       => 'Friendship & Peers',
		'romantic-relationship' => 'Romantic Relationships',
		'sexuality-affirming'   => 'Sexuality (Affirming)',
		'sleep-somantic'        => 'Sleep & Somatic',
	);

	return $labels[ $slug ] ?? ucwords( trim( preg_replace( '/[-_&\s]+/', ' ', $slug ) ) );
}

/**
 * Inline SVG for an icon chosen in a dropdown, e.g. "areas-of-expertise/grief.svg".
 *
 * The value must be one of nirvana_icon_choices(), so a tampered value can
 * never read another file. A leading "/images/" (the old content.json form)
 * is accepted too.
 */
function nirvana_icon( string $value ): string {
	$value = preg_replace( '#^/?images/#', '', $value );

	foreach ( nirvana_icon_choices() as $icons ) {
		if ( isset( $icons[ $value ] ) ) {
			return nirvana_svg( '/images/' . $value );
		}
	}

	return '';
}

/**
 * Fills every select field named "icon" with the theme's icons.
 */
add_filter( 'acf/load_field/name=icon', 'nirvana_icon_field_choices' );
function nirvana_icon_field_choices( array $field ): array {
	if ( 'select' !== ( $field['type'] ?? '' ) ) {
		return $field;
	}

	$field['choices']       = nirvana_icon_choices();
	$field['ui']            = 1;
	$field['allow_null']    = 0;
	$field['return_format'] = 'value';

	return $field;
}
