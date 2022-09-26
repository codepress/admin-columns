<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<a class="ac-pointer instructions" rel="pointer-<?php echo esc_attr( $this->id ); ?>" data-pos="<?php echo esc_attr( $this->position ); ?>" data-width="300">
	<?php echo $this->label; ?>
</a>