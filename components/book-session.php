<?php
$args = props('book-session');
if ( ! empty( $args["props"] ) ) {
  callComponent('link-button', [
    "children" => "Book a Session",
    "variant"  => $args["props"]["variant"],
    "href"     => "#",
  ]);
}
?>
