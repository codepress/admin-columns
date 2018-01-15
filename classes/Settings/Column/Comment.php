<?php

/**
 * @since 3.0.8
 */
class AC_Settings_Column_Comment extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $comment_property;

	protected function set_name() {
		$this->name = 'comment';
	}

	protected function define_options() {
		return array(
			'comment_property_display' => 'comment',
		);
	}

	public function get_dependent_settings() {

		switch ( $this->get_comment_property_display() ) {

			case 'date' :
				return array( new AC_Settings_Column_Date( $this->column ) );

				break;
			case 'comment' :
				return array( new AC_Settings_Column_StringLimit( $this->column ) );

				break;

			default :
				return array();
		}
	}

	/**
	 * @param int   $id
	 * @param mixed $original_value
	 *
	 * @return string|int
	 */
	public function format( $id, $original_value ) {

		switch ( $this->get_comment_property_display() ) {

			case 'date' :
				$value = $this->get_comment_property( 'comment_date', $id );

				break;
			case 'author' :
				$value = $this->get_comment_property( 'comment_author', $id );

				break;
			case 'author_email' :
				$value = $this->get_comment_property( 'comment_author_email', $id );

				break;
			case 'comment' :
				$value = $this->get_comment_property( 'comment_content', $id );

				break;
			default :
				$value = $id;
		}

		return $value;
	}

	/**
	 * @param string $property
	 * @param int    $id
	 *
	 * @return false|string
	 */
	private function get_comment_property( $property, $id ) {
		$comment = get_comment( $id );

		if ( ! isset( $comment->{$property} ) ) {
			return false;
		}

		return $comment->{$property};
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		$options = array(
			'comment'      => __( 'Comment' ),
			'id'           => __( 'ID' ),
			'author'       => __( 'Author' ),
			'author_email' => __( 'Author Email', 'codepress-admin-column' ),
			'date'         => __( 'Date' ),
		);

		natcasesort( $options );

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_comment_property_display() {
		return $this->comment_property;
	}

	/**
	 * @param string $comment_property
	 *
	 * @return bool
	 */
	public function set_comment_property_display( $comment_property ) {
		$this->comment_property = $comment_property;

		return true;
	}

}