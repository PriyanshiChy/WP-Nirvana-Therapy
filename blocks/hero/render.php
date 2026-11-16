<?php
/**
 * Hero block.
 *
 * @var array $block      Block settings.
 * @var bool  $is_preview True in the editor.
 */

defined( 'ABSPATH' ) || exit;

$background = (int) get_field( 'background' );
$bg_url     = $background ? (string) wp_get_attachment_image_url( $background, 'full' ) : '';

// Overrides the default background set in entry.css (#hero, --hero-bg).
$style = $bg_url ? sprintf( ' style="%s"', esc_attr( '--hero-bg: url(' . esc_url_raw( $bg_url ) . ')' ) ) : '';
?>
<section id="hero"<?php echo $style; // Escaped above. ?>>
  <div class="content [&_a]:w-full! sm:[&_a]:w-fit!">
    <div class="flex flex-col items-center gap-6 md:gap-8">
      <h1><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h1>
      <p><?php echo esc_html( get_field( 'subtitle' ) ); ?></p>
    </div>
    <?php nirvana_button( array( 'preset' => 'book', 'variant' => 'glow' ) ); ?>
  </div>
</section>
