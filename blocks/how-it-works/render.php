<?php
/**
 * How It Works block: session types, opening hours, booking steps.
 */

defined( 'ABSPATH' ) || exit;

$opening_hours = (string) get_field( 'opening_hours' );
$steps_heading = (string) get_field( 'steps_heading' );
$closing       = (string) get_field( 'closing' );
?>
<section id="how-it-works" class="px-6 pt-9 pb-9 sm:p-16 space-y-9">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="flex md:py-8 md:mt-4 divide-x-2 divide-primary">
    <?php foreach ( (array) get_field( 'modes' ) as $item ) : ?>
      <div class="flex flex-1 flex-col items-center text-center justify-center px-2.5 gap-4">
        <div class="size-16 [&>svg]:h-full [&>svg]:w-full"><?php echo nirvana_icon( (string) ( $item['icon'] ?? '' ) ); ?></div>
        <div class="flex flex-col items-center gap-4">
          <p class="font-serif font-semibold text-lg sm:text-2xl"><?php echo esc_html( $item['title'] ?? '' ); ?></p>
          <p class="font-sans text-base h-12"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if ( $opening_hours ) : ?>
  <div class="md:mt-4 md:py-2 text-center text-base md:text-2xl font-sans">
    <p><?php echo esc_html( $opening_hours ); ?></p>
  </div>
  <?php endif; ?>
  <div class="md:mt-4 flex flex-col md:items-center md:text-center gap-y-9">
    <h3 class="leading-[100%]"><?php echo nirvana_kses_inline( $steps_heading ); ?></h3>
    <div class="w-full flex flex-col md:flex-row md:py-8 space-y-9">
      <?php foreach ( (array) get_field( 'steps' ) as $item ) : ?>
        <div class="flex flex-1 md:flex-col items-center text-center">
          <div class="md:max-w-48 w-full md:space-y-4 space-x-4 flex md:block items-center">
            <div class="space-y-4 max-w-30 md:max-w-full w-full shrink-0">
              <div class="size-12 md:size-16 [&>svg]:h-full [&>svg]:w-full mx-auto">
                <?php echo nirvana_icon( (string) ( $item['icon'] ?? '' ) ); ?>
              </div>
              <p class="font-serif font-semibold text-xl md:text-2xl [&_br]:hidden md:[&_br]:block"><?php echo nirvana_kses_inline( $item['title'] ?? '' ); ?></p>
            </div>
            <p class="font-sans"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php if ( $closing ) : ?>
  <h4 class="font-medium text-lg md:text-2xl md:mt-4 text-center"><?php echo nirvana_kses_inline( $closing ); ?></h4>
  <?php endif; ?>
</section>
