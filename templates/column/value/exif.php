<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<h2 style="margin-top: 0; font-size: 16px;"><?= $this->title; ?> ( <a href="<?= esc_attr( $this->file_url ); ?>"><?= $this->file_name; ?></a> )</h2>
<div>
	<?php foreach ( $this->exif_data as $label => $value ): ?>
		<div style="display: flex; width: 100%;">
			<div style="width: 200px; background: #eee; border-bottom: 1px solid #ccc; padding: 5px;"><?= $label; ?></div>
			<div style="border-bottom: 1px solid #ccc; padding: 5px; padding-left: 10px; flex-grow: 1;"><?= $value; ?></div>
		</div>
	<?php endforeach; ?>
</div>