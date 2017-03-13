<?php

class AC_Settings_Column_CommentCount extends AC_Settings_Column
	implements AC_Settings_FormatInterface {

	private $comment_status;

	public function get_name() {
		return 'comment_count';
	}

	protected function define_options() {
		return array(
			'comment_status' => 'total_comments'
		);
	}

	/**
	 * @return AC_View
	 */
	public function create_view() {
		$view = new AC_View( array(
			'label'   => __( 'Comment status', 'codepress-admin-columns' ),
			'tooltip' => __( 'Select which comment status you like to display.', 'codepress-admin-columns' ),
			'setting' => $this->create_element( 'select' )->set_options( $this->get_comment_statuses() ),
		) );

		return $view;
	}

	/**
	 * @return array
	 */
	private function get_comment_statuses() {
		$options = array(
			'total_comments' => __( 'Total', 'codepress-admin-columns' ),
			'approved'       => __( 'Approved', 'codepress-admin-columns' ),
			'moderated'      => __( 'Pending', 'codepress-admin-columns' ),
			'spam'           => __( 'Spam', 'codepress-admin-columns' ),
			'trash'          => __( 'Trash', 'codepress-admin-columns' ),
		);

		asort( $options );

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
	 * @param stdClass $count
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function format( $count, $object_id = null ) {
		$value = 0;

		$status = $this->get_comment_status();

		if ( isset( $count->{$status} ) ) {
			$value = $count->{$status};
		}

		return $value;
	}

}