<?php
/* Template Name: Landing */

$content = get_page_content("index");
$buttons = get_component_content("buttons");
?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<?php callComponent("head", [
  "title" => $content["head"]["title"],
  "description" => $content["head"]["description"],
  "keywords" => $content["head"]["keywords"],
]); ?>

<body>
  <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/icons.svg' ); ?>
  <?php get_template_part('parts/header'); ?>
  <main>
    <section id="hero">
      <div class="content">
        <h1><?php echo($content["hero"]["title"]); ?></h1>
        <p><?php echo($content["hero"]["subtitle"]); ?></p>
        <?php callComponent('book-session', ["variant"  =>  "glow"]); ?>
      </div>
    </section>

    <section id="about-us" class="px-16 pt-18 pb-16 text-center">
      <h2><?php echo($content["about-us"]["title"]); ?></h2>
      <div class="mt-14 max-w-5xl mx-auto text-lg leading-[150%]">
        <?php foreach($content["about-us"]["description"] as $item): ?>
          <p><?php echo($item); ?></p><br />
        <?php endforeach; ?>
      </div>
    </section>

    <section id="meet-the-founder">
      <h2><?php echo($content["meet-the-founder"]["title"]); ?></h2>
      <div class="flex gap-14 w-full mx-auto mt-12 items-start">
        <div class="max-w-4xl flex-1 flex flex-col">
          <div class="px-5">
            <div class="py-8 space-y-4">
              <h3 class="font-serif font-medium italic text-5xl"><?php echo($content["meet-the-founder"]["heading"]); ?></h3>
              <p class="font-sans font-medium text-2xl"><?php echo($content["meet-the-founder"]["role"]); ?></p>
              <p class="font-sans font-normal text-lg"><?php echo($content["meet-the-founder"]["qualification"]); ?></p>
            </div>
            <div class="text-lg leading-6 font-sans">
              <?php foreach($content["meet-the-founder"]["description"] as $item): ?>
                <p><?php echo($item); ?></p><br />
              <?php endforeach; ?>
            </div>
          </div>
          <div class="px-4 py-8 flex mt-5">
            <?php foreach($content["meet-the-founder"]["stats"] as $item): ?>
              <div class="flex-1 flex flex-col items-center justify-center gap-2 font-serif">
                <?php
                  preg_match('/^(\d+)(.*)$/', $item["title"], $m);
                  $num = $m[1] ?? '';
                  $suffix = htmlspecialchars($m[2] ?? '');
                ?>
                <p class="text-7xl stat-count" data-target="<?php echo $num; ?>" data-suffix="<?php echo $suffix; ?>">
                  <?php echo($item["title"]); ?>
                </p>
                <p class="font-medium text-base"><?php echo($item["subtitle"]); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="px-10 py-8 flex flex-col relative">
          <img class="max-h-4/5 h-fit object-contain px-5" src="<?php echo get_asset_uri($content["meet-the-founder"]["image"]); ?>" alt="Founder - Nirvana Therapy" />
          <div class="absolute bottom-12 left-1/2 -translate-x-1/2 p-6 -rotate-4 translate-y-full max-w-120 w-full bg-[#CCC8EC]">
            <p class="font-cursive text-2xl leading-7 py-4"><?php echo $content["meet-the-founder"]["card"]; ?></p>
          </div>
        </div>
      </div>
      <div class="p-2.5 flex justify-center w-full mt-16">
        <?php callComponent('book-session', ["variant" => 'primary']); ?>
      </div>
    </section>

    <section id="testimonials" class="py-18 space-y-16">
      <div class="px-16 flex w-full justify-between">
        <h2><?php echo $content["testimonials"]["title"]; ?></h2>
        <div class="flex gap-4">
          <button class="testimonial-carousel-previous link outlined px-2.5!">
            <svg class="size-10 text-primary" aria-hidden="true">
              <use href="#icon-arrow-right" />
            </svg>
          </button>
          <button class="testimonial-carousel-next link outlined px-2.5!">
            <svg class="size-10 text-primary rotate-180" aria-hidden="true">
              <use href="#icon-arrow-right" />
            </svg>
          </button>
        </div>
      </div>
      <div class="testimonial-carousel flex w-full gap-12 items-center overflow-hidden px-16">
        <?php foreach($content["testimonials"]["testimonials"] as $item): ?>
        <div class="h-96 max-w-screen w-90 shrink-0 bg-[#DDE8FE] px-9 pb-10 pt-5 space-y-4 flex flex-col">
          <?php echo(get_asset("/images/icons/quote.svg")); ?>
          <p class="flex-1 line-clamp-10 h-full font-serif overflow-clip text-lg leading-7"><?php echo($item); ?></p>
        </div>
        <?php endforeach ?>
      </div>
      <div class="flex w-full justify-center [&_.link_svg]:size-8">
        <?php
          callComponent('link-button', [
            "children" => [$buttons["read-reviews"]["title"], get_asset("/images/icons/external-link.svg")],
            "variant"  => "underlined",
            "target"   => "_blank",
            "href"     => $buttons["read-reviews"]["href"],
          ]);
        ?>
      </div>
    </section>

    <section id="choose-us" class="px-16 py-18 space-y-16 min-h-screen">
      <h2 class="text-center"><?php echo $content["choose-us"]["title"]; ?></h2>
      <div class="flex w-full items-stretch divide-x-2 divide-primary">
        <?php foreach($content["choose-us"]["description"] as $item): ?>
          <div class="space-y-4 px-9">
            <div class="flex flex-col items-center h-32 justify-between">
              <h4 class="font-serif text-2xl font-medium"><?php echo $item["title"]; ?></h4>
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <?php echo get_asset($item["icon"]); ?>
              </div>
            </div>
            <div class="font-sans text-lg leading-6">
              <?php foreach($item["description"] as $subitem): ?>
                <p><?php echo $subitem ?></p>
                <br />
              <?php endforeach ?>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </section>

    <section id="area-of-expertise" class="px-16 py-18 space-y-16">
      <h2 class="text-center"><?php echo($content["areas-of-expertise"]["title"]); ?></h2>
      <div class="grid grid-cols-3 [&>*:not(:nth-last-child(-n+3))]:border-b-2 *:border-r-2 [&>*:nth-child(3n)]:border-r-0 *:border-primary">
        <?php foreach($content["areas-of-expertise"]["description"] as $item): ?>
          <div class="flip-card h-40">
            <div class="flip-card-inner">
              <div class="flip-card-front flex items-center gap-4 px-9 py-8">
                <?php echo(get_asset($item["image"])) ?>
                <p class="text-2xl font-serif"><?php echo($item["title"]) ?></p>
              </div>
              <div class="flip-card-back px-9 space-y-4 flex flex-col justify-center">
                <?php foreach($item["description"] as $subitem): ?>
                  <p class="text-base font-sans leading-[150%]"><?php echo($subitem) ?></p>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="flex justify-center items-center gap-9 py-6">
        <!-- <p class="font-sans text-2xl"><?php echo($content["areas-of-expertise"]["closing"]); ?></p> -->
        <?php callComponent('contact-us', ["variant" => "primary", "label" => "Start a Conversation"]) ?>
      </div>
    </section>

    <section id="how-it-works" class="p-16">
      <h2><?php echo $content["how-it-works"]["title"]; ?></h1>
      <div class="flex py-8 mt-4 divide-x divide-primary">
        <?php foreach($content["how-it-works"]["description"] as $item): ?>
          <div class="flex flex-1 flex-col items-center px-2.5 gap-4">
            <div class="size-16 [&>svg]:h-full [&>svg]:w-full"><?php echo get_asset($item["image"]) ?></div>
            <div class="flex flex-col items-center gap-4">
              <p class="font-serif font-semibold text-2xl"><?php echo $item["title"]; ?></p>
              <p class="font-sans text-base"><?php echo $item["description"]; ?></p>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="mt-4 py-2 text-center text-2xl font-sans">
        <p><?php echo $content["how-it-works"]["opening_hours"]; ?></p>
      </div>
      <div class="mt-4 flex flex-col items-center text-center gap-y-9">
        <h3 class="leading-[100%]"><?php echo $content["how-it-works"]["heading"]; ?></h3>
        <div class="w-full flex py-8">
          <?php foreach($content["how-it-works"]["steps"] as $item): ?>
            <div class="flex flex-1 flex-col items-center text-center">
              <div class="max-w-48 w-full space-y-4">
                <div class="size-16 [&>svg]:h-full [&>svg]:w-full mx-auto">
                  <?php echo get_asset($item["image"]) ?>
                </div>
                <p class="font-serif font-semibold text-2xl"><?php echo $item["title"] ?></p>
                <p class="font-sans"><?php echo $item["description"] ?></p>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
      <h4 class="font-medium text-2xl mt-4 text-center"><?php echo $content["how-it-works"]["closing"] ?></h4>
    </section>

    <section id="faq" class="px-16 py-18">
      <h2 class="text-center"><?php echo $content["faq"]["heading"] ?></h2>
      <p class="text-center font-serif text-2xl mt-3"><?php echo $content["faq"]["subheading"] ?></p>

      <div class="max-w-4xl mx-auto mt-14" id="faq-accordion">

      <?php foreach($content["faq"]["description"] as $item): ?>
        <div class="faq-item">
          <button class="faq-trigger" aria-expanded="false">
            <span><?php echo $item["question"] ?></span>
            <svg class="faq-icon" aria-hidden="true"><use href="#icon-chevron-down" /></svg>
          </button>
          <div class="faq-answer">
            <p><?php echo $item["answer"] ?></p>
          </div>
        </div>
      <?php endforeach ?>

      </div>

      <div class="flex justify-center items-center gap-8 mt-14">
        <p class="font-sans font-normal text-2xl"><?php echo $content["faq"]["closing"] ?></p>
        <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
      </div>
    </section>
  </main>
  <?php get_template_part('parts/footer'); ?>
</body>

</html>
