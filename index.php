<?php
/**
 * Fallback template (blog index, archives, search, single posts).
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
  <main id="main" class="tnc pt-48 pb-9 sm:py-36 px-6 sm:px-16">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-16' ); ?>>
          <?php
          if ( is_singular() ) {
            the_title( '<h1 class="font-serif text-[1.625rem] sm:text-[2.625rem]">', '</h1>' );
          } else {
            the_title( sprintf( '<h2 class="font-serif text-[1.625rem] sm:text-[2.625rem]"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' );
          }
          ?>
          <div class="entry-content mx-auto max-w-4xl w-full text-base sm:text-lg mt-9 font-sans px-6 sm:px-0">
            <?php is_singular() ? the_content() : the_excerpt(); ?>
          </div>
        </article>
      <?php endwhile; ?>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <p class="font-sans text-base sm:text-lg"><?php esc_html_e( 'No content found.', 'nirvana-therapy' ); ?></p>
    <?php endif; ?>
  </main>
<?php
get_footer();
