<?php
$args = props('link-button');
$target = $args["props"]["target"] ?? "";
if ( ! empty( $args["props"] ) ) : ?>
<a
	class="link <?php echo($args["props"]["variant"] ?? ""); ?>"
	href="<?php echo($args["props"]["href"] ?? '#') ?>"
	<?php
		if($target != "") {
			echo(sprintf('target="%s"', $args["props"]["target"]));
		}
	?>
>
	<div>
		<?php echo($args["children"]) ?>
	</div>
</a>
<?php endif; ?>
