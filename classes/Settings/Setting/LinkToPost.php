<?php

class AC_Settings_Setting_LinkToPost extends AC_Settings_Setting_Toggle
implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $link_to_post = 'off';

	protected function set_managed_options() {
		$this->managed_options = array( 'link_to_post' );
	}

	public function create_view() {
		$view = parent::create_view();

		$view->set_data( array(
			'label'   => __( 'Link to post', 'codepress-admin-columns' ),
			'tooltip' => __( 'This will make the permalink clickable..', 'codepress-admin-columns' ),
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_link_to_post() {
		return $this->link_to_post;
	}

	/**
	 * @param string $link_to_post
	 *
	 * @return $this
	 */
	public function set_link_to_post( $link_to_post ) {
		$this->link_to_post = $link_to_post;

		return $this;
	}

	/**
	 * @param int $post_id
	 *
	 * @return false|string
	 */
	public function format( $post_id ) {
		$link = get_permalink( $post_id );

		if ( 'on' === $this->get_link_to_post() ) {
			$link = ac_helper()->html->link( $link, $link, array( 'target' => '_blank' ) );
		}

		return $link;
	}

}