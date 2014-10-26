<?php
/**
 * Base class for columns containing action links for items.
 *
 * @since 2.2.6
 */
abstract class CPAC_Column_Actions extends CPAC_Column {

	/**
	 * Get a list of action links for an item (e.g. post) ID.
	 *
	 * @since 2.2.6
	 *
	 * @param int $item_id Item ID to get the list of actions for.
	 * @return array List of actions ([action name] => [action link]).
	 */
	abstract public function get_actions( $item_id );

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.6
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-actions';
		$this->properties['label'] = __( 'Actions', 'cpac' );

		// Options
		$this->options['use_icons'] = false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.6
	 */
	public function get_value( $post_id ) {

		$actions = $this->get_raw_value( $post_id );

		if ( ! empty( $this->options->use_icons ) ) {
			return implode( '', $this->convert_actions_to_icons( $actions ) );
		}

		return implode( ' | ', $actions );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.6
	 */
	public function get_raw_value( $post_id ) {

		/**
		 * Filter the action links for the actions column
		 *
		 * @since 2.2.9
		 *
		 * @param array List of actions ([action name] => [action link]).
		 * @param CPAC_Column_Actions $column_instance Column object.
		 */
		return apply_filters( 'cac/column/actions/action_links', $this->get_actions( $post_id ), $this );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.6
	 */
	public function display_settings() {

		parent::display_settings();

		$this->display_field_use_icons();
	}

	/**
	 * Display the settings field for using icons instead of text links.
	 *
	 * @since 2.2.6
	 */
	public function display_field_use_icons() {

		?>
		<tr class="column_editing">
			<?php $this->label_view( __( 'Use icons?', 'cpac' ), __( 'Use icons instead of text for displaying the actions.', 'cpac' ), 'use_icons' ); ?>
			<td class="input">
				<label for="<?php $this->attr_id( 'use_icons' ); ?>-yes">
					<input type="radio" value="1" name="<?php $this->attr_name( 'use_icons' ); ?>" id="<?php $this->attr_id( 'use_icons' ); ?>-yes"<?php checked( $this->options->use_icons, '1' ); ?> />
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $this->attr_id( 'use_icons' ); ?>-no">
					<input type="radio" value="" name="<?php $this->attr_name( 'use_icons' ); ?>" id="<?php $this->attr_id( 'use_icons' ); ?>-no"<?php checked( $this->options->use_icons, '' ); ?> />
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
		<?php
	}

	/**
	 * Convert items from a list of action links to icons (if they have an icon).
	 *
	 * @since 2.2.6
	 *
	 * @param array $actions List of actions ([action name] => [action link]).
	 * @return array List of actions ([action name] => [action icon link]).
	 */
	public function convert_actions_to_icons( $actions ) {

		$icons = $this->get_actions_icons();

		foreach ( $actions as $action => $link ) {
			if ( isset( $icons[ $action ] ) ) {
				// Add mandatory "class" HTML attribute
				if ( strpos( $link, 'class=' ) === false ) {
					$link = str_replace( '<a ', '<a class="" ', $link );
				}

				// Add icon and tooltip classes
				$link = preg_replace( '/class=["\'](.*?)["\']/', 'class="$1 cpac-tip button cpac-button-action dashicons hide-content dashicons-' . $icons[ $action ] . '"', $link, 1 );

				// Add tooltip title
				$link = preg_replace_callback( '/>(.*?)<\/a>/', array( $this, 'add_link_tooltip' ), $link );

				$actions[ $action ] = $link;
			}
		}

		return $actions;
	}

	/**
	 * Add the tooltip data attribute to the link
	 * Callback for preg_replace_callback
	 *
	 * @since 2.2.6.1
	 *
	 * @param array $matches Matches information from preg_replace_callback
	 * @return string Link part with tooltip attribute
	 */
	public function add_link_tooltip( $matches ) {
		return ' data-tip="' . esc_attr( $matches[1] ) . '">' . $matches[1] . '</a>';
	}

	/**
	 * Get a list of action names and their corresponding dashicons.
	 *
	 * @since 2.2.6
	 *
	 * @return array List of actions and icons ([action] => [dashicon]).
	 */
	public function get_actions_icons() {

		return array(
			'edit' => 'edit',
			'trash' => 'trash',
			'delete' => 'trash',
			'untrash' => 'undo',
			'view' => 'visibility',
			'inline hide-if-no-js' => 'welcome-write-blog'
		);
	}

}