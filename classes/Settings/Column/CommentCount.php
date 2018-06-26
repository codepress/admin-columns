<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class CommentCount extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $comment_status;

	public function get_name() {
		return 'comment_count';
	}

	protected function define_options() {
		return array(
			'comment_status' => 'total_comments',
		);
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_options( $this->get_comment_statuses() );

		$view = new View( array(
			'label'   => __( 'Comment status', 'codepress-admin-columns' ),
			'tooltip' => __( 'Select which comment status you like to display.', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return array
	 */
	protected function get_comment_statuses() {
		$options = array(
			'approved'  => __( 'Approved', 'codepress-admin-columns' ),
			'moderated' => __( 'Pending', 'codepress-admin-columns' ),
			'spam'      => __( 'Spam', 'codepress-admin-columns' ),
			'trash'     => __( 'Trash', 'codepress-admin-columns' ),
		);

		natcasesort( $options );

		// First
		$options = array( 'total_comments' => __( 'Total', 'codepress-admin-columns' ) ) + $options;

		return $options;
	}

	/**
	 * @return int
	 */
	public function get_comment_status() {
		return $this->comment_status;
	}

	/**
	 * @param string $comment_status
	 *
	 * @return bool
	 */
	public function set_comment_status( $comment_status ) {
		$this->comment_status = $comment_status;

		return true;
	}

	/**
	 * @param int $post_id
	 *
	 * @return int
	 */
	public function get_comment_count( $post_id ) {
		$status = $this->get_comment_status();
		$count = wp_count_comments( $post_id );

		if ( empty( $count->$status ) ) {
			return false;
		}

		return $count->$status;
	}

	/**
	 * @param int $post_id
	 * @param int $original_value
	 *
	 * @return false|string
	 */
	public function format( $post_id, $original_value ) {
		$count = $this->get_comment_count( $post_id );

		if ( ! $count ) {
			return $this->column->get_empty_char();
		}

		return ac_helper()->html->link( add_query_arg( array( 'p' => $post_id, 'comment_status' => $this->get_comment_status() ), admin_url( 'edit-comments.php' ) ), $count );
	}

}