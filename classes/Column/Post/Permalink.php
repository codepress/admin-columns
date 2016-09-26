<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying full item permalink (including URL).
 *
 * @since 2.0
 */
class AC_Column_Post_Permalink extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-permalink';
		$this->properties['label'] = __( 'Permalink', 'codepress-admin-columns' );

		$this->default_options['link_to_post'] = false;
	}

	public function get_value( $post_id ) {
		$link = $this->get_raw_value( $post_id );

		if ( 'on' == $this->get_option( 'link_to_post' ) ) {
			$link = ac_helper()->html->link( $link, $link, array( 'target' => '_blank' ) );
		}

		return $link;
	}

	public function get_raw_value( $post_id ) {
		return get_permalink( $post_id );
	}

	public function display_settings() {
		$this->field_settings->field( array(
			'type'        => 'radio',
			'name'        => 'link_to_post',
			'label'       => __( 'Link to post', 'codepress-admin-columns' ),
			'description' => __( 'This will make the permalink clickable.', 'codepress-admin-columns' ),
			'options'     => array(
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			),
			'default_value'     => 'off'
		) );
	}

}