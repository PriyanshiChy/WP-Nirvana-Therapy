<?php
/**
 * Site header: document head, opening <body>, icon sprite and the navbar.
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:fixed! focus:top-4 focus:left-4 focus:z-100 focus:bg-foreground focus:px-4 focus:py-3 focus:font-sans" href="#main"><?php esc_html_e( 'Skip to content', 'nirvana-therapy' ); ?></a>
<?php echo nirvana_svg( '/images/icons.svg' ); // Icon sprite referenced by <use href="#icon-…">. ?>
<?php get_template_part( 'parts/header' ); ?>
