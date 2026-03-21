<?php
$content = get_component_content("buttons")["book-session"];
$args = props('book-session');
if ( ! empty( $args["props"] ) ) {
  callComponent('link-button', [
    "children" => $content["label"],
    "variant"  => $args["props"]["variant"],
    "href"     => $content["href"],
    "target"   => "_blank",
  ]);
}
?>
