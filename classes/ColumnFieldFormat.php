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
	 * @param int[]|int $attachment_ids
	 *
	 * @return string
	 */
	public function images( $attachment_ids ) {
		return ac_helper()->image->get_images_by_ids( $attachment_ids, $this->image_sizes() );
	}

	/**
	 * @param int $post_id
	 *
	 * @return false|string Post Label
	 */
	public function post( $post_id ) {
		switch ( $this->column->get_option( 'post_property_display' ) ) {

			case 'author' :
				$user_id = ac_helper()->post->get_raw_field( 'post_author', $post_id );
				$label = ac_helper()->user->get_display_name( $user_id );
				break;

			case 'id' :
				$label = $post_id;
				break;

			default:
				$label = get_the_title( $post_id );
				break;
		}

		return $label;
	}

	/**
	 * @param int $post_id
	 *
	 * @return string Url
	 */
	public function post_link_to( $post_id ) {
		$link = false;

		switch ( $this->column->get_option( 'post_link_to' ) ) {
			case 'edit_post' :
				$link = get_edit_post_link( $post_id );
				break;
			case 'view_post' :
				$link = get_permalink( $post_id );
				break;
			case 'edit_author' :
				$link = get_edit_user_link( ac_helper()->post->get_raw_field( 'post_author', $post_id ) );
				break;
			case 'view_author' :
				$link = get_author_posts_url( ac_helper()->post->get_raw_field( 'post_author', $post_id ) );
				break;
		}

		return $link;
	}

	/**
	 * @param int $author_id
	 *
	 * @return bool|string Url
	 */
	public function user_link_to( $author_id ) {
		$link = false;

		switch ( $this->column->get_option( 'user_link_to' ) ) {
			case 'edit_user' :
				$link = get_edit_user_link( $author_id );
				break;
			case 'view_user_posts' :
				$link = add_query_arg( array(
					'post_type' => $this->column->get_post_type(),
					'author'    => get_the_author_meta( 'ID' ),
				), 'edit.php' );
				break;
			case 'view_author' :
				$link = get_author_posts_url( $author_id );
				break;
			case 'email_user' :
				$email = get_the_author_meta( 'email', $author_id );
				$link = $email ? 'mailto:' . $email : false;
				break;
		}

		return $link;
	}

}