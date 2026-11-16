<?php
/**
 * Areas of Expertise block: flip cards on desktop, a list on mobile.
 */

defined( 'ABSPATH' ) || exit;

$items = (array) get_field( 'items' );
?>
<section id="area-of-expertise" class="px-6 pt-9 pb-9 sm:p-16 space-y-9 sm:space-y-16">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="hidden lg:grid grid-cols-3 [&>*:not(:nth-last-child(-n+3))]:border-b-2 *:border-r-2 [&>*:nth-child(3n)]:border-r-0 *:border-primary">
    <?php foreach ( $items as $item ) : ?>
      <div class="flip-card h-40" tabindex="0">
        <div class="flip-card-inner">
          <div class="flip-card-front flex items-center gap-4 px-9 py-8 [&_svg]:shrink-0">
            <?php echo nirvana_icon( (string) ( $item['icon'] ?? '' ) ); ?>
            <p class="text-2xl font-serif"><?php echo esc_html( $item['title'] ?? '' ); ?></p>
          </div>
          <div class="flip-card-back px-9 space-y-4 flex flex-col justify-center">
            <?php foreach ( nirvana_lines( $item['body'] ?? '' ) as $line ) : ?>
              <p class="text-base font-sans leading-[150%]"><?php echo esc_html( $line ); ?></p>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="lg:hidden max-w-md mx-auto">
    <?php foreach ( $items as $item ) : ?>
      <div class="flex gap-4 py-4 px-5 items-center w-fit">
        <div class="size-24 [&>svg]:h-full [&>svg]:w-full shrink-0">
          <?php echo nirvana_icon( (string) ( $item['icon'] ?? '' ) ); ?>
        </div>
        <div class="flex flex-col gap-4">
          <p class="text-xl font-serif font-medium"><?php echo esc_html( $item['title'] ?? '' ); ?></p>
          <div class="font-sans text-[0.9375rem] leading-[1.5] text-pretty">
            <?php $lines = nirvana_lines( $item['body'] ?? '' ); ?>
            <?php foreach ( $lines as $k => $line ) : ?>
              <p><?php echo esc_html( $line ); ?></p><?php if ( array_key_last( $lines ) !== $k ) : ?><br /><?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="flex justify-center items-center gap-9 sm:py-6">
    <?php
    nirvana_button(
      array(
        'preset' => 'whatsapp',
        'label'  => get_field( 'cta_label' ) ?: __( 'Start a Conversation', 'nirvana-therapy' ),
      )
    );
    ?>
  </div>
</section>
