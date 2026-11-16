<?php
/**
 * Template Name: Therapists
 *
 * The therapist listing: the heading (Therapists page → Heading, else the
 * page title), the page content as the intro, then a card per therapist
 * (wp-admin → Therapists, in each one's "Order").
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
  <main id="main" class="therapists-page pt-36 lg:pt-18 pb-16 sm:pb-18 px-6 sm:px-16">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php
      $heading = function_exists( 'get_field' ) ? (string) get_field( 'heading' ) : '';
      ?>
      <h1 class="font-serif text-[2rem] sm:text-[3.5rem] leading-[1.3] text-center"><?php echo '' !== $heading ? nirvana_kses_inline( $heading ) : esc_html( get_the_title() ); ?></h1>

      <?php if ( '' !== trim( get_the_content() ) ) : ?>
      <div class="therapists-intro mx-auto max-w-5xl mt-9 sm:mt-16 font-sans text-base sm:text-lg leading-[150%] text-center">
        <?php the_content(); ?>
      </div>
      <?php endif; ?>
    <?php endwhile; ?>

    <?php $therapists = nirvana_get_therapists(); ?>
    <?php if ( $therapists ) : ?>
    <ul class="mx-auto max-w-[72.5rem] mt-12 sm:mt-16 flex flex-wrap justify-center gap-6 sm:gap-8" aria-label="<?php esc_attr_e( 'Our therapists', 'nirvana-therapy' ); ?>">
      <?php foreach ( $therapists as $therapist ) : ?>
        <li class="w-full lg:w-[calc((100%-2rem)/2)] lg:max-w-[35.25rem]">
          <?php get_template_part( 'parts/therapist-card', null, array( 'therapist' => $therapist ) ); ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </main>
<?php
get_footer();
