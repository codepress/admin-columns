<div>
	<?php foreach ( $this->options as $option ) : ?>
		<?= $option->render(); ?>
	<?php endforeach; ?>
</div>