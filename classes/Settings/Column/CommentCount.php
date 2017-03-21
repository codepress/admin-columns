<?php

class AC_Settings_Column_CommentCount extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	private $comment_status;

	private $admin_url;

	public function get_name() {
		return 'comment_count';
	}

	protected function define_options() {
		return array(
			'comment_status' => 'total_comments',
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
			'approved'  => __( 'Approved', 'codepress-admin-columns' ),
			'moderated' => __( 'Pending', 'codepress-admin-columns' ),
			'spam'      => __( 'Spam', 'codepress-admin-columns' ),
			'trash'     => __( 'Trash', 'codepress-admin-columns' ),
		);

		asort( $options );

		// First
		$options = array( 'total_comments' => __( 'Total', 'codepress-admin-columns' ) ) + $options;

		return $options;
	}

	public function set_admin_url( $admin_url ) {
		$this->admin_url = $admin_url;
	}

	private function get_admin_url() {
		if ( null === $this->admin_url ) {
			$this->set_admin_url( admin_url( 'edit-comments.php' ) );
		}

		return $this->admin_url;
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
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		$status = $this->get_comment_status();
		$value_formatter->value = ac_helper()->string->get_empty_char();

		if ( isset( $value_formatter->value->$status ) ) {
			$url = add_query_arg( array( 'comment_status' => $status ), $this->get_admin_url() );
			$value_formatter->value = ac_helper()->html->link( $url, $value_formatter->value->$status );
		}

		return $value_formatter;
	}

}