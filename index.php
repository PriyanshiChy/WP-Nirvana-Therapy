<?php
/**
 * The main template file
 *
 * @package Nirvana_Painted
 */
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Welcome to Nirvana Therapy</title>

  <!-- Favicon -->
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicon.ico" type="image/x-icon" />

  <!-- Theme main stylesheet (style.css in theme root) -->
  <link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" />

  <!-- Google Fonts example -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Reenie+Beanie&display=swap" rel="stylesheet">

  <!-- JS asset in head (rare; usually footer) -->
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/head-script.js" defer></script>

  <!-- WordPress hook: VERY important for plugins & enqueued assets -->
  <!-- <?php wp_head(); ?> -->
</head>


<body>
  <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/icons.svg' ); ?>
  <?php get_template_part('parts/header'); ?>
  <main>
    <section id="hero">
      <div class="content">
        <h1>
          A place where your <span class="font-semibold italic">feelings</span> matter
        </h1>
        <p>A safe, confidential space to explore your thoughts, emotions, and experiences without judgment.</p>
        <?php callComponent('book-session', ["variant"   => "glow"]); ?>
      </div>
    </section>

    <section id="about" class="px-16 mt-18 pb-16 text-center">
      <h2>Welcome to Nirvana Therapy</h2>
      <div class="mt-14 max-w-5xl mx-auto text-lg leading-[150%]">
        <p>Nirvana Therapy was created as a space where people can slow down and feel supported. The word Nirvana refers to liberation, a release from suffering and the patterns that keep us feeling stuck. In this context, it does not imply the absence of pain or difficulty, but the possibility of relating to our experiences with greater awareness and freedom.</p>
        <br />
        <p>This practice was shaped around that idea: not forcing change, but creating space for understanding to emerge. <br/>Therapy can be helpful during many moments in life whether you're dealing with stress, emotional exhaustion, relationship struggles, grief, past experiences that still linger, or simply a feeling that something isn’t quite right. You don’t need to arrive with clear answers. Therapy here begins with listening and unfolds gently, guided by what feels most relevant to you.</p>
        <br />
        <p>At Nirvana Therapy, sessions are collaborative, gentle, and grounded. Together, we explore your thoughts, emotions, and patterns at a pace that feels safe and manageable. The goal isn’t to “fix” you, but to help you understand yourself with greater clarity and self-compassion.</p>
        <br />
        <p>If you’re looking for a space to pause, reflect, and gradually find more freedom in how you relate to yourself and your life, Nirvana Therapy is here to support you. Let’s talk, explore, and make space for the life you deserve.</p>
      </div>
    </section>

    <section id="about-founder">
      <h2>Meet the Founder</h2>
      <div class="flex gap-14 w-full mx-auto mt-16 items-start">
        <div class="max-w-4xl flex-1 flex flex-col">
          <div class="px-5">
            <div class="py-8 space-y-4">
              <h3 class="font-serif font-medium italic text-5xl">Hey, I am Shreya!</h3>
              <p class="font-sans font-medium text-2xl">Counselling Psychologist </p>
              <p class="font-sans font-normal text-lg">MSc in Psychology (Clinical), PGD Counselling Psychology</p>
            </div>
            <div class="text-lg leading-6 font-sans mt-4">
              <p>My journey into therapy was shaped by something I’ve often been called- “sensitive”. What once felt like something I needed to hide has become one of my greatest strengths. Feeling deeply allows me to understand people beyond their words, notice what remains unspoken, and hold space for emotions with care and compassion.</p>
              <br />
              <p>In our sessions, I work with you, not on you. Together, we create a space that feels safe, reflective, and deeply human  where you can explore your emotions, understand your experiences, and move toward healing and growth.</p>
              <br />
              <p>My approach to therapy is rooted in empathy, curiosity, and emotional attunement. I draw from Emotionally Focused Therapy (EFT), Cognitive Behavioral Therapy (CBT), Acceptance and Commitment Therapy (ACT), and trauma-informed practices, adapting my work to each person’s unique needs and pace.</p>
              <br />
              <p>With two years of counselling experience and a strong passion for breaking the stigma around mental health, At Nirvana Therapy, our goal is to help you feel lighter, more in control and connected to yourself.</p>
            </div>
          </div>
          <div class="px-4 py-8 flex mt-5">
            <div class="flex-1 flex flex-col items-center justify-center gap-2 font-serif">
              <p class="text-7xl">2+</p>
              <p class="font-medium text-base">Years of Experience</p>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center gap-2 font-serif">
              <p class="text-7xl">500+</p>
              <p class="font-medium text-base">Hours of Therapy</p>
            </div>
            <div class="flex-1 flex flex-col items-center justify-center gap-2 font-serif">
              <p class="text-7xl">60+</p>
              <p class="font-medium text-base">Clients</p>
            </div>
          </div>
        </div>
        <div class="px-10 py-8 flex flex-col relative">
          <img class="max-h-4/5 h-fit object-contain px-5" src="<?php echo get_template_directory_uri(); ?>/assets/images/founder.png" alt="Founder - Nirvana Therapy" />
          <div class="absolute bottom-12 left-1/2 -translate-x-1/2 p-6 -rotate-4 translate-y-full max-w-120 w-full bg-[#CCC8EC]">
            <p class="font-cursive text-2xl leading-7 py-4">I believe therapy isn’t just about fixing problems, it’s about feeling seen, heard, and understood. At its core, therapy is a space where you can slow down, make sense of your experiences, and feel less alone in what you’re carrying. </p>
          </div>
        </div>
      </div>
      <div class="p-2.5 flex justify-center w-full mt-16">
        <?php
        callComponent('book-session', [
          "variant"  => 'primary',
        ]); ?>
      </div>
    </section>

    <section id="testimonials" class="px-16 py-18 space-y-16">
      <div class="flex w-full justify-between">
        <h3>What are people saying</h3>
        <div class="flex gap-4">
          <button class="link outlined px-2.5!">
            <svg class="size-10 text-primary" aria-hidden="true">
              <use href="#icon-arrow-right" />
            </svg>
          </button>
          <button class="link outlined px-2.5!">
            <svg class="size-10 text-primary rotate-180" aria-hidden="true">
              <use href="#icon-arrow-right" />
            </svg>
          </button>
        </div>
      </div>
      <div class="flex w-full gap-12"></div>
      <div class="flex w-full justify-center">
        <?php
          callComponent('link-button', [
            "children"  => ["Read more Reviews", '<svg class="size-8 text-primary rotate-135" aria-hidden="true"><use href="#icon-arrow-right" /></svg>'],
            "variant"   => "underlined",
            "target"    => "_blank",
            // TODO: Add review link @pchy
            "href"      => "#",
          ]);
        ?>
      </div>
    </section>

    <section id="choose-us" class="px-16 py-18 space-y-16 min-h-screen">
      <h3>Why Choose Nirvana Therapy?</h3>
      <div class="flex w-full items-stretch">
        <div class="space-y-4 px-9">
          <div class="flex flex-col items-center h-32 justify-between">
            <h4 class="font-serif text-2xl font-medium">Trust & Emotional Safety</h4>
            <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
              <!-- add svg -->
            </div>
          </div>
          <div class="font-sans text-lg leading-6">
            <p>Choosing a therapist requires trust and a sense of safety. Nirvana Therapy offers a calm, non-judgmental space where you can slow down, speak openly, and explore your experiences without feeling rushed or dismissed.</p>
            <br />
            <p>Careful listening and clear professional boundaries create consistency and emotional safety throughout the process.</p>
          </div>
        </div>
        <div class="space-y-4 px-9 border-x border-primary">
          <div class="flex flex-col items-center h-32 justify-between">
            <h4 class="font-serif text-2xl font-medium">Evidence-Based & Structured Care</h4>
            <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
              <!-- add svg -->
            </div>
          </div>
          <div class="font-sans text-lg leading-6">
            <p>Choosing a therapist requires trust and a sense of safety. Nirvana Therapy offers a calm, non-judgmental space where you can slow down, speak openly, and explore your experiences without feeling rushed or dismissed.</p>
            <br />
            <p>Careful listening and clear professional boundaries create consistency and emotional safety throughout the process.</p>
          </div>
        </div>
        <div class="space-y-4 px-9">
          <div class="flex flex-col items-center h-32 justify-between">
            <h4 class="font-serif text-2xl font-medium">Trust & Emotional Safety</h4>
            <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
              <!-- add svg -->
            </div>
          </div>
          <div class="font-sans text-lg leading-6">
            <p>Choosing a therapist requires trust and a sense of safety. Nirvana Therapy offers a calm, non-judgmental space where you can slow down, speak openly, and explore your experiences without feeling rushed or dismissed.</p>
            <br />
            <p>Careful listening and clear professional boundaries create consistency and emotional safety throughout the process.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="services">
    </section>

    <section id="how-it-works">
    </section>

    <section id="faq">
    </section>
  </main>
  <footer></footer>
</body>

</html>
