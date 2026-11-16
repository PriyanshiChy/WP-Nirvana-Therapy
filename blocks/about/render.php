<?php
/**
 * About block.
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="about-us" class="px-6 pt-9 pb-9 sm:p-16 sm:text-center">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="mt-9 sm:mt-14 max-w-5xl mx-auto text-base sm:text-lg leading-[150%] [&>p+p]:mt-[1lh]">
    <?php echo nirvana_kses( get_field( 'body' ) ); ?>
  </div>
</section>
