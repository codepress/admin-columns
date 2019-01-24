<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<li class="<?php echo esc_attr( $this->slug ); ?>">
	<div class="addon-header">
		<div class="inner">
			<img src="<?php echo esc_url( $this->logo ); ?>"/>
		</div>
	</div>
	<div class="addon-content">
		<h3><?php echo esc_html( $this->title ); ?></h3>
		<p><?php echo esc_html( $this->description ); ?></p>
	</div>
	<div class="addon-actions">
		<?php echo $this->actions; ?>
	</div>
</li>