<?php
defined( 'ABSPATH' ) || exit;

$menu          = nirvana_menu_items( 'primary' );
$whatsapp_href = (string) nirvana_setting( 'whatsapp_url' );
$home_label    = sprintf(
	/* translators: %s: site name. */
	__( '%s – home', 'nirvana-therapy' ),
	get_bloginfo( 'name' )
);

?>
<header>
  <div id="header-bar" class="flex items-center justify-between">
    <div class="w-fit xl:w-60">
      <a class="[&_svg]:h-12 lg:[&_svg]:h-16" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $home_label ); ?>">
        <?php echo nirvana_svg( '/images/logo.svg' ); ?>
      </a>
    </div>

    <nav class="lg:block hidden" aria-label="<?php esc_attr_e( 'Main', 'nirvana-therapy' ); ?>">
      <ul class="flex">
        <?php foreach ( $menu as $item ) : ?>
          <li><a class="after:transition-all after:duration-300 after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-[calc(100%-3.25rem)] after:border-b-2 after:border-b-transparent" href="<?php echo esc_url( $item['href'] ); ?>"<?php echo nirvana_link_target_attrs( $item['target'] ); ?>><?php echo esc_html( $item['label'] ); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="xl:block hidden">
      <?php nirvana_button( array( 'preset' => 'whatsapp' ) ); ?>
    </div>

    <div class="xl:hidden lg:block hidden">
      <?php nirvana_button( array( 'preset' => 'whatsapp', 'icon_only' => true ) ); ?>
    </div>

    <div class="lg:hidden flex items-center gap-2 sm:gap-4">
      <?php if ( $whatsapp_href ) : ?>
      <a href="<?php echo esc_url( $whatsapp_href ); ?>" target="_blank" rel="noopener noreferrer" class="p-2" aria-label="<?php esc_attr_e( 'WhatsApp', 'nirvana-therapy' ); ?>">
        <svg class="size-8 text-primary" aria-hidden="true" focusable="false"><use href="#icon-whatsapp"></use></svg>
      </a>
      <?php endif; ?>
      <button class="p-2" id="header-hamburger" aria-label="<?php esc_attr_e( 'Open menu', 'nirvana-therapy' ); ?>" data-close-label="<?php esc_attr_e( 'Close menu', 'nirvana-therapy' ); ?>" aria-expanded="false" aria-controls="mobile-menu">
        <?php echo nirvana_svg( '/images/icons/hamburger.svg' ); ?>
      </button>
    </div>
  </div>

  <div id="mobile-menu" class="space-y-6 lg:hidden aria-hidden:hidden" aria-hidden="true">
    <nav aria-label="<?php esc_attr_e( 'Mobile', 'nirvana-therapy' ); ?>">
      <ul class="w-full flex flex-col">
        <?php foreach ( $menu as $item ) : ?>
          <li class="w-full h-12 text-base text-center">
            <a class="w-full h-full flex items-center justify-center p-0!" href="<?php echo esc_url( $item['href'] ); ?>"<?php echo nirvana_link_target_attrs( $item['target'] ); ?>><span><?php echo esc_html( $item['label'] ); ?></span></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="flex flex-col gap-4 [&_a]:w-full!">
      <?php nirvana_button( array( 'preset' => 'book' ) ); ?>
      <?php nirvana_button( array( 'preset' => 'whatsapp', 'variant' => 'outlined' ) ); ?>
    </div>
  </div>
</header>
