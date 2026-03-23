<?php $content = get_component_content("footer"); ?>
<footer>
  <div class="px-6 sm:px-16 py-9 sm:py-10 flex flex-col sm:flex-row gap-4 sm:gap-16">
    <!-- Left column: EXPLORE + ADDRESS -->
    <div class="flex-1 flex flex-col gap-4 sm:gap-10">

      <?php $explore = $content["sections"][0]; ?>
      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $explore["title"]; ?></p>
        <ul class="flex sm:flex-col gap-y-2 gap-x-6">
          <?php foreach($explore["links"] as $link): ?>
            <li><a class="flex items-center font-sans text-base text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit" href="<?php echo esc_url($link["href"]); ?>"><?php echo $link["label"]; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php $legal = $content["sections"][2]; ?>
      <div class="flex sm:hidden flex-col">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $legal["title"]; ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach($legal["links"] as $link): ?>
            <li><a class="flex items-center font-sans text-base text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit" href="<?php echo esc_url($link["href"]); ?>"><?php echo $link["label"]; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php $address = $content["sections"][1]; ?>
      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $address["title"]; ?></p>
        <p class="font-sans text-base text-text leading-6 w-fit p-2 sm:p-0"><?php echo $address["text"]; ?></p>
        <a class="flex items-center font-sans text-sm text-text hover:underline px-2 sm:px-0 h-11 sm:h-fit gap-2.5" href="<?php echo esc_url($address["button"]["href"]); ?>" target="_blank" rel="noopener noreferrer">
          <?php echo $address["button"]["title"]; ?>
          <span class="[&>svg.icon-external-link]:size-4! [&>svg]:h-full [&>svg]:w-full"><?php echo get_asset("/images/icons/" . $address["button"]["icon"]); ?></span>
        </a>
      </div>

    </div>

    <!-- Right column: LEGAL + FIND US + CONTACT INFORMATION -->
    <div class="flex-1 flex flex-col gap-4 sm:gap-10">

      <?php $legal = $content["sections"][2]; ?>
      <div class="hidden sm:flex flex-col sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $legal["title"]; ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach($legal["links"] as $link): ?>
            <li><a class="font-sans text-base text-text hover:underline" href="<?php echo esc_url($link["href"]); ?>"><?php echo $link["label"]; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php $findus = $content["sections"][3]; ?>
      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $findus["title"]; ?></p>
        <div class="flex gap-2 sm:gap-3 p-2 sm:p-0">
          <?php foreach($findus["socials"] as $social): ?>
            <a href="<?php echo esc_url($social["href"]) ?>" class="flex items-center justify-center size-10 text-foreground bg-primary">
              <?php echo get_asset("/images/icons/" . $social["icon"]) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <?php $contact = $content["sections"][4]; ?>
      <div class="flex flex-col items-start sm:gap-4">
        <p class="font-serif font-semibold text-sm tracking-widest text-text px-2 sm:px-0"><?php echo $contact["title"]; ?></p>
        <ul class="flex flex-col gap-2 sm:gap-3 px-2 py-4 sm:p-0">
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
  <div class="flex flex-col items-center gap-4 sm:gap-6 mt-4 mb-4 md:mb-48">
    <a class="[&_svg]:h-full [&_svg]:max-h-12 [&_svg]:sm:max-h-16 [&_svg]:w-full" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php echo file_get_contents( get_stylesheet_directory() . '/assets/images/logo.svg' ); ?>
    </a>
    <p class="font-sans text-base"><?php echo $content["closing"]; ?></p>
    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center justify-center w-full px-6">
      <?php callComponent('book-session', ["variant" => "primary"]); ?>
      <?php callComponent('contact-us', ["variant" => "outlined"]); ?>
    </div>
  </div>

  <!-- Copyright -->
  <div class="relative flex flex-col-reverse sm:flex-row justify-end sm:px-16 px-4 pt-6 pb-9 md:pb-4 text-center md:text-right">
    <a href="https://pchy.design" class="sm:absolute left-10 bottom-0 font-sans text-sm font-semibold sm:block py-2 px-3 sm:bg-foreground text-foreground sm:text-primary rounded-t-xl" target="_blank" rel="noopener noreferrer">Website by • <span class="text-foreground sm:text-primary">pchy</span></a>
    <p class="font-sans text-sm text-foreground font-semibold"><?php echo $content["copyright"]; ?></p>
  </div>
</footer>
