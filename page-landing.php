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
      <div class="content [&_a]:w-full! sm:[&_a]:w-fit!">
        <div class="flex flex-col items-center gap-6 md:gap-8">
          <h1><?php echo($content["hero"]["title"]); ?></h1>
          <p><?php echo($content["hero"]["subtitle"]); ?></p>
        </div>
        <?php callComponent('book-session', ["variant"  =>  "glow"]); ?>
      </div>
    </section>

    <section id="about-us" class="px-6 pt-26 pb-9 sm:p-16 sm:text-center">
      <h2><?php echo($content["about-us"]["title"]); ?></h2>
      <div class="mt-9 sm:mt-14 max-w-5xl mx-auto text-base sm:text-lg leading-[150%]">
        <?php $desc = $content["about-us"]["description"]; $last = array_key_last($desc); ?>
        <?php foreach($desc as $k => $item): ?>
          <p><?php echo($item); ?></p><?php if($k !== $last): ?><br /><?php endif; ?>
        <?php endforeach; ?>
      </div>
    </section>

    <section id="meet-the-founder" class="px-6 pt-26 pb-9 sm:p-16">
      <h2><?php echo($content["meet-the-founder"]["title"]); ?></h2>
      <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-14 w-full mx-auto mt-9 sm:mt-12 items-start">
        <div class="max-w-4xl flex-1 flex flex-col">
          <div class="sm:px-5">
            <div class="sm:pb-8 space-y-4">
              <h3 class="font-serif font-medium italic"><?php echo($content["meet-the-founder"]["heading"]); ?></h3>
              <p class="font-sans font-medium text-lg sm:text-2xl"><?php echo($content["meet-the-founder"]["role"]); ?></p>
              <p class="font-sans font-normal text-base sm:text-lg"><?php echo($content["meet-the-founder"]["qualification"]); ?></p>
            </div>
            <div class="text-base sm:text-lg leading-6 font-sans">
              <?php $founder_desc = $content["meet-the-founder"]["description"]; $founder_last = array_key_last($founder_desc); ?>
              <?php foreach($founder_desc as $k => $item): ?>
                <p><?php echo($item); ?></p><?php if($k !== $founder_last): ?><br /><?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="sm:px-4 grid grid-cols-3 mt-5 sm:mt-9 gap-x-2">
            <?php foreach($content["meet-the-founder"]["stats"] as $item): ?>
              <div class="flex flex-col items-center justify-center gap-2 font-serif">
                <?php
                  preg_match('/^(\d+)(.*)$/', $item["title"], $m);
                  $num = $m[1] ?? '';
                  $suffix = htmlspecialchars($m[2] ?? '');
                ?>
                <p class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl stat-count text-wrap" data-target="<?php echo $num; ?>" data-suffix="<?php echo $suffix; ?>">
                  <?php echo($item["title"]); ?>
                </p>
                <p class="font-medium text-xs sm:text-sm md:text-base h-10 text-center"><?php echo($item["subtitle"]); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="px-6 md:px-18 mb-4 flex flex-col justify-center items-center relative w-full max-w-4/5 sm:max-w-2/3 lg:max-w-2/5 mx-auto flex-1">
          <img class="h-fit object-contain md:mx-5" src="<?php echo get_asset_uri($content["meet-the-founder"]["image"]); ?>" alt="Founder - Nirvana Therapy" />
          <div class="-mt-9 p-3 sm:p-6 -rotate-4 w-[110%] bg-[#CCC8EC]">
            <p class="font-cursive text-lg sm:text-2xl leading-7 py-2 sm:py-4"><?php echo $content["meet-the-founder"]["card"]; ?></p>
          </div>
        </div>
      </div>
      <div class="sm:py-2.5 sm:px-2.5 flex justify-center w-full mt-9 sm:mt-16">
        <?php callComponent('book-session', ["variant" => 'primary']); ?>
      </div>
    </section>

    <section id="testimonials" class="px-6 pt-26 pb-9 sm:py-16 sm:space-y-16">
      <div class="px-6 sm:px-16 flex w-full justify-between">
        <h2><?php echo $content["testimonials"]["title"]; ?></h2>
        <div class="hidden sm:flex gap-4">
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
      <div class="testimonial-carousel flex w-full gap-12 items-center overflow-hidden sm:px-16 mt-9">
        <?php foreach($content["testimonials"]["testimonials"] as $item): ?>
        <div class="h-96 max-w-full w-90 shrink-0 bg-[#DDE8FE] px-9 pb-10 pt-5 space-y-4 flex flex-col">
          <?php echo(get_asset("/images/icons/quote.svg")); ?>
          <p class="flex-1 line-clamp-10 h-full font-serif overflow-clip text-lg leading-7"><?php echo($item); ?></p>
        </div>
        <?php endforeach ?>
      </div>
      <div class="flex sm:hidden justify-center gap-4 mt-6">
        <button class="testimonial-carousel-previous link outlined icon px-2.5!">
          <svg class="size-10 text-primary" aria-hidden="true">
            <use href="#icon-arrow-right" />
          </svg>
        </button>
        <button class="testimonial-carousel-next link outlined icon px-2.5!">
          <svg class="size-10 text-primary rotate-180" aria-hidden="true">
            <use href="#icon-arrow-right" />
          </svg>
        </button>
      </div>
      <div class="flex w-full justify-center [&_.link_svg]:size-8 sm:px-6 mt-9">
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

    <section id="choose-us" class="px-6 py-26 sm:p-16 space-y-16 min-h-screen">
      <h2><?php echo $content["choose-us"]["title"]; ?></h2>
      <div class="flex flex-col lg:flex-row w-full items-stretch lg:divide-x-2 divide-primary space-y-9">
        <?php foreach($content["choose-us"]["description"] as $item): ?>
          <div class="space-y-4 sm:px-9">
            <div class="flex flex-col-reverse items-start sm:items-center h-32 justify-between">
              <h4 class="font-serif text-xl md:text-2xl font-medium"><?php echo $item["title"]; ?></h4>
              <div class="size-16 [&>svg]:h-full [&>svg]:w-full">
                <?php echo get_asset($item["icon"]); ?>
              </div>
            </div>
            <div class="font-sans text-base md:text-lg leading-6">
              <?php $choose_last = array_key_last($item["description"]); ?>
              <?php foreach($item["description"] as $k => $subitem): ?>
                <p><?php echo $subitem ?></p><?php if($k !== $choose_last): ?><br /><?php endif; ?>
              <?php endforeach ?>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </section>

    <section id="area-of-expertise" class="px-6 pt-26 pb-9 sm:p-16 space-y-9 sm:space-y-16">
      <h2><?php echo($content["areas-of-expertise"]["title"]); ?></h2>
      <div class="hidden lg:grid grid-cols-3 [&>*:not(:nth-last-child(-n+3))]:border-b-2 *:border-r-2 [&>*:nth-child(3n)]:border-r-0 *:border-primary">
        <?php foreach($content["areas-of-expertise"]["description"] as $item): ?>
          <div class="flip-card h-40">
            <div class="flip-card-inner">
              <div class="flip-card-front flex items-center gap-4 px-9 py-8 [&_svg]:shrink-0">
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
      <div class="lg:hidden max-w-md mx-auto">
        <?php foreach($content["areas-of-expertise"]["description"] as $item): ?>
          <div class="flex gap-4 py-4 px-5 items-center w-fit">
            <div class="size-24 [&>svg]:h-full [&>svg]:w-full shrink-0">
              <?php echo(get_asset($item["image"])) ?>
            </div>
            <div class="flex flex-col gap-4">
              <p class="text-xl font-serif font-medium"><?php echo($item["title"]) ?></p>
              <div class="font-sans text-sm leading-[120%]">
                <?php $mobile_last = array_key_last($item["description"]); ?>
                <?php foreach($item["description"] as $k => $subitem): ?>
                  <p><?php echo($subitem) ?></p><?php if($k !== $mobile_last): ?><br /><?php endif; ?>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="flex justify-center items-center gap-9 sm:py-6">
        <?php callComponent('contact-us', ["variant" => "primary", "label" => "Start a Conversation"]) ?>
      </div>
    </section>

    <section id="how-it-works" class="px-6 pt-26 pb-9 sm:p-16 space-y-9">
      <h2><?php echo $content["how-it-works"]["title"]; ?></h1>
      <div class="flex md:py-8 md:mt-4 divide-x-2 divide-primary">
        <?php foreach($content["how-it-works"]["description"] as $item): ?>
          <div class="flex flex-1 flex-col items-center text-center justify-center px-2.5 gap-4">
            <div class="size-16 [&>svg]:h-full [&>svg]:w-full"><?php echo get_asset($item["image"]) ?></div>
            <div class="flex flex-col items-center gap-4">
              <p class="font-serif font-semibold text-lg sm:text-2xl"><?php echo $item["title"]; ?></p>
              <p class="font-sans text-base h-12"><?php echo $item["description"]; ?></p>
            </div>
          </div>
        <?php endforeach ?>
      </div>
      <div class="md:mt-4 md:py-2 text-center text-base md:text-2xl font-sans">
        <p><?php echo $content["how-it-works"]["opening_hours"]; ?></p>
      </div>
      <div class="md:mt-4 flex flex-col md:items-center md:text-center gap-y-9">
        <h3 class="leading-[100%]"><?php echo $content["how-it-works"]["heading"]; ?></h3>
        <div class="w-full flex flex-col md:flex-row md:py-8 space-y-9">
          <?php foreach($content["how-it-works"]["steps"] as $item): ?>
            <div class="flex flex-1 md:flex-col items-center text-center">
              <div class="md:max-w-48 w-full md:space-y-4 space-x-4 flex md:block items-center">
                <div class="space-y-4 max-w-30 md:max-w-full w-full shrink-0">
                  <div class="size-12 md:size-16 [&>svg]:h-full [&>svg]:w-full mx-auto">
                    <?php echo get_asset($item["image"]) ?>
                  </div>
                  <p class="font-serif font-semibold text-xl md:text-2xl [&_br]:hidden md:[&_br]:block"><?php echo $item["title"] ?></p>
                </div>
                <p class="font-sans"><?php echo $item["description"] ?></p>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
      <h4 class="font-medium text-lg md:text-2xl md:mt-4 text-center"><?php echo $content["how-it-works"]["closing"] ?></h4>
    </section>

    <section id="faq" class="px-6 pt-26 pb-9 sm:p-16">
      <h2><?php echo $content["faq"]["heading"] ?></h2>
      <p class="font-serif text-base sm:text-2xl mt-3 sm:text-center"><?php echo $content["faq"]["subheading"] ?></p>

      <div class="max-w-4xl mx-auto mt-9 sm:mt-14" id="faq-accordion">

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

      <div class="flex flex-col sm:flex-row justify-center items-center gap-8 mt-14">
        <p class="font-sans font-normal text-lg sm:text-2xl"><?php echo $content["faq"]["closing"] ?></p>
        <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
      </div>
    </section>
  </main>
  <?php get_template_part('parts/footer'); ?>
</body>

</html>
