<?php
/**
 * Columng displaying full item permalink (including URL).
 *
 * @since 2.0
 */
class CPAC_Column_Post_Permalink extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-permalink';
		$this->properties['label']	 	= __( 'Permalink', 'cpac' );

		// Options
		$this->options['link_to_post'] = false;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		$value = $this->get_raw_value( $post_id );

		if ( $this->options->link_to_post == 'on' ) {
			$value = '<a href="' . esc_attr( $value ) .'" target="_blank">' . $value . '</a>';
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {

		return get_permalink( $post_id );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.1
	 */
	public function display_settings() {

		$this->display_field_link_to_post();
	}

	/**
	 * Display the settings field for selecting whether the column value should link to the corresponding post
	 *
	 * @since 2.2.1
	 */
	public function display_field_link_to_post() {

		$field_key = 'link_to_post';
		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( __( 'Link to post', 'cpac' ), __( 'This will make the permalink clickable.', 'cpac' ), $field_key ); ?>
			<td class="input">
				<label for="<?php $this->attr_id( $field_key ); ?>-on">
					<input type="radio" value="on" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-on"<?php checked( $this->options->link_to_post, 'on' ); ?> />
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $this->attr_id( $field_key ); ?>-off">
					<input type="radio" value="off" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-off"<?php checked( in_array( $this->options->link_to_post, array( '', 'off' ) ) ); ?> />
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
		<?php
	}
}