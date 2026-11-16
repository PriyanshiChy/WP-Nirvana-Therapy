<?php
/**
 * Site Settings (SCF options page) and navigation menus.
 *
 * Global content (contact details, button links, socials, footer text) lives on
 * the "Site Settings" page in wp-admin. Its fields are defined in
 * acf-json/group_nirvana_site_settings.json so they are versioned with the theme.
 */

defined( 'ABSPATH' ) || exit;

// ---------------------------------------------------------------------------
// Options page
// ---------------------------------------------------------------------------

add_action( 'acf/init', 'nirvana_register_options_page' );
function nirvana_register_options_page(): void {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Settings', 'nirvana-therapy' ),
			'menu_title' => __( 'Site Settings', 'nirvana-therapy' ),
			'menu_slug'  => 'nirvana-site-settings',
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-generic',
			'position'   => 59,
			'redirect'   => false,
			'autoload'   => true,
		)
	);
}

/**
 * The site needs Secure Custom Fields for its editable content.
 */
add_action( 'admin_notices', 'nirvana_missing_scf_notice' );
function nirvana_missing_scf_notice(): void {
	if ( function_exists( 'get_field' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html__( 'The Nirvana Therapy theme needs the Secure Custom Fields plugin. Install and activate it under Plugins → Add New.', 'nirvana-therapy' )
	);
}

/**
 * A value from Site Settings, or $default when it is empty or SCF is inactive.
 *
 * Returns the raw value: escape it where it is printed.
 */
function nirvana_setting( string $name, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$value = get_field( $name, 'option' );

	return ( null === $value || false === $value || '' === $value || array() === $value ) ? $default : $value;
}

/**
 * "tel:" link for a phone number typed in any format, e.g. "+91 62919 57761".
 */
function nirvana_tel_href( string $phone ): string {
	$digits = preg_replace( '/[^\d+]/', '', $phone );
	return $digits ? 'tel:' . $digits : '';
}

/**
 * Copyright line with {year} replaced by the current year.
 */
function nirvana_copyright(): string {
	$text = (string) nirvana_setting( 'copyright', '© {year} ' . get_bloginfo( 'name' ) );
	return str_replace( '{year}', wp_date( 'Y' ), $text );
}

/**
 * Social networks the theme has icons for: value => [label, icon file].
 */
function nirvana_social_networks(): array {
	return array(
		'instagram' => array( 'Instagram', 'instagram.svg' ),
		'linkedin'  => array( 'LinkedIn', 'linkedin.svg' ),
	);
}

/**
 * Social links from Site Settings, skipping rows with an unknown network or no URL.
 *
 * @return array<int, array{label: string, href: string, icon: string}>
 */
function nirvana_socials(): array {
	$networks = nirvana_social_networks();
	$socials  = array();

	foreach ( (array) nirvana_setting( 'socials', array() ) as $row ) {
		$network = $row['network'] ?? '';
		$url     = $row['url'] ?? '';

		if ( '' === $url || ! isset( $networks[ $network ] ) ) {
			continue;
		}

		$socials[] = array(
			'label' => $networks[ $network ][0],
			'href'  => $url,
			'icon'  => $networks[ $network ][1],
		);
	}

	return $socials;
}

// ---------------------------------------------------------------------------
// Menus (Appearance → Menus)
// ---------------------------------------------------------------------------

add_action( 'after_setup_theme', 'nirvana_register_menus' );
function nirvana_register_menus(): void {
	register_nav_menus(
		array(
			'primary'        => __( 'Header', 'nirvana-therapy' ),
			'footer-explore' => __( 'Footer – Explore', 'nirvana-therapy' ),
			'footer-legal'   => __( 'Footer – Legal', 'nirvana-therapy' ),
		)
	);
}

/**
 * Top-level items of the menu assigned to $location, as plain data so the
 * header and footer keep their own markup.
 *
 * @return array<int, array{label: string, href: string, target: string}>
 */
function nirvana_menu_items( string $location ): array {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( ! $items ) {
		return array();
	}

	$out = array();
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent ) {
			continue; // The design has no dropdowns.
		}

		$out[] = array(
			'label'  => $item->title,
			'href'   => nirvana_menu_href( (string) $item->url ),
			'target' => (string) $item->target,
		);
	}

	return $out;
}

/**
 * Root-relative links ("/#faq") resolve against the site URL, and "Home" on
 * the front page scrolls to the hero instead of reloading.
 */
function nirvana_menu_href( string $url ): string {
	if ( str_starts_with( $url, '/' ) && ! str_starts_with( $url, '//' ) ) {
		$url = home_url( $url );
	}

	if ( is_front_page() && untrailingslashit( $url ) === untrailingslashit( home_url( '/' ) ) ) {
		return home_url( '/#hero' );
	}

	return $url;
}

/**
 * target/rel attributes for a menu link.
 */
function nirvana_link_target_attrs( string $target ): string {
	if ( '_blank' !== $target ) {
		return '';
	}

	return ' target="_blank" rel="noopener noreferrer"';
}
