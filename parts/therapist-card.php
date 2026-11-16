<?php
/**
 * One therapist card on the Therapists page.
 *
 * @var array $args { therapist: WP_Post }
 */

defined( 'ABSPATH' ) || exit;

$therapist = $args['therapist'] ?? null;
if ( ! $therapist instanceof WP_Post ) {
	return;
}

$name      = get_the_title( $therapist );
$role      = nirvana_therapist_role( $therapist );
$expertise = nirvana_therapist_expertise( $therapist );
?>
<article class="therapist-card h-full flex flex-col gap-6 p-5 sm:p-8 bg-foreground shadow-card">
  <div class="flex items-start gap-4 sm:gap-6">
    <?php echo nirvana_therapist_photo( $therapist, 'therapist-card', 'size-24 sm:size-[9.125rem] shrink-0' ); ?>

    <div class="flex flex-col justify-center gap-3 sm:gap-4 min-w-0 self-stretch">
      <div class="flex flex-col gap-2">
        <h2 class="font-serif font-semibold text-xl sm:text-2xl leading-[1.3]"><?php echo esc_html( $name ); ?></h2>
        <?php if ( '' !== $role ) : ?>
        <p class="font-sans text-sm sm:text-base text-text/70!"><?php echo esc_html( $role ); ?></p>
        <?php endif; ?>
      </div>

      <?php echo nirvana_therapist_language_tags( $therapist ); // Escaped inside. ?>

      <?php if ( '' !== $expertise ) : ?>
      <p class="font-sans text-sm leading-[1.2]"><?php echo esc_html( $expertise ); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="therapist-actions mt-auto flex flex-col min-[420px]:flex-row gap-2.5">
    <?php echo nirvana_therapist_book_button( $therapist, array( 'size' => 'small' ) ); // Escaped inside. ?>
    <?php
    nirvana_button(
      array(
        'label'      => __( 'View Profile', 'nirvana-therapy' ),
        'href'       => get_permalink( $therapist ),
        'variant'    => 'outlined',
        'size'       => 'small',
        /* translators: %s: therapist name. */
        'aria_label' => sprintf( __( 'View %s’s profile', 'nirvana-therapy' ), $name ),
      )
    );
    ?>
  </div>
</article>
