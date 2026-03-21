<?php
$content = get_component_content("navbar");
$buttons = get_component_content("buttons");
$whatsappHref = $buttons["contact-us"]["href"];
?>
<header>
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
  </a>

  <nav>
    <ul>
      <?php foreach($content["links"] as $item): ?>
        <li><a href="<?php echo(home_url($item["href"] == "/" && is_front_page() ? '/#hero' : $item["href"])); ?>"><?php echo($item["label"]); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="header-desktop-cta">
    <?php callComponent('contact-us', ["variant" => "primary"]); ?>
  </div>

  <div class="header-mobile-controls">
    <a href="<?php echo esc_url($whatsappHref); ?>" target="_blank" class="header-mobile-whatsapp" aria-label="WhatsApp">
      <svg class="size-8 text-primary" aria-hidden="true"><use href="#icon-whatsapp"></use></svg>
    </a>
    <button class="header-hamburger" aria-label="Open menu" aria-expanded="false">
      <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <line x1="4" y1="8"  x2="24" y2="8"  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <line x1="4" y1="14" x2="24" y2="14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <line x1="4" y1="20" x2="24" y2="20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
</header>

<div class="mobile-menu" aria-hidden="true">
  <div class="mobile-menu-header">
    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
    </a>
    <div class="mobile-menu-header-controls">
      <a href="<?php echo esc_url($whatsappHref); ?>" target="_blank" aria-label="WhatsApp">
        <svg class="size-8 text-primary" aria-hidden="true"><use href="#icon-whatsapp"></use></svg>
      </a>
      <button class="header-close" aria-label="Close menu">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <line x1="6" y1="6"  x2="22" y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          <line x1="22" y1="6" x2="6"  y2="22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>
  </div>

  <nav class=""mobile-menu-nav">
    <ul class="w-full">
      <?php foreach($content["links"] as $item): ?>
        <li class="w-full h-12 text-base text-center"><a class="w-full h-full flex items-center justify-center" href="<?php echo(home_url($item["href"] == "/" && is_front_page() ? '/#hero' : $item["href"])); ?>"><span><?php echo($item["label"]); ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="mobile-menu-actions">
    <?php callComponent('book-session', ["variant" => "primary"]); ?>
    <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
  </div>
</div>
