<?php $content = get_component_content("footer"); ?>
<footer>
  <div class="px-16 py-10 flex gap-16">
    <!-- Left column: EXPLORE + ADDRESS -->
    <div class="flex-1 flex flex-col gap-10">

      <?php $explore = $content["sections"][0]; ?>
      <div class="flex flex-col gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text"><?php echo $explore["title"]; ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach($explore["links"] as $link): ?>
            <li><a class="font-sans text-base text-text hover:underline" href="<?php echo esc_url($link["href"]); ?>"><?php echo $link["label"]; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php $address = $content["sections"][1]; ?>
      <div class="flex flex-col gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text"><?php echo $address["title"]; ?></p>
        <p class="font-sans text-base text-text leading-6"><?php echo $address["text"]; ?></p>
        <a class="flex items-center gap-2 font-sans text-base text-text hover:underline" href="<?php echo esc_url($address["button"]["href"]); ?>" target="_blank" rel="noopener noreferrer">
          <?php echo $address["button"]["title"]; ?>
          <span class="size-4 [&>svg]:h-full [&>svg]:w-full"><?php echo get_asset("/images/icons/" . $address["button"]["icon"]); ?></span>
        </a>
      </div>

    </div>

    <!-- Right column: LEGAL + FIND US + CONTACT INFORMATION -->
    <div class="flex-1 flex flex-col gap-10">

      <?php $legal = $content["sections"][2]; ?>
      <div class="flex flex-col gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text"><?php echo $legal["title"]; ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach($legal["links"] as $link): ?>
            <li><a class="font-sans text-base text-text hover:underline" href="<?php echo esc_url($link["href"]); ?>"><?php echo $link["label"]; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php $findus = $content["sections"][3]; ?>
      <div class="flex flex-col gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text"><?php echo $findus["title"]; ?></p>
        <div class="flex gap-3">
          <?php foreach($findus["socials"] as $social): ?>
            <a href="<?php esc_url($social["href"]) ?>" class="flex items-center justify-center size-10 text-foreground bg-primary">
              <?php echo get_asset("/images/icons/" . $social["icon"]) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <?php $contact = $content["sections"][4]; ?>
      <div class="flex flex-col gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text"><?php echo $contact["title"]; ?></p>
        <ul class="flex flex-col gap-3">
          <?php foreach($contact["contacts"] as $item): ?>
            <li>
              <a class="flex items-center gap-3 font-sans text-base text-text hover:underline" href="<?php echo esc_url($item["href"]); ?>">
                <span class="size-6 shrink-0 [&>svg]:h-full [&>svg]:w-full"><?php echo get_asset("/images/icons/" . $item["icon"]); ?></span>
                <?php echo $item["label"]; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    </div>
  </div>

  <!-- Closing section -->
  <div class="flex flex-col items-center gap-6 mt-4 mb-48">
    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
    </a>
    <p class="font-sans text-base"><?php echo $content["closing"]; ?></p>
    <div class="flex gap-6 items-center">
      <?php callComponent('book-session', ["variant" => "primary"]); ?>
      <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
    </div>
  </div>

  <!-- Copyright -->
  <div class="px-16 pt-6 pb-4 text-right">
    <p class="font-sans text-sm text-foreground font-semibold"><?php echo $content["copyright"]; ?></p>
  </div>
</footer>
