<?php

namespace AC\Column\Post;

use AC\Column;

class WordCount extends Column {

	public function __construct() {
		$this->set_type( 'column-word_count' );
		$this->set_label( __( 'Word Count', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return ac_helper()->string->word_count( get_post_field( 'post_content', $post_id ) );
	}

}