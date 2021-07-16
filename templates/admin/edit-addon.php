<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<li class="ac-addon -<?= esc_attr( $this->slug ) ?>" data-slug="<?= $this->slug ?>">
	<div class="ac-addon__header">
		<img src="<?= esc_url( $this->logo ) ?>" alt="<?= esc_attr( $this->slug ) ?>"/>
	</div>
	<div class="ac-addon__content">
		<h3><?= esc_html( $this->title ) ?></h3>
		<p>
			<?= esc_html( $this->description ) ?>
			<a class="ac-addon__more-link" href="<?= esc_attr( $this->link ) ?>" target="_blank"><?= __( 'More details', 'codepress-admin-columns' ) ?> &raquo;</a>
		</p>
	</div>
	<div class="ac-addon__actions">
		<?= $this->actions ?>
	</div>
</li>