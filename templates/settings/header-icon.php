<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = [
	'ac-column-heading-setting',
	'ac-column-indicator--' . $this->setting,
	'-' . $this->setting,
	'ac-column-indicator',
];

if ( $this->dashicon ) {
	$classes[] = 'dashicons';
	$classes[] = $this->dashicon;
}

if ( $this->class ) {
	$classes[] = $this->class;
}

if ( 'on' === $this->state ) {
	$classes[] = 'on';
}

?>
<span class="<?= esc_attr( implode( ' ', $classes ) ); ?>"
		title="<?= esc_attr( $this->title ); ?>"
		data-setting="<?= esc_attr( $this->setting ); ?>"
		data-indicator-toggle=""
>
</span>