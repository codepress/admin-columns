<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attributes = $this->attributes ?? [];
$attribute_markup = [];

foreach ( $attributes as $key => $value ) {
	$attribute_markup[] = sprintf( '%s="%s"', $key, esc_attr__( $value ) );
}
?>

<video controls src="<?= $this->src ?>" preload="metadata" <?= implode( ' ', $attribute_markup ); ?>></video>