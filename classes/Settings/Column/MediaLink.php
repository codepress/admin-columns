<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\View;

class MediaLink extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	protected $media_link_to;

	protected function define_options() {
		return array(
			'media_link_to' => '',
		);
	}

	public function format( $value, $original_value ) {
		$id = $original_value;

		switch ( $this->get_media_link_to() ) {
			case 'download' :
				$link = get_edit_post_link( $id );

				break;
			default :
				$link = false;
		}

		if ( $link ) {
			$value = ac_helper()->html->link( $link, $value );
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )->set_options( $this->get_display_options() );

		$view = new View( array(
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		// Default options
		$options = array(
			''            => __( 'None' ),
			'download'   => __( 'Download', 'codepress-admin-columns' ),
			'view_post'   => __( 'View Post' ),
			'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
			'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
		);

		if ( $this->column instanceof AC\Column\Relation ) {
			$relation_options = array(
				'edit_post'   => _x( 'Edit %s', 'post' ),
				'view_post'   => _x( 'View %s', 'post' ),
				'edit_author' => _x( 'Edit %s Author', 'post', 'codepress-admin-columns' ),
				'view_author' => _x( 'View Public %s Author Page', 'post', 'codepress-admin-columns' ),
			);

			$label = $this->column->get_relation_object()->get_labels()->singular_name;

			foreach ( $relation_options as $k => $option ) {
				$options[ $k ] = sprintf( $option, $label );
			}
		}

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_media_link_to() {
		return $this->media_link_to;
	}

	/**
	 * @param string $media_link_to
	 *
	 * @return bool
	 */
	public function set_media_link_to( $media_link_to ) {
		$this->media_link_to = $media_link_to;

		return true;
	}

}