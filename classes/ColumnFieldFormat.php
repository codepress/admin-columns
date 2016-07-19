<?php
defined( 'ABSPATH' ) or die();

class AC_ColumnFieldFormat {

	private $column;

	public function __construct( CPAC_Column $column ) {
		$this->column = $column;
	}

	/**
	 * @param int|WP_User $user
	 *
	 * @return false|string
	 */
	public function user( $user ) {
		return ac_helper()->user->get_display_name( $user, $this->column->get_option( 'display_author_as' ) );
	}

	/**
	 * @param string $date
	 *
	 * @return string
	 */
	public function date( $date ) {
		return ac_helper()->date->date( $date, $this->column->get_option( 'date_format' ) );
	}

	/**
	 * @param string $words
	 *
	 * @return string
	 */
	public function word_limit( $words ) {
		$limit = $this->column->get_option( 'excerpt_length' );

		return $limit ? wp_trim_words( $words, $limit ) : $words;
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function character_limit( $string ) {
		$limit = $this->column->get_option( 'character_limit' );

		return is_numeric( $limit ) && 0 < $limit && strlen( $string ) > $limit ? substr( $string, 0, $limit ) . __( '&hellip;' ) : $string;
	}

	/**
	 * @return array|false|string
	 */
	public function image_sizes() {
		$size = $this->column->get_option( 'image_size' );

		if ( 'cpac-custom' == $size ) {
			$size = array(
				$this->column->get_option( 'image_size_w' ),
				$this->column->get_option( 'image_size_h' ),
			);
		}

		return $size;
	}

	/**
	 * @param int[] $attachment_ids
	 *
	 * @return string
	 */
	public function images( $attachment_ids ) {
		return ac_helper()->image->get_images_by_ids( $attachment_ids, $this->image_sizes() );
	}

}