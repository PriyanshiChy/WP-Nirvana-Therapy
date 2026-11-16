<?php
/* Template Name: Landing */

/**
 * The landing page is built from the section blocks in /blocks. Edit and
 * reorder them in the block editor.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

  <main id="main">
    <?php
    // wptexturize() would curl the quotes inside Tailwind classes such as
    // [&>svg]:w-full (it reads the ">" as the end of the tag) and break the
    // markup, so it is skipped for the section blocks.
    $texturize = remove_filter( 'the_content', 'wptexturize' );

    while ( have_posts() ) :
      the_post();
      the_content();
    endwhile;

    if ( $texturize ) {
      add_filter( 'the_content', 'wptexturize' );
    }
    ?>
  </main>

<?php get_footer();
