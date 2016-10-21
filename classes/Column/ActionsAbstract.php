<?php
defined( 'ABSPATH' ) or die();

/**
 * Base class for columns containing action links for items.
 *
 * @since 2.2.6
 */
abstract class AC_Column_ActionsAbstract extends CPAC_Column {

	/**
	 * Get a HTML list of action links for an item (e.g. post) ID.
	 *
	 * @since 2.2.6
	 *
	 * @param int $id Item ID to get the list of actions for.
	 *
	 * @return string HTML list of actions
	 */
	abstract public function get_actions( $id );

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-actions';
		$this->properties['label'] = __( 'Actions', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {

		// Load icons styling in the footer
		wp_enqueue_style( 'ac-action-icons', AC()->get_plugin_url() . 'assets/css/column-action-icons' . AC()->minified() . '.css', false, AC()->get_version() );

		$actions = $this->get_actions( $id );

		if ( $this->get_option( 'use_icons' ) ) {
			$actions = '<div class="show-icons">' . $actions . '</div>';
		}

		return $actions;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.6
	 */
	public function display_settings() {
		parent::display_settings();

		$this->field_settings->field( array(
			'type'        => 'radio',
			'name'        => 'use_icons',
			'label'       => __( 'Use icons?', 'codepress-admin-columns' ),
			'description' => __( 'Use icons instead of text for displaying the actions.', 'codepress-admin-columns' ),
			'options'     => array(
				'1' => __( 'Yes' ),
				''  => __( 'No' ),
			),
			'default_value' => '',
		) );
	}

}