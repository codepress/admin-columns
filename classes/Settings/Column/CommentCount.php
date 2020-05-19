<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class CommentCount extends Settings\Column
	implements Settings\FormatValue {

	const NAME = 'comment_count';

	const STATUS_ALL = 'all';
	const STATUS_APPROVED = 'approved';
	const STATUS_PENDING = 'moderated';
	const STATUS_SPAM = 'spam';
	const STATUS_TRASH = 'trash';
	const STATUS_TOTAL_COMMENTS = 'total_comments';

	/**
	 * @var string
	 */
	private $comment_status;

	protected function set_name() {
		$this->name = self::NAME;
	}

	protected function define_options() {
		return [
			'comment_status' => self::STATUS_ALL,
		];
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$setting = $this->create_element( 'select' )
		                ->set_options( $this->get_comment_statuses() );

		$view = new View( [
			'label'   => __( 'Comment status', 'codepress-admin-columns' ),
			'tooltip' => __( 'Select which comment status you like to display.', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );

		return $view;
	}

	/**
	 * @return array
	 */
	protected function get_comment_statuses() {
		$options = [
			self::STATUS_APPROVED => __( 'Approved', 'codepress-admin-columns' ),
			self::STATUS_PENDING  => __( 'Pending', 'codepress-admin-columns' ),
			self::STATUS_SPAM     => __( 'Spam', 'codepress-admin-columns' ),
			self::STATUS_TRASH    => __( 'Trash', 'codepress-admin-columns' ),
		];

		natcasesort( $options );

		// First
		$options = [ self::STATUS_ALL => __( 'Total', 'codepress-admin-columns' ) ] + $options;

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
		if ( self::STATUS_TOTAL_COMMENTS === $comment_status ) {
			$comment_status = self::STATUS_ALL;
		}

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

		return ac_helper()->html->link( add_query_arg( [
			'p'              => $post_id,
			'comment_status' => $this->get_comment_status(),
		], admin_url( 'edit-comments.php' ) ), $count );
	}

}