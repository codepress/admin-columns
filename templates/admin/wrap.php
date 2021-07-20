<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="cpac" class="wrap">
	<h1 class="screen-reader-text"><?= isset ( $this->title ) ? $this->title : ''; ?></h1>
	<?= $this->content; ?>
</div>