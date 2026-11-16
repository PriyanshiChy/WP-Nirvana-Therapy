<?php
/**
 * Testimonials block.
 */

defined( 'ABSPATH' ) || exit;

$testimonials = (array) get_field( 'testimonials' );
$arrow        = '<svg class="size-10 text-primary%s" aria-hidden="true" focusable="false"><use href="#icon-arrow-right" /></svg>';
?>
<section id="testimonials" class="px-6 pt-6 pb-9 sm:py-16 sm:space-y-16">
  <div class="sm:px-16 flex w-full justify-between">
    <h2 class="text-left!"><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
    <div class="hidden sm:flex gap-4">
      <button type="button" aria-controls="testimonial-carousel" aria-label="<?php esc_attr_e( 'Previous testimonial', 'nirvana-therapy' ); ?>" class="testimonial-carousel-previous link outlined px-2.5!">
        <?php printf( $arrow, '' ); ?>
      </button>
      <button type="button" aria-controls="testimonial-carousel" aria-label="<?php esc_attr_e( 'Next testimonial', 'nirvana-therapy' ); ?>" class="testimonial-carousel-next link outlined px-2.5!">
        <?php printf( $arrow, ' rotate-180' ); ?>
      </button>
    </div>
  </div>
  <div id="testimonial-carousel" role="region" aria-label="<?php esc_attr_e( 'Testimonials', 'nirvana-therapy' ); ?>" class="testimonial-carousel flex w-full gap-12 items-center overflow-hidden sm:px-16 mt-9">
    <?php foreach ( $testimonials as $item ) : ?>
    <div class="h-96 max-w-full w-90 shrink-0 bg-[#DDE8FE] px-9 pb-10 pt-5 space-y-4 flex flex-col">
      <?php echo nirvana_svg( '/images/icons/quote.svg' ); ?>
      <p class="flex-1 line-clamp-10 h-full font-serif overflow-clip text-lg leading-7"><?php echo esc_html( $item['quote'] ?? '' ); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="flex sm:hidden justify-center gap-4 mt-6">
    <button type="button" aria-controls="testimonial-carousel" aria-label="<?php esc_attr_e( 'Previous testimonial', 'nirvana-therapy' ); ?>" class="testimonial-carousel-previous link outlined icon px-2.5!">
      <?php printf( $arrow, '' ); ?>
    </button>
    <button type="button" aria-controls="testimonial-carousel" aria-label="<?php esc_attr_e( 'Next testimonial', 'nirvana-therapy' ); ?>" class="testimonial-carousel-next link outlined icon px-2.5!">
      <?php printf( $arrow, ' rotate-180' ); ?>
    </button>
  </div>
  <?php $reviews_button = nirvana_get_button( array( 'preset' => 'reviews' ) ); ?>
  <?php if ( $reviews_button ) : ?>
  <div class="flex w-full justify-center [&_.link_svg]:size-8 sm:px-6 mt-9">
    <?php echo $reviews_button; // Escaped in nirvana_get_button(). ?>
  </div>
  <?php endif; ?>
</section>
