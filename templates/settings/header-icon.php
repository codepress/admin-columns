<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array(
	'ac-column-heading-setting',
	'ac-column-indicator--' . esc_attr( $this->setting ),
	'ac-column-indicator'
);

if( $this->dashicon ){
	$classes[] = 'dashicons';
	$classes[] = esc_attr( $this->dashicon );
}

if( 'on' == $this->state ){
	$classes[] = 'on';
}

if( $this->class ){
	$classes[] = $this->class;
}

?>
<span class="<?php echo implode( ' ', $classes );?>"
	title="<?php echo esc_attr( $this->title ); ?>"
	data-setting="<?php echo esc_attr( $this->setting ); ?>"
	data-indicator-toggle=""
	>
</span>