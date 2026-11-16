<?php
/**
 * About block.
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="about-us" class="px-6 pt-9 pb-9 sm:p-16 sm:text-center">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="mt-9 sm:mt-14 max-w-5xl mx-auto text-[1.0625rem] sm:text-lg leading-[1.65] sm:leading-[150%] [&>p+p]:mt-5 sm:[&>p+p]:mt-[1lh] text-pretty">
    <?php echo nirvana_kses( get_field( 'body' ) ); ?>
  </div>
</section>
