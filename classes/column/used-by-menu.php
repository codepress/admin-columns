<?php
/**
 * Column displaying the menus the item is used in. Supported by all object types that
 * can be referenced in menus (i.e. posts).
 *
 * @since 2.2.5
 */
class CPAC_Column_Used_By_Menu extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.5
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-used_by_menu';
		$this->properties['label']	 	= __( 'Used by Menu', 'cpac' );

		// Options
		$this->options['link_to_menu'] = false;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.2.5
	 */
	function apply_conditional() {
		if ( ! $this->get_meta_type() ) {
			return false;
		}
		return true;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.5
	 */
	function get_value( $object_id ) {

		$menus = array();
		if ( $menu_ids = $this->get_raw_value( $object_id ) ) {
			foreach ( $menu_ids as $menu_id ) {
				$term = get_term_by( 'id', $menu_id, 'nav_menu' );

				$title = $term->name;
				if ( 'on' == $this->options->link_to_menu ) {
					$title = '<a href="' . esc_url( add_query_arg( array( 'menu' => $menu_id ), admin_url('nav-menus.php') ) ) . '">' . $term->name .  '</a>';
				}

				$menus[] = $title;
			}
		}

		return implode( ', ', $menus );
	}

	/**
	 * Get object metatype of the storage model
	 *
	 * @since 2.2.5
	 */
	function get_meta_type() {
		$object_type = false;
		if ( isset( $this->storage_model->taxonomy ) ) {
			$object_type = $this->storage_model->taxonomy;
		}
		elseif ( isset( $this->storage_model->post_type ) ) {
			$object_type = $this->storage_model->post_type;
		}
		return $object_type;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.2.5
	 */
	function get_raw_value( $object_id ) {

		$object_type = $this->get_meta_type();


		$menu_item_ids = get_posts( array(
			'post_type' => 'nav_menu_item',
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => '_menu_item_object_id',
					'value' => $object_id
				),
				array(
					'key' => '_menu_item_object',
					'value' => $object_type
				),
			)
		) );

		if ( ! $menu_item_ids ) {
			return false;
		}

		$menu_ids = wp_get_object_terms( $menu_item_ids, 'nav_menu', array( 'fields' => 'ids') );
		if ( ! $menu_ids || is_wp_error( $menu_ids ) ) {
			return false;
		}

		return $menu_ids;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.5
	 */
	public function display_settings() {

		$this->display_field_link_to_menu();
	}

	/**
	 * Display the settings field for selecting whether the column value should link to the corresponding post
	 *
	 * @since 2.2.5
	 */
	public function display_field_link_to_menu() {

		$field_key = 'link_to_menu';
		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( __( 'Link to menu', 'cpac' ), __( 'This will make the title link to the menu.', 'cpac' ), $field_key ); ?>
			<td class="input">
				<label for="<?php $this->attr_id( $field_key ); ?>-on">
					<input type="radio" value="on" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-on"<?php checked( $this->options->link_to_menu, 'on' ); ?> />
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $this->attr_id( $field_key ); ?>-off">
					<input type="radio" value="off" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-off"<?php checked( in_array( $this->options->link_to_menu, array( '', 'off' ) ) ); ?> />
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
		<?php
	}

}