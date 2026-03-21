<?php $content = get_component_content("navbar"); ?>
<header>
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
  </a>

  <nav>
    <ul>
      <?php
      foreach($content["links"] as $item): ?>
        <li><a href="<?php echo(home_url($item["href"] == "/" && is_front_page() ? '/#hero' :$item["href"])); ?>"><?php echo($item["label"]); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <?php callComponent('contact-us', ["variant" => "primary"]); ?>
</header>