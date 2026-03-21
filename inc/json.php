<?php
function get_content()
{
  $url = get_stylesheet_directory() . "/assets/content.json";
	$raw = file_get_contents($url);
  return json_decode($raw, true);
}

function get_page_content(string $page) {
  return get_content()[$page];
}

function get_component_content(string $name) {
  return get_content()["components"][$name];
}

function get_asset_uri(string $path) {
  $url = get_stylesheet_directory_uri() . "/assets" . $path;
  return $url;
}

function get_asset(string $path) {
  $url = get_stylesheet_directory() . "/assets" . $path;
  return file_get_contents($url);
}
?>
