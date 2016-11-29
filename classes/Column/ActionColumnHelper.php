<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Holds all the row actions buttons for each content type (e.g. post, comment, user and media).
 * WP_List_Table does not have a method for retrieving row actions. This class uses their filters to fetch the actions.
 * For example usage see the AC_Column_Actions class.
 *
 * Class AC_Column_ActionColumnHelper
 */
class AC_Column_ActionColumnHelper {

	private $actions;

	/**
	 * @since 2.5
	 */
	protected static $_instance = null;

	/**
	 * @since 2.5
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_filter( 'comment_row_actions', array( $this, 'set_comment' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'set_post' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'set_post' ), 10, 2 );
		add_filter( 'media_row_actions', array( $this, 'set_media' ), 10, 2 );
		add_filter( 'user_row_actions', array( $this, 'set_user' ), 10, 2 );
	}

	public function set_comment( $actions, $comment ) {
		$this->actions[ 'comment' ][ $comment->ID ] = $actions;
	}

	public function set_post( $actions, $post ) {
		$this->actions[ 'post' ][ $post->ID ] = $actions;
	}

	public function set_media( $actions, $post ) {
		$this->actions[ 'media' ][ $post->ID ] = $actions;
	}

	public function set_user( $actions, $user ) {
		$this->actions[ 'user' ][ $user->ID ] = $actions;
	}

	/**
	 * Retrieve row actions like 'edit, trash, spam' etc.
	 *
	 * @param string $type
	 * @param int $id Object ID
	 *
	 * @return array Array with actions
	 */
	public function get( $type, $id ) {
		return isset( $this->actions[ $type ][ $id ] ) ? $this->actions[ $type ][ $id ] : array();
	}

}