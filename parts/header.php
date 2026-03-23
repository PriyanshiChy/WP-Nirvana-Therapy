<?php
$content = get_component_content("navbar");
$buttons = get_component_content("buttons");
$whatsappHref = $buttons["contact-us"]["href"];
?>
<header>
  <div id="header-bar" class="flex items-center justify-between">
    <div class="w-fit xl:w-60">
      <a class="[&_svg]:h-12 lg:[&_svg]:h-16" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
      </a>
    </div>

    <nav class="lg:block hidden">
      <ul class="flex">
        <?php foreach($content["links"] as $item): ?>
          <li><a class="after:transition-all after:duration-300 after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-[calc(100%-3.25rem)] after:border-b-2 after:border-b-transparent" href="<?php echo(home_url($item["href"] == "/" && is_front_page() ? '/#hero' : $item["href"])); ?>"><?php echo($item["label"]); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="xl:block hidden">
      <?php callComponent('contact-us', ["variant" => "primary"]); ?>
    </div>

    <div class="xl:hidden lg:block hidden">
      <?php callComponent('contact-us', ["variant" => "primary", "icon" => true]); ?>
    </div>

    <div class="lg:hidden flex items-center gap-2 sm:gap-4">
      <a href="<?php echo esc_url($whatsappHref); ?>" target="_blank" class="p-2" aria-label="WhatsApp">
        <svg class="size-8 text-primary" aria-hidden="true"><use href="#icon-whatsapp"></use></svg>
      </a>
      <button class="p-2" id="header-hamburger" aria-label="Open menu" aria-expanded="false">
        <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/icons/hamburger.svg' ); ?>
      </button>
    </div>
  </div>

  <div id="mobile-menu" class="space-y-6 lg:hidden aria-hidden:hidden" aria-hidden="true">
    <nav>
      <ul class="w-full flex flex-col">
        <?php foreach($content["links"] as $item): ?>
          <li class="w-full h-12 text-base text-center">
            <a class="w-full h-full flex items-center justify-center p-0!" href="<?php echo(home_url($item["href"] == "/" && is_front_page() ? '/#hero' : $item["href"])); ?>"><span><?php echo($item["label"]); ?></span></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="flex flex-col gap-4 [&_a]:w-full!">
      <?php callComponent('book-session', ["variant" => "primary"]); ?>
      <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
    </div>
  </div>
</header>
