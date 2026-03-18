<?php
function get_page_content(string $page)
{
  $url = sprintf("%s/content/%s.json", get_template_directory_uri(), $page);
	$raw = file_get_contents($url);
  return [
    "json"  =>  json_decode($raw, true),
    "raw"   =>  $raw,
  ];
}
?>
