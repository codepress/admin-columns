<?php
defined( 'ABSPATH' ) or die();

/**
 * Base class for columns containing action links for items.
 *
 * @since 2.2.6
 */
abstract class AC_Column_ActionsAbstract extends AC_Column {

	/**
	 * Get a list of action links for an item (e.g. post) ID.
	 *
	 * @since 2.2.6
	 *
	 * @param int $id Item ID to get the list of actions for.
	 *
	 * @return array List of actions ([action name] => [action link]).
	 */
	abstract protected function get_object_type();

	/**
	 * @since 2.2.6
	 */
	public function __construct() {
		$this->set_type( 'column-actions' );
		$this->set_label( __( 'Actions', 'codepress-admin-columns' ) );
	}

	/**
	 * @since 2.2.6
	 */
	public function get_value( $id ) {
		if ( $this->get_option( 'use_icons' ) ) {
			return '<span class="cpac_use_icons"></span>';
		}

		return '';
	}

	/**
	 * @see AC_Column::display_settings()
	 * @since 2.2.6
	 */
	public function display_settings() {
		parent::display_settings();
		// Use icons
		$this->field_settings->field( array(
			'type'          => 'radio',
			'name'          => 'use_icons',
			'label'         => __( 'Use icons?', 'codepress-admin-columns' ),
			'description'   => __( 'Use icons instead of text for displaying the actions.', 'codepress-admin-columns' ),
			'options'       => array(
				'1' => __( 'Yes' ),
				''  => __( 'No' ),
			),
			'default_value' => '',
		) );
	}

}