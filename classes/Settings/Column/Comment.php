<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

/**
 * @since 3.0.8
 */
class Comment extends Settings\Column
	implements Settings\FormatValue {

	const NAME = 'comment';

	const PROPERTY_COMMENT = 'comment';
	const PROPERTY_DATE = 'date';
	const PROPERTY_ID = 'id';
	const PROPERTY_AUTHOR = 'author';
	const PROPERTY_AUTHOR_EMAIL = 'author_email';

	/**
	 * @var string
	 */
	private $comment_property;

	protected function set_name() {
		$this->name = self::NAME;
	}

	protected function define_options() {
		return [
			'comment_property_display' => 'comment',
		];
	}

	public function get_dependent_settings() {

		switch ( $this->get_comment_property_display() ) {

			case self::PROPERTY_DATE :
				return [
					new Settings\Column\Date( $this->column ),
					new Settings\Column\CommentLink( $this->column ),
				];

				break;
			case self::PROPERTY_COMMENT :
				return [
					new Settings\Column\StringLimit( $this->column ),
					new Settings\Column\CommentLink( $this->column ),
				];

				break;
			default :
				return [ new Settings\Column\CommentLink( $this->column ) ];
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

			case self::PROPERTY_DATE :
				$value = $this->get_comment_property( 'comment_date', $id );

				break;
			case self::PROPERTY_AUTHOR :
				$value = $this->get_comment_property( 'comment_author', $id );

				break;
			case self::PROPERTY_AUTHOR_EMAIL :
				$value = $this->get_comment_property( 'comment_author_email', $id );

				break;
			case self::PROPERTY_COMMENT :
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

		$view = new View( [
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		] );

		return $view;
	}

	protected function get_display_options() {
		$options = [
			self::PROPERTY_COMMENT      => __( 'Comment' ),
			self::PROPERTY_ID           => __( 'ID' ),
			self::PROPERTY_AUTHOR       => __( 'Author' ),
			self::PROPERTY_AUTHOR_EMAIL => __( 'Author Email', 'codepress-admin-column' ),
			self::PROPERTY_DATE         => __( 'Date' ),
		];

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