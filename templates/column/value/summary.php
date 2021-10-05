<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<strong>Title: </strong><?= $this->title; ?><br>
<strong>Date Published: </strong><?= $this->date_published; ?><br>
<strong>Status: </strong><?= $this->status; ?><br>
<?php foreach ( $this->taxonomies as $label => $terms ): ?>
	<strong><?= $label; ?>: </strong><?= implode( ', ', $terms ); ?><br>
<?php endforeach ?>
<strong>Excerpt: </strong><br><?= $this->excerpt; ?><br>
