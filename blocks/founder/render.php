<?php
/**
 * Meet the Founder block.
 */

defined( 'ABSPATH' ) || exit;

$photo = (int) get_field( 'photo' );
$name  = (string) get_field( 'name' );
$alt   = __( 'Founder - Nirvana Therapy', 'nirvana-therapy' ); // Overridden by the photo's alt text in the Media Library.
// Only stats with a number or a label; with none, the stats row is hidden.
// (An empty repeater comes back as false or "", so don't cast it to an array.)
$stats = get_field( 'stats' );
$stats = array_slice(
	array_values(
		array_filter(
			is_array( $stats ) ? $stats : array(),
			static fn( $stat ): bool => is_array( $stat ) && ( absint( $stat['number'] ?? 0 ) > 0 || '' !== trim( (string) ( $stat['label'] ?? '' ) ) )
		)
	),
	0,
	3
);
?>
<section id="meet-the-founder" class="px-6 pt-0 pb-9 sm:p-16">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-14 w-full mx-auto mt-9 sm:mt-12 items-start">
    <div class="max-w-4xl flex-1 flex flex-col">
      <div class="sm:px-5">
        <div class="pb-6 sm:pb-8 space-y-2 sm:space-y-4">
          <h3 class="font-serif font-medium italic"><?php echo esc_html( $name ); ?></h3>
          <p class="font-sans font-medium text-lg sm:text-2xl"><?php echo esc_html( get_field( 'role' ) ); ?></p>
          <p class="font-sans font-medium text-base sm:text-lg"><?php echo esc_html( get_field( 'qualification' ) ); ?></p>
        </div>
        <div class="text-[1.0625rem] sm:text-lg leading-[1.65] sm:leading-6 font-sans [&>p+p]:mt-5 sm:[&>p+p]:mt-[1lh] text-pretty">
          <?php echo nirvana_kses( get_field( 'bio' ) ); ?>
        </div>
      </div>
      <?php if ( $stats ) : ?>
      <div class="sm:px-4 grid grid-cols-3 mt-5 sm:mt-9 gap-x-2">
        <?php foreach ( $stats as $stat ) : ?>
          <?php
          $number = absint( $stat['number'] ?? 0 );
          $suffix = (string) ( $stat['suffix'] ?? '' );
          ?>
          <div class="flex flex-col items-center justify-center gap-2 font-serif">
            <p class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl stat-count text-wrap" data-target="<?php echo esc_attr( $number ); ?>" data-suffix="<?php echo esc_attr( $suffix ); ?>">
              <?php echo esc_html( $number . $suffix ); ?>
            </p>
            <p class="font-medium text-xs sm:text-sm md:text-base h-10 text-center"><?php echo esc_html( $stat['label'] ?? '' ); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="px-6 md:px-18 mb-4 flex flex-col justify-center items-center relative w-full max-w-4/5 sm:max-w-2/3 lg:max-w-2/5 mx-auto flex-1">
      <?php if ( $photo && wp_attachment_is_image( $photo ) ) : ?>
        <?php
        echo wp_get_attachment_image(
          $photo,
          'large',
          false,
          array(
            'class'         => 'h-fit object-contain md:mx-5',
            'loading'       => 'lazy', // Below the fold.
            'fetchpriority' => 'auto',
            'alt'           => get_post_meta( $photo, '_wp_attachment_image_alt', true ) ?: $alt,
          )
        );
        ?>
      <?php else : ?>
        <img class="h-fit object-contain md:mx-5" src="<?php echo esc_url( nirvana_asset_uri( '/images/founder-picture.webp' ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
      <?php endif; ?>
      <div class="-mt-9 p-3 sm:p-6 -rotate-4 w-[110%] bg-[#CCC8EC]">
        <p class="font-cursive text-[1.375rem] sm:text-2xl leading-[1.35] sm:leading-7 py-2 sm:py-4"><?php echo nirvana_kses_inline( get_field( 'card' ) ); ?></p>
      </div>
    </div>
  </div>
  <div class="sm:py-2.5 sm:px-2.5 flex justify-center w-full mt-9 sm:mt-16">
    <?php nirvana_button( array( 'preset' => 'book' ) ); ?>
  </div>
</section>
