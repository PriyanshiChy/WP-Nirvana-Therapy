<?php
/**
 * The main template file
 *
 * @package Nirvana_Painted
 */

$content = get_page_content("index")
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
  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js" defer></script>

  <!-- WordPress hook: VERY important for plugins & enqueued assets -->
  <!-- <?php wp_head(); ?> -->
</head>


<body>
  <script><?php echo($content["raw"]); ?></script>
  <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/icons.svg' ); ?>
  <?php get_template_part('parts/header'); ?>
  <main>
    <section id="hero">
      <div class="content">
        <h1>
          A place where your <span class="font-semibold italic">feelings</span> matter
        </h1>
        <p>A safe, confidential space to explore your thoughts, emotions, and experiences without judgment.</p>
        <?php callComponent('book-session', ["variant"  =>  "glow"]); ?>
      </div>
    </section>

    <section id="about" class="px-16 pt-18 pb-16 text-center">
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
      <h3 class="text-center">Why Choose Nirvana Therapy?</h3>
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

    <section id="how-it-works" class="p-16">
      <h2>Start your journey with us</h1>
      <div class="flex py-8 mt-4">
        <div class="flex flex-1 flex-col items-center px-2.5 gap-4 border-r border-r-primary">
          <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
            <!-- add svg -->
          </div>
          <div class="flex flex-col items-center gap-4">
            <p class="font-serif font-semibold text-2xl">Offline Session</p>
            <p class="font-sans text-base">In Kolkata</p>
          </div>
        </div>
        <div class="flex flex-1 flex-col items-center px-2.5 gap-4">
          <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
            <!-- add svg -->
          </div>
          <div class="flex flex-col items-center gap-4">
            <p class="font-serif font-semibold text-2xl">Offline Session</p>
            <p class="font-sans text-base">In Kolkata</p>
          </div>
        </div>
      </div>
      <div class="mt-4 py-2 text-center text-2xl font-sans">
        <p>Opening Hours- 12pm - 7pm Monday to Saturday</p>
      </div>
      <div class="mt-4 flex flex-col items-center text-center gap-y-9">
        <h3>How to Book a Consultation</h3>
        <div class="w-full flex py-8">
          <div class="flex flex-1 flex-col items-center text-center">
            <div class="max-w-48 w-full space-y-4">
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <!-- add svg -->
              </div>
              <p class="font-serif font-semibold text-2xl">
                1.<br/>Register
              </p>
              <p class="font-sans">
                Click the link “Register” and fill out the registration form with your basic details.
              </p>
            </div>
          </div>
          <div class="flex flex-1 flex-col items-center text-center">
            <div class="max-w-48 w-full space-y-4">
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <!-- add svg -->
              </div>
              <p class="font-serif font-semibold text-2xl">
                1.<br/>Register
              </p>
              <p class="font-sans">
                Click the link “Register” and fill out the registration form with your basic details.
              </p>
            </div>
          </div>
          <div class="flex flex-1 flex-col items-center text-center">
            <div class="max-w-48 w-full space-y-4">
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <!-- add svg -->
              </div>
              <p class="font-serif font-semibold text-2xl">
                1.<br/>Register
              </p>
              <p class="font-sans">
                Click the link “Register” and fill out the registration form with your basic details.
              </p>
            </div>
          </div>
          <div class="flex flex-1 flex-col items-center text-center">
            <div class="max-w-48 w-full space-y-4">
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <!-- add svg -->
              </div>
              <p class="font-serif font-semibold text-2xl">
                1.<br/>Register
              </p>
              <p class="font-sans">
                Click the link “Register” and fill out the registration form with your basic details.
              </p>
            </div>
          </div>
        </div>
      </div>
      <h4 class="font-medium text-[1.625rem] mt-4 text-center">Voila! Your healing journey begins now </h4>
    </section>

    <section id="faq" class="px-16 py-18">
      <h3 class="text-center">We have got the <span class="italic font-semibold">answers</span></h3>
      <p class="text-center font-serif text-2xl mt-3">To Frequently asked questions</p>

      <div class="max-w-4xl mx-auto mt-14" id="faq-accordion">

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>What actually happens in therapy?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>In a therapy session, you and your therapist talk openly about what's on your mind, your feelings, and your experiences. Sessions are guided by what feels most relevant to you. There's no fixed script — it's a collaborative conversation that unfolds at your pace.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>How do I know if I need therapy?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>You don't need to be in a crisis to benefit from therapy. If you've been feeling stressed, emotionally exhausted, stuck in patterns, or simply feel that something isn't quite right, therapy can help. Reaching out early is often more effective than waiting until things feel unbearable.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>Is everything I share in therapy confidential?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>Yes. Confidentiality is a cornerstone of therapy. Everything you share stays between you and your therapist. There are only a few rare exceptions required by law — such as if there is a serious risk of harm to you or someone else — and these will always be discussed with you transparently.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>Do you offer online as well as in-person sessions?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>Yes, both options are available. In-person sessions are held in Kolkata, and online sessions are available via video call for those who prefer to connect from the comfort of their own space or are located elsewhere.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>How long is each therapy session?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>Each session is typically 50 minutes long. This provides enough time to explore what's on your mind while keeping the conversation focused and intentional.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>How long does therapy usually last?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>The duration varies from person to person depending on what you're working through. Some people find short-term therapy of 8–12 sessions helpful, while others benefit from longer ongoing work. This is something you'll explore together with your therapist based on your goals and progress.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>Can I ask my queries before booking a paid session?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>Absolutely. You're welcome to reach out with any questions before committing to a session. A brief introductory call can help clarify whether this feels like the right fit for you.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>How much does therapy cost?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>Session fees will be shared during your initial inquiry. Please reach out via the contact details below and we'll provide you with the current pricing and any available options.</p>
          </div>
        </div>

        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span>Is this a crisis or emergency service?</span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer" hidden>
            <p>No. Nirvana Therapy is not a crisis or emergency service. If you or someone you know is in immediate danger, please contact emergency services or a crisis helpline. Therapy here is intended for ongoing support, reflection, and personal growth.</p>
          </div>
        </div>

      </div>

      <div class="flex justify-center items-center gap-8 mt-14">
        <p class="font-sans font-normal text-2xl">Need More Information?</p>
        <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
      </div>
    </section>
  </main>
  <footer></footer>
</body>

</html>
