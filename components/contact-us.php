<?php
$content = get_component_content("buttons")["contact-us"];
$args = props('contact-us');
if ( ! empty( $args["props"] ) ) {
  callComponent('link-button', [
    "children" => ['<svg class="size-8 text-foreground" aria-hidden="true"><use href="#icon-whatsapp"></use></svg>', ($args["props"]["label"] ?? $content["label"])],
    "variant"  => $args["props"]["variant"],
    "href"     => $content["href"],
    "target"   => "_blank",
  ]);
}
?>
