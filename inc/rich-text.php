<?php
/**
 * Rich text: which HTML editable content may contain, and the custom
 * <semibold> tag.
 *
 * <semibold> must be allowed in three places or WordPress drops it:
 *   1. on output         nirvana_kses()
 *   2. on save           wp_kses_allowed_html filter (users without unfiltered_html)
 *   3. in the editor     TinyMCE custom_elements, plus a "Semibold" button
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tags allowed in editable rich text.
 */
function nirvana_allowed_html(): array {
	return array(
		'a'        => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
		'br'       => array(),
		'p'        => array(),
		'em'       => array(),
		'i'        => array(),
		'strong'   => array(),
		'b'        => array(),
		'u'        => array(),
		'ul'       => array(),
		'ol'       => array(),
		'li'       => array(),
		'semibold' => array(),
	);
}

/**
 * Escapes editable rich text for output. Links opening a new tab get
 * rel="noopener noreferrer".
 */
function nirvana_kses( $html ): string {
	return wp_targeted_link_rel( wp_kses( (string) $html, nirvana_allowed_html() ) );
}

/**
 * Escapes a one-line field (titles, headings) that may contain inline
 * formatting: links, line breaks, bold/italic/underline and <semibold>.
 */
function nirvana_kses_inline( $html ): string {
	$allowed = array_diff_key( nirvana_allowed_html(), array_flip( array( 'p', 'ul', 'ol', 'li' ) ) );
	return wp_targeted_link_rel( wp_kses( (string) $html, $allowed ) );
}

/**
 * Non-empty lines of a textarea, for fields entered as "one item per line".
 */
function nirvana_lines( $text ): array {
	$lines = array_map( 'trim', preg_split( '/\R/', (string) $text ) );
	return array_values( array_filter( $lines, 'strlen' ) );
}

// 2. Keep <semibold> when content is saved.
add_filter( 'wp_kses_allowed_html', 'nirvana_kses_allow_semibold', 10, 2 );
function nirvana_kses_allow_semibold( array $tags, $context ): array {
	if ( 'post' === $context ) {
		$tags['semibold'] = array();
	}

	return $tags;
}

// 3. Teach TinyMCE (SCF's WYSIWYG fields) about <semibold>.
add_filter( 'tiny_mce_before_init', 'nirvana_tinymce_semibold' );
function nirvana_tinymce_semibold( array $init ): array {
	$init['custom_elements']         = trim( ( $init['custom_elements'] ?? '' ) . ',~semibold', ',' );
	$init['extended_valid_elements'] = trim( ( $init['extended_valid_elements'] ?? '' ) . ',semibold', ',' );
	$init['content_style']           = trim( ( $init['content_style'] ?? '' ) . ' semibold{font-weight:600}' );

	return $init;
}

add_filter( 'mce_external_plugins', 'nirvana_tinymce_plugins' );
function nirvana_tinymce_plugins( array $plugins ): array {
	$plugins['nirvana_semibold'] = add_query_arg(
		'ver',
		nirvana_asset_version( '/assets/js/tinymce-semibold.js' ),
		get_template_directory_uri() . '/assets/js/tinymce-semibold.js'
	);
	return $plugins;
}

/**
 * Toolbar for SCF WYSIWYG fields: pick "Nirvana" as the field's toolbar.
 */
add_filter( 'acf/fields/wysiwyg/toolbars', 'nirvana_wysiwyg_toolbar' );
function nirvana_wysiwyg_toolbar( array $toolbars ): array {
	$toolbars['Nirvana'] = array(
		1 => array( 'bold', 'nirvana_semibold', 'italic', 'underline', 'bullist', 'numlist', 'link', 'unlink', 'removeformat', 'undo', 'redo' ),
	);

	return $toolbars;
}
