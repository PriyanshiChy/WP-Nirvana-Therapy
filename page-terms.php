<?php
/* Template Name: Terms and Conditions */

$content = get_page_content("terms-and-conditions");
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
  <main class="tnc py-36 px-16">
    <h1 class="font-serif text-[2.625rem]"><?php echo $content["title"]; ?></h1>
    <p class="font-sans text-lg mt-4"><?php echo $content["subtitle"]; ?></p>
    <div class="mx-auto max-w-4xl w-full text-lg mt-16 font-sans">
      <?php foreach ($content["description"] as $item): ?>
        <p><?php echo($item); ?></p><br />
      <?php endforeach ?>
      <p class="text-center mt-7"><?php echo($content["closing"]); ?></p>
    </div>
  </main>
  <?php get_template_part('parts/footer'); ?>
</body>

</html>