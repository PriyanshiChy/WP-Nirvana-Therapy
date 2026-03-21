<?php $args = props('head'); ?>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $args["props"]["title"]; ?></title>
  <meta name="description" content="<?php echo $args["props"]["description"]; ?>" />
  <meta name="keywords" content="<?php echo $args["props"]["keywords"]; ?>" />
  <meta name="creator" content="Priyanshi Choudhury <priyanshichoudhury.work@gmail.com>" />
  <meta name="author" content="Nirvana Therapy" />

  <meta property="og:title" content="<?php echo $args["props"]["title"]; ?>" />
  <meta property="og:description" content="<?php echo $args["props"]["description"]; ?>" />
  <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/og-image.png" />
  <meta property="og:url" content="https://nirvanatherapy.co" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?php echo $args["props"]["title"]; ?>" />
  <meta name="twitter:description" content="<?php echo $args["props"]["description"]; ?>" />
  <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-card.png" />

  <!-- Favicon -->
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon.ico" type="image/x-icon" />
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-16x16.png" sizes="16x16">
  <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/favicon/apple-touch-icon.png">

  <!-- Preload images -->
  <link rel="preload" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/hero.webp" />
  <link rel="preload" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/tnc.webp" />
  <!-- Prefetch images -->
  <link rel="prefetch" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/about.webp" />
  <link rel="prefetch" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/bg-footer.webp" />
  <link rel="prefetch" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/choose-us.webp" />
  <link rel="prefetch" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/founder.webp" />
  <link rel="prefetch" as="image" href="<?php echo get_template_directory_uri(); ?>/assets/images/bg/services.webp" />

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