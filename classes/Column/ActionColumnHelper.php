<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	public function get( $type, $id ) {
		return $this->actions[ $type ][ $id ];
	}

}