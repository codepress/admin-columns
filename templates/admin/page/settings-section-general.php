<form method="post" action="options.php">

	<?php settings_fields( \AC\Settings\General::GROUP ); ?>

	<?php foreach ( $this->options as $option ) : ?>
		<?= $option->render(); ?>
	<?php endforeach; ?>

	<p class="save-button">
		<input type="submit" class="button" value="<?php echo esc_attr( __( 'Save' ) ); ?>"/>
	</p>
</form>