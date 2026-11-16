<?php
/**
 * Page not found.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
  <main id="main" class="tnc pt-48 pb-9 sm:py-36 px-6 sm:px-16 min-h-screen">
    <h1 class="font-serif text-[1.625rem] sm:text-[2.625rem]"><?php esc_html_e( 'Page not found', 'nirvana-therapy' ); ?></h1>
    <p class="font-sans text-base sm:text-lg mt-4"><?php esc_html_e( 'The page you are looking for doesn’t exist or has moved.', 'nirvana-therapy' ); ?></p>
    <div class="flex mt-9 sm:mt-16">
      <?php
      nirvana_button(
        array(
          'label' => __( 'Back to home', 'nirvana-therapy' ),
          'href'  => home_url( '/' ),
        )
      );
      ?>
    </div>
  </main>
<?php
get_footer();
