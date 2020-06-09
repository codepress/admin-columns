<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class PostCount extends Column {

	public function __construct() {
		$this->set_type( 'column-user_postcount' )
		     ->set_label( __( 'Post Count', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		$ids = $this->get_raw_value( $user_id );

		if ( empty( $ids ) ) {
			return $this->get_empty_char();
		}

		$value = number_format_i18n( count( $ids ) );

		if ( post_type_exists( $this->get_selected_post_type() ) ) {
			$link = add_query_arg( [
				'post_type' => $this->get_selected_post_type(),
				'author'    => $user_id,
			], admin_url( 'edit.php' ) );

			$value = sprintf( '<a href="%s">%s</a>', $link, $value );
		}

		return $value;
	}

	protected function get_selected_post_type() {
		return $this->get_setting( 'post_type' )->get_post_type();
	}

	public function get_raw_value( $user_id ) {
		$post_type = $this->get_selected_post_type();

		if ( 'any' === $post_type ) {
			// All post types, including the ones that are marked "exclude from search"
			$post_type = get_post_types();
		}

		return get_posts( [
			'fields'         => 'ids',
			'author'         => $user_id,
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => [ 'publish', 'private' ],
		] );
	}

	protected function register_settings() {
		$this->add_setting( new Settings\Column\PostType( $this, true ) );
	}

}