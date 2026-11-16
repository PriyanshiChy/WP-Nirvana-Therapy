<?php
/**
 * Sample therapists for Local development: four copies of the Figma sample
 * card, plus the Therapists page (Therapists template, intro and heading).
 *
 * Run from the WordPress root:
 *   wp eval-file wp-content/themes/nirvana-therapy/tools/seed-therapists.php [photo-attachment-id]
 *
 * Only runs when there are no therapists yet, so it can't overwrite real
 * entries. Not shipped in the theme zip.
 */

defined( 'ABSPATH' ) || exit;

$photo = (int) ( $args[0] ?? 0 );

// Never touch real therapists: this only seeds an empty site.
$real = get_posts( array( 'post_type' => 'therapist', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
if ( $real ) {
	WP_CLI::error( 'Therapists already exist. This script only seeds a site with none, so it never overwrites real entries.' );
}

// --- Therapists page -------------------------------------------------------

$page = get_page_by_path( 'therapists' );
$intro = array(
	'At Nirvana Therapy, we bring together a team of psychologists with diverse expertise and therapeutic approaches, united by a shared commitment to making mental health care compassionate, personalised and accessible.',
	'Get to know our team and find the support that feels right for you.',
);
$content = implode(
	"\n\n",
	array_map(
		static fn( string $text ): string => "<!-- wp:paragraph -->\n<p>" . esc_html( $text ) . "</p>\n<!-- /wp:paragraph -->",
		$intro
	)
);

$page_id = $page ? $page->ID : 0;
$page_id = wp_insert_post(
	array(
		'ID'            => $page_id,
		'post_title'    => 'Therapists',
		'post_name'     => 'therapists',
		'post_content'  => $content,
		'post_status'   => 'publish',
		'post_type'     => 'page',
		'page_template' => 'page-therapists.php',
	),
	true
);
if ( is_wp_error( $page_id ) ) {
	WP_CLI::error( $page_id->get_error_message() );
}
update_field( 'field_nv_therapists_heading', 'Find the right therapist <i>for you.</i>', $page_id );
WP_CLI::log( "Therapists page: {$page_id}" );

// --- Therapists ------------------------------------------------------------

$about = array(
	'Growing up, I often heard my friends call me an “emotional healer.” I was the person they turned to for a listening ear, a calm presence, and a space where they could simply be themselves. Over time, I began to recognise my inclination towards healing and spirituality. It was evident that this was more than just a role I played, it was one of my greatest strengths.',
	'I have always felt deeply and been drawn to understanding what lies beneath the words people speak: the emotions that remain unexpressed, the experiences that shape us, and the things we may not yet know how to put into words. I believe in holding these emotions with care, curiosity, and compassion.',
	'In our sessions, we work together to create a safe, reflective and judgement-free space that allows you to feel, express and explore your emotions and experiences, all while moving forward at a pace that feels comfortable to you.',
);
$approach = array(
	'My approach to therapy is empathetic, curiosity-driven, collaborative, non-judgemental and integrative. I draw elements from Cognitive-Behaviour Therapy (CBT), Acceptance and Commitment Therapy (ACT), Compassion-Focused Therapy, Somatic Healing, emotion focused work, trauma informed approaches and skills drawn from Dialectical Behaviour Therapy (DBT), tailored to each client or couple’s needs and goals.',
	'In couples therapy, I focus on understanding emotional and relational needs and dynamics that influence how a couple connects. I draw from Bowen Family Systems Theory and the Gottman Method to explore family influences, strengthen trust, improve communication, navigate conflict and build healthier relationships.',
	'I work towards helping clients navigate anxiety, relationship difficulties, emotional overwhelm, self-doubt, trauma, life transitions, and the feeling of being stuck. At the heart of my work is the hope that you feel heard and supported, just as you are.',
);

$heading   = static fn( string $text ): string => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $text ) . "</h2>\n<!-- /wp:heading -->";
$paragraph = static fn( string $text ): string => "<!-- wp:paragraph -->\n<p>" . esc_html( $text ) . "</p>\n<!-- /wp:paragraph -->";

$sample = array(
	'role'      => 'Counselling Psychologist',
	'fields'    => array(
		'field_nv_therapist_experience' => '5+ years',
		'field_nv_therapist_education'  => 'MSc in Psychological Counselling; Certification courses in CBT, ACT, Trauma Informed Therapy, and Couples Therapy',
		'field_nv_therapist_timings'    => 'Tuesday, Thursday and Saturday (12-5pm)',
		'field_nv_therapist_mode'       => 'Online',
	),
	'languages' => array( 'English', 'Hindi' ),
	'expertise' => array( 'Couple Therapy', 'Relationship issues', 'Anxiety', 'Dysfunctional Families', 'Overthinking' ),
	'content'   => implode(
		"\n\n",
		array_merge(
			array( $heading( 'About me' ) ),
			array_map( $paragraph, $about ),
			array( $heading( 'Approach' ) ),
			array_map( $paragraph, $approach )
		)
	),
);

for ( $i = 1; $i <= 4; $i++ ) {
	$slug     = 1 === $i ? 'shreya-agrawal' : "shreya-agrawal-{$i}";
	$id = wp_insert_post(
		array(
			'post_type'    => 'therapist',
			'post_title'   => 'Shreya Agrawal',
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'menu_order'   => $i,
			'post_content' => $sample['content'],
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( $id->get_error_message() );
		continue;
	}

	update_field( 'field_nv_therapist_role', $sample['role'], $id );
	foreach ( $sample['fields'] as $key => $value ) {
		update_field( $key, $value, $id );
	}
	wp_set_object_terms( $id, $sample['languages'], 'therapist_language' );
	wp_set_object_terms( $id, $sample['expertise'], 'therapist_expertise' );
	if ( $photo && wp_attachment_is_image( $photo ) ) {
		set_post_thumbnail( $id, $photo );
	}

	WP_CLI::log( "Created: {$slug} ({$id})" );
}

WP_CLI::success( 'Done.' );
