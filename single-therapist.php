<?php
/**
 * A therapist's profile page (/therapists/<name>/): the "View Profile" link
 * on each card.
 *
 * Left: a card with the photo, name, role, experience, education, languages,
 * days & timing, mode of work and the Book button (the therapist's fields).
 * Right: the editor content: "About me", "Approach"… as headings, each
 * section divided by a line (see .therapist-bio in entry.css).
 * Banner: the therapist's Banner image (default: Site Settings → Therapists).
 */

defined( 'ABSPATH' ) || exit;

/**
 * "Label:" in small grey over (or before) a value, as on the profile card.
 */
$nirvana_profile_detail = static function ( string $label, string $value, bool $inline = false ): void {
	if ( '' === $value ) {
		return;
	}
	$value = nl2br( esc_html( $value ) );
	?>
	<?php if ( $inline ) : ?>
	<p class="text-center"><span class="therapist-label"><?php echo esc_html( $label ); ?></span> <span class="therapist-value"><?php echo $value; // Escaped above. ?></span></p>
	<?php else : ?>
	<div class="flex flex-col items-center gap-1 text-center">
		<p class="therapist-label"><?php echo esc_html( $label ); ?></p>
		<p class="therapist-value"><?php echo $value; // Escaped above. ?></p>
	</div>
	<?php endif; ?>
	<?php
};

get_header();
?>
  <main id="main" class="therapist-profile" style="--therapist-bg: url('<?php echo esc_url( nirvana_therapist_banner_url( get_queried_object() ) ); ?>')">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php
      $therapist  = get_post();
      $role       = nirvana_therapist_role( $therapist );
      $experience = nirvana_therapist_field( $therapist, 'experience' );
      $education  = nirvana_therapist_field( $therapist, 'education' );
      $timings    = nirvana_therapist_field( $therapist, 'timings' );
      $mode       = nirvana_therapist_field( $therapist, 'mode_of_work' );
      $languages  = nirvana_therapist_language_tags( $therapist, 'large' );
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'mx-auto max-w-[90rem] flex flex-col lg:flex-row items-start gap-10 lg:gap-16 px-6 sm:px-16 pt-36 lg:pt-27 pb-16 lg:pb-18' ); ?>>
        <aside class="therapist-panel w-full lg:w-[24.667rem] shrink-0 flex flex-col items-center gap-12 px-6 py-12 sm:px-12 sm:py-16" aria-label="<?php esc_attr_e( 'Therapist details', 'nirvana-therapy' ); ?>">
          <div class="flex flex-col items-center gap-6 w-full">
            <?php echo nirvana_therapist_photo( $therapist, 'therapist-profile', 'size-40 shrink-0' ); ?>

            <?php the_title( '<h1 class="font-serif font-semibold text-[2rem] sm:text-[2.5rem] leading-[1.275] text-center">', '</h1>' ); ?>

            <div class="flex flex-col items-center gap-4 w-full">
              <?php if ( '' !== $role ) : ?>
              <p class="font-sans text-lg sm:text-xl text-text/70! text-center"><?php echo esc_html( $role ); ?></p>
              <?php endif; ?>
              <?php $nirvana_profile_detail( __( 'Exp:', 'nirvana-therapy' ), $experience, true ); ?>
              <?php $nirvana_profile_detail( __( 'Education:', 'nirvana-therapy' ), $education ); ?>
            </div>
          </div>

          <?php if ( $languages || $timings || $mode ) : ?>
          <div class="flex flex-col items-center gap-4 w-full">
            <?php if ( $languages ) : ?>
            <div class="flex flex-col items-center gap-1">
              <p class="therapist-label"><?php esc_html_e( 'Languages:', 'nirvana-therapy' ); ?></p>
              <?php echo $languages; // Escaped inside. ?>
            </div>
            <?php endif; ?>
            <?php $nirvana_profile_detail( __( 'Days & Timing:', 'nirvana-therapy' ), $timings ); ?>
            <?php $nirvana_profile_detail( __( 'Mode of Work:', 'nirvana-therapy' ), $mode, true ); ?>
          </div>
          <?php endif; ?>

          <div class="therapist-book flex flex-col gap-2 w-full">
            <p class="font-sans text-base text-center leading-[1.2] py-[0.3rem]"><?php echo esc_html( nirvana_therapist_booking_prompt( $therapist ) ); ?></p>
            <?php echo nirvana_therapist_book_button( $therapist ); // Escaped inside. ?>
          </div>
        </aside>

        <?php if ( '' !== trim( get_the_content() ) ) : ?>
        <div class="therapist-bio flex-1 min-w-0 lg:pt-27 lg:pr-9 font-sans text-base sm:text-lg">
          <?php the_content(); ?>
        </div>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
  </main>
<?php
get_footer();
