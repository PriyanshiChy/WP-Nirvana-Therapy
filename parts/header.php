<header>
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
  </a>

  <nav>
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#how-it-works">Therapy</a></li>
      <li><a href="#faq">FAQs</a></li>
    </ul>
  </nav>

  <?php callComponent('contact-us', ["variant"   => "primary"]); ?>
</header>