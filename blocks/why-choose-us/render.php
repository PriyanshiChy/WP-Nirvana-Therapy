<?php
/**
 * Why Choose Us block.
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="choose-us" class="px-6 pb-26 pt-9 sm:p-16 space-y-9 sm:space-y-16 min-h-screen">
  <h2><?php echo nirvana_kses_inline( get_field( 'title' ) ); ?></h2>
  <div class="flex flex-col lg:flex-row w-full items-stretch lg:divide-x-2 divide-primary space-y-9">
    <?php foreach ( (array) get_field( 'items' ) as $item ) : ?>
      <div class="space-y-4 sm:px-9">
        <div class="flex flex-col-reverse items-start sm:items-center h-32 justify-between">
          <h3 class="font-serif text-xl! md:text-2xl! font-medium"><?php echo nirvana_kses_inline( $item['title'] ?? '' ); ?></h3>
          <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
            <?php echo nirvana_icon( (string) ( $item['icon'] ?? '' ) ); ?>
          </div>
        </div>
        <div class="font-sans text-[1.0625rem] md:text-lg leading-[1.65] md:leading-6 [&>p+p]:mt-5 md:[&>p+p]:mt-[1lh] text-pretty">
          <?php echo nirvana_kses( $item['body'] ?? '' ); ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
