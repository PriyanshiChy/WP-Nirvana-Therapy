<?php
/**
 * Default page template: Terms and Conditions and any page the client
 * creates in WP admin. The body is written in the block editor and styled
 * by the .entry-content rules in entry.css.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
  <main id="main" class="tnc pt-48 pb-9 sm:py-36 px-6 sm:px-16">
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php the_title( '<h1 class="font-serif text-[1.625rem] sm:text-[2.625rem]">', '</h1>' ); ?>
        <?php
        $subtitle = function_exists( 'get_field' ) ? (string) get_field( 'subtitle' ) : '';
        if ( '' !== $subtitle ) :
          ?>
        <p class="font-sans text-base sm:text-lg mt-4"><?php echo esc_html( $subtitle ); ?></p>
        <?php endif; ?>
        <div class="entry-content mx-auto max-w-4xl w-full text-base sm:text-lg mt-9 sm:mt-16 font-sans px-6 sm:px-0">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  </main>
<?php
get_footer();
