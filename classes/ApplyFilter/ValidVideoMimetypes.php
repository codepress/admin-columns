<?php

namespace AC\ApplyFilter;

use AC;

class ValidVideoMimetypes {

	private $column;

	public function __construct( AC\Column $column ) {
		$this->column = $column;
	}

	public function apply_filters() {
		return apply_filters( 'ac/column/video_player/valid_mime_types', [ 'video/mp4', 'video/webm', 'video/quicktime' ], $this->column );
	}

}