<?php
defined( 'ABSPATH' ) || exit;

$explore    = nirvana_menu_items( 'footer-explore' );
$legal      = nirvana_menu_items( 'footer-legal' );
$address    = (string) nirvana_setting( 'address' );
$maps_url   = (string) nirvana_setting( 'maps_url' );
$maps_label = (string) nirvana_setting( 'maps_label', __( 'Find us on Maps', 'nirvana-therapy' ) );
$socials    = nirvana_socials();
$phone      = (string) nirvana_setting( 'phone' );
$email      = (string) nirvana_setting( 'email' );
$contacts   = array_filter(
	array(
		$phone ? array( 'icon' => 'phone.svg', 'label' => $phone, 'href' => nirvana_tel_href( $phone ) ) : null,
		$email ? array( 'icon' => 'email.svg', 'label' => $email, 'href' => 'mailto:' . $email ) : null,
	)
);
$closing    = (string) nirvana_setting( 'footer_closing' );
?>
<footer>
  <div class="px-6 sm:px-16 py-9 sm:py-10 flex flex-col sm:flex-row gap-4 sm:gap-16">
    <!-- Left column: EXPLORE + ADDRESS -->
    <div class="flex-1 flex flex-col gap-4 sm:gap-10">

      <nav class="flex flex-col items-start sm:gap-4" aria-label="<?php esc_attr_e( 'Explore', 'nirvana-therapy' ); ?>">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_explore', __( 'EXPLORE', 'nirvana-therapy' ) ) ); ?></p>
        <ul class="flex sm:flex-col gap-y-2 gap-x-6">
          <?php foreach ( $explore as $link ) : ?>
            <li><a class="flex items-center font-sans text-base text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit" href="<?php echo esc_url( $link['href'] ); ?>"<?php echo nirvana_link_target_attrs( $link['target'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="flex sm:hidden flex-col">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_legal', __( 'LEGAL', 'nirvana-therapy' ) ) ); ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach ( $legal as $link ) : ?>
            <li><a class="flex items-center font-sans text-base text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit" href="<?php echo esc_url( $link['href'] ); ?>"<?php echo nirvana_link_target_attrs( $link['target'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_address', __( 'ADDRESS', 'nirvana-therapy' ) ) ); ?></p>
        <address class="not-italic font-sans text-base text-text leading-6 w-fit p-2 sm:p-0"><?php echo nl2br( esc_html( $address ) ); ?></address>
        <?php if ( $maps_url ) : ?>
        <a class="flex items-center font-sans text-sm text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit gap-2.5" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer">
          <?php echo esc_html( $maps_label ); ?>
          <span class="[&>svg.icon-external-link]:size-4! [&>svg]:h-full [&>svg]:w-full"><?php echo nirvana_svg( '/images/icons/external-link.svg' ); ?></span>
        </a>
        <?php endif; ?>
      </div>

    </div>

    <!-- Right column: LEGAL + FIND US + CONTACT INFORMATION -->
    <div class="flex-1 flex flex-col gap-4 sm:gap-10">

      <div class="hidden sm:flex flex-col sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_legal', __( 'LEGAL', 'nirvana-therapy' ) ) ); ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach ( $legal as $link ) : ?>
            <li><a class="font-sans text-base text-text hover:underline" href="<?php echo esc_url( $link['href'] ); ?>"<?php echo nirvana_link_target_attrs( $link['target'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_find_us', __( 'FIND US', 'nirvana-therapy' ) ) ); ?></p>
        <div class="flex gap-2 sm:gap-3 p-2 sm:p-0">
          <?php foreach ( $socials as $social ) : ?>
            <a href="<?php echo esc_url( $social['href'] ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center size-10 text-foreground bg-primary" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
              <?php echo nirvana_svg( '/images/icons/' . $social['icon'] ); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo esc_html( nirvana_setting( 'footer_heading_contact', __( 'CONTACT INFORMATION', 'nirvana-therapy' ) ) ); ?></p>
        <ul class="flex flex-col gap-2 sm:gap-3 px-2 py-4 sm:p-0">
          <?php foreach ( $contacts as $item ) : ?>
            <li>
              <a class="flex items-center gap-3 font-sans text-base text-text hover:underline" href="<?php echo esc_url( $item['href'] ); ?>">
                <span class="size-6 shrink-0 [&>svg]:h-full [&>svg]:w-full"><?php echo nirvana_svg( '/images/icons/' . $item['icon'] ); ?></span>
                <?php echo esc_html( $item['label'] ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  </div>

  <!-- Closing section -->
  <div class="flex flex-col items-center gap-4 sm:gap-6 mt-4 mb-4 md:mb-48">
    <a class="[&_svg]:h-full [&_svg]:max-h-12 [&_svg]:sm:max-h-16 [&_svg]:w-full" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s – home', 'nirvana-therapy' ), get_bloginfo( 'name' ) ) ); ?>">
      <?php echo nirvana_svg( '/images/logo.svg' ); ?>
    </a>
    <p class="font-sans text-base"><?php echo esc_html( $closing ); ?></p>
    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center justify-center w-full px-6">
      <?php nirvana_button( array( 'preset' => 'book' ) ); ?>
      <?php nirvana_button( array( 'preset' => 'whatsapp', 'variant' => 'outlined' ) ); ?>
    </div>
  </div>

  <!-- Copyright -->
  <div class="relative flex flex-col-reverse sm:flex-row justify-end sm:px-16 px-4 pt-6 pb-9 md:pb-4 text-center md:text-right">
    <a href="https://pchy.design" class="sm:absolute left-10 bottom-0 font-sans text-sm font-semibold sm:block py-2 px-3 sm:bg-foreground text-foreground sm:text-primary rounded-t-xl" target="_blank" rel="noopener noreferrer">Website by • <span class="text-foreground sm:text-primary">pchy</span></a>
    <p class="font-sans text-sm text-foreground font-semibold"><?php echo esc_html( nirvana_copyright() ); ?></p>
  </div>
</footer>
