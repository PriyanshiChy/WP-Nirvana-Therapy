<?php
function normalizeChildren($value): string
{
	if (is_array($value)) {
		return implode(' ', $value);
	}

	return (string) $value;
}

function props(string $key): array {
	$args = get_query_var($key);

	if (empty($args) || ! is_array($args)) {
		echo sprintf('<div id="props">%s</div>', $args);
		return [
			"children"		=>	"",
			"attributes"	=>	"",
			"props"				=>	$args,
		];
	}

	$children = normalizeChildren($args["children"] ?? "");
	unset($args["children"]);

	$attributes = "";
	foreach ($args as $key => $value) {
		$attributes .= sprintf(
			' %s="%s"',
			esc_attr($key),
			esc_attr($value),
		);
	}

	return [
		"children"		=>	$children,
		"attributes"	=>	$attributes,
		"props"				=>	$args,
	];
}

function callComponent(string $key, array $args = []) {
	set_query_var($key, $args);
	return get_template_part(sprintf('components/%s', $key));
}
?>
