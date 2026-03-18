<?php
$args = props('contact-us');
if ( ! empty( $args["props"] ) ) {
  callComponent('link-button', [
    "children" => ['<svg class="size-8 text-foreground" aria-hidden="true"><use href="#icon-whatsapp"></use></svg>', "Contact us"],
    "variant"  => $args["props"]["variant"],
    // TODO: Add contact us links @pchy
    "href"     => "#",
  ]);
}
?>
