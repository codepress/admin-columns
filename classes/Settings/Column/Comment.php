<?php

/**
 * @since NEWVERSION
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
		$setting = array();

		switch ( $this->get_comment_property_display() ) {
			case 'date' :
				$setting[] = new AC_Settings_Column_Date( $this->column );
				break;
			case 'comment' :
				$setting[] = new AC_Settings_Column_StringLimit( $this->column );
				break;
		}

		return $setting;
	}

	/**
	 * @param int   $id
	 * @param mixed $original_value
	 *
	 * @return string|int
	 */
	public function format( $id, $original_value ) {
		$comment = get_comment( $id );

		switch ( $this->get_comment_property_display() ) {
			case 'date' :
				$value = $comment->comment_date;

				break;
			case 'author' :
				$value = $comment->comment_author;

				break;
			case 'author_email' :
				$value = $comment->comment_author_email;

				break;
			case 'comment' :
				$value = $comment->comment_content;

				break;
			default :
				$value = $id;
		}

		return $value;
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

		asort( $options );

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