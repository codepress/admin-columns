<?php

class AC_Settings_Column_LinkToPost extends AC_Settings_Column_Toggle
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $link_to_post;

	protected function define_options() {
		return array(
			'link_to_post' => 'off',
		);
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
	 * @return bool
	 */
	public function set_link_to_post( $link_to_post ) {
		$this->link_to_post = $link_to_post;

		return true;
	}

	/**
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		$link = get_permalink( $value_formatter->get_id() );

		if ( 'on' === $this->get_link_to_post() ) {
			$link = ac_helper()->html->link( $link, $link, array( 'target' => '_blank' ) );
		}

		$value_formatter->value = $link;

		return $value_formatter;
	}

}