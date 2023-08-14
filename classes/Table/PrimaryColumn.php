<?php

namespace AC\Table;

use AC\ListScreen;
use WP_Post;

class PrimaryColumn {

	private $list_screen;

	public function __construct( ListScreen $list_screen ) {
		$this->list_screen = $list_screen;
	}

	public function set_primary_column( string $default ): string {
		if ( ! $this->list_screen->get_column_by_name( $default ) ) {
			$default = (string) key( $this->list_screen->get_columns() );
		}

		// If actions column is present, set it as primary
		foreach ( $this->list_screen->get_columns() as $column ) {
			if ( 'column-actions' === $column->get_type() ) {
				$default = $column->get_name();
				if ( $this->list_screen instanceof ListScreen\Media ) {
					// Add download button to the actions column
					add_filter( 'media_row_actions', [ $this, 'set_media_download_row_action' ], 10, 2 );
				}
			}
		}
		// Set inline edit data if the default column (title) is not present
		if ( $this->list_screen instanceof ListScreen\Post && 'title' !== $default ) {
			add_filter( 'page_row_actions', [ $this, 'set_inline_edit_data' ], 20, 2 );
			add_filter( 'post_row_actions', [ $this, 'set_inline_edit_data' ], 20, 2 );
		}

		// Remove inline edit action if the default column (author) is not present
		if ( $this->list_screen instanceof ListScreen\Comment && 'comment' !== $default ) {
			add_filter( 'comment_row_actions', [ $this, 'remove_quick_edit_from_actions' ], 20, 2 );
		}

		return $default;
	}

	public function set_media_download_row_action( array $actions, WP_Post $post ): array {
		$link_attributes = [
			'download' => '',
			'title'    => __( 'Download', 'codepress-admin-columns' ),
		];
		$actions['download'] = ac_helper()->html->link( wp_get_attachment_url( $post->ID ), __( 'Download', 'codepress-admin-columns' ), $link_attributes );

		return $actions;
	}

	public function set_inline_edit_data( array $actions, WP_Post $post ) {
		get_inline_data( $post );

		return $actions;
	}

	public function remove_quick_edit_from_actions( array $actions ): array {
		unset( $actions['quickedit'] );

		return $actions;
	}

}