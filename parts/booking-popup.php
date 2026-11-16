<?php
/**
 * The booking popup's markup (see inc/booking-popup.php). One .booking-step per
 * step; assets/js/actions/booking-popup.js shows one at a time.
 *
 * $args['steps']: nirvana_booking_popup_steps().
 */

defined( 'ABSPATH' ) || exit;

$steps = $args['steps'] ?? array();
?>
<dialog id="booking-popup" class="booking-popup" aria-labelledby="booking-popup-title-0">
  <div class="booking-card">
    <div class="booking-progress" aria-hidden="true"><span></span></div>

    <button type="button" class="booking-back text-text/70" aria-label="<?php esc_attr_e( 'Back', 'nirvana-therapy' ); ?>" hidden>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <button type="button" class="booking-close text-text/70" aria-label="<?php esc_attr_e( 'Close', 'nirvana-therapy' ); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <?php foreach ( $steps as $i => $step ) : ?>
      <?php
      $tiles    = 'tiles' === $step['style'];
      $captions = array_filter( array_column( $step['options'], 'caption' ) );
      $footer   = '' !== $step['footer_label'] || '' !== $step['footer_text'];
      ?>
    <div class="booking-step <?php echo '' !== $step['description'] ? 'gap-6' : 'gap-12'; ?>" data-step-id="<?php echo esc_attr( $step['id'] ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
      <div class="flex flex-col items-center gap-2">
        <h2 id="booking-popup-title-<?php echo (int) $i; ?>" class="font-serif font-semibold text-xl sm:text-2xl leading-[1.3]" tabindex="-1"><?php echo esc_html( $step['title'] ); ?></h2>
        <?php if ( '' !== $step['description'] ) : ?>
        <p class="text-sm leading-normal text-text/70!"><?php echo nl2br( esc_html( $step['description'] ) ); ?></p>
        <?php endif; ?>
      </div>

      <?php if ( $step['options'] ) : ?>
      <ul class="<?php echo $tiles ? 'booking-tiles' : 'booking-list ' . ( $captions || $footer ? 'gap-2' : 'gap-4' ); ?>">
        <?php foreach ( $step['options'] as $option ) : ?>
          <?php $target = nirvana_booking_popup_target( $option['then'], $option['step'], $option['url'] ); ?>
        <li>
          <?php if ( $tiles ) : ?>
          <<?php echo $target['tag']; ?> class="booking-tile"<?php echo $target['attrs']; // Escaped inside. ?> data-answer="<?php echo esc_attr( $option['label'] ); ?>">
            <?php echo nirvana_icon( $option['icon'] ); // Theme file. ?>
            <span><?php echo esc_html( $option['label'] ); ?></span>
          </<?php echo $target['tag']; ?>>
          <?php else : ?>
          <<?php echo $target['tag']; ?> class="link outlined small"<?php echo $target['attrs']; // Escaped inside. ?> data-answer="<?php echo esc_attr( $option['label'] ); ?>">
            <div><p><?php echo esc_html( $option['label'] ); ?></p></div>
          </<?php echo $target['tag']; ?>>
          <?php endif; ?>
          <?php if ( '' !== $option['caption'] && ! $tiles ) : ?>
          <p class="text-xs leading-normal text-text/70!"><?php echo esc_html( $option['caption'] ); ?></p>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php if ( $footer ) : ?>
      <div class="booking-footer">
        <?php if ( '' !== $step['footer_text'] ) : ?>
        <p class="text-sm leading-normal text-text/70!"><?php echo esc_html( $step['footer_text'] ); ?></p>
        <?php endif; ?>
        <?php if ( '' !== $step['footer_label'] ) : ?>
          <?php $target = nirvana_booking_popup_target( $step['footer_then'], '', $step['footer_url'] ); ?>
        <<?php echo $target['tag']; ?> class="link primary small"<?php echo $target['attrs']; // Escaped inside. ?>>
          <div><p><?php echo esc_html( $step['footer_label'] ); ?></p></div>
        </<?php echo $target['tag']; ?>>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</dialog>
