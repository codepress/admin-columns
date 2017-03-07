<?php

class AC_Settings_Setting_CommentStatus extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	private $comment_status;

	protected function define_options() {
		return array( 'comment_status' );
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
			'total_comments' => __( 'Totals', 'codepress-admin-columns' ),
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
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function format( $post_id, $object_id = null ) {
		$value = ac_helper()->string->get_empty_char();

		$status = $this->get_comment_status();
		$count = $this->column->get_raw_value( $post_id );

		if ( $count > 0 ) {
			$names = $this->get_comment_statuses();
			$url = add_query_arg( array( 'p' => $post_id, 'comment_status' => $status ), admin_url( 'edit-comments.php' ) );

			$value = ac_helper()->html->link( $url, $count, array( 'class' => 'cp-' . $status, 'title' => $names[ $status ] ) );
		}

		return $value;
	}

}