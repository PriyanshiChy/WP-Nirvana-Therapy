<?php
/**
 * FAQ block: accordion + FAQPage structured data.
 *
 * @var bool $is_preview True in the editor.
 */

defined( 'ABSPATH' ) || exit;

$items = array_values(
	array_filter(
		(array) get_field( 'items' ),
		static fn( $item ): bool => ! empty( $item['question'] )
	)
);
$closing = (string) get_field( 'closing' );
?>
<section id="faq" class="px-6 pt-9 pb-9 sm:p-16">
  <h2><?php echo nirvana_kses_inline( get_field( 'heading' ) ); ?></h2>
  <p class="font-serif text-base sm:text-2xl mt-3 sm:text-center"><?php echo esc_html( get_field( 'subheading' ) ); ?></p>

  <div class="max-w-4xl mx-auto mt-6 sm:mt-14" id="faq-accordion">
  <?php foreach ( $items as $i => $item ) : ?>
    <div class="faq-item">
      <button type="button" class="faq-trigger" id="faq-trigger-<?php echo (int) $i; ?>" aria-expanded="false" aria-controls="faq-answer-<?php echo (int) $i; ?>">
        <span><?php echo esc_html( $item['question'] ); ?></span>
        <svg class="faq-icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-down" /></svg>
      </button>
      <div class="faq-answer" id="faq-answer-<?php echo (int) $i; ?>" role="region" aria-labelledby="faq-trigger-<?php echo (int) $i; ?>" inert>
        <p><?php echo nirvana_kses_inline( $item['answer'] ?? '' ); ?></p>
      </div>
    </div>
  <?php endforeach; ?>
  </div>

  <div class="flex flex-col sm:flex-row justify-center items-center gap-8 mt-9 sm:mt-14">
    <?php if ( $closing ) : ?>
    <p class="font-sans font-normal text-lg sm:text-2xl"><?php echo nirvana_kses_inline( $closing ); ?></p>
    <?php endif; ?>
    <?php nirvana_button( array( 'preset' => 'whatsapp', 'variant' => 'outlined' ) ); ?>
  </div>
</section>
<?php
if ( ! $is_preview && $items ) {
	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => array_map(
			static fn( array $item ): array => array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $item['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => trim( wp_strip_all_tags( $item['answer'] ?? '' ) ),
				),
			),
			$items
		),
	);

	// JSON_HEX_TAG turns < and > into < / >, so nothing can close the <script>.
	printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $schema, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE ) );
}
