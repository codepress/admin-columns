<?php

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
class CPAC_Column_Taxonomy extends CPAC_Column {

	public function init() {
		parent::init();

		// Properties
		$this->properties['type'] = 'column-taxonomy';
		$this->properties['label'] = __( 'Taxonomy', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;

		// Options
		$this->options['taxonomy'] = ''; // Taxonomy slug
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$term_ids = $this->get_raw_value( $post_id );

		return $this->get_terms_for_display( $term_ids, $this->get_taxonomy() );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return wp_get_post_terms( $post_id, $this->get_taxonomy(), array( 'fields' => 'ids' ) );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.3.4
	 */
	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {
		$post_type = $this->get_post_type();
		if ( ! $post_type || ! get_object_taxonomies( $post_type ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {
		$taxonomies = get_object_taxonomies( $this->get_post_type(), 'objects' );

		foreach ( $taxonomies as $index => $taxonomy ) {
			if ( $taxonomy->name == 'post_format' ) {
				unset( $taxonomies[ $index ] );
			}
		}
		?>

		<tr class="column_taxonomy">
			<?php $this->label_view( __( "Taxonomy", 'codepress-admin-columns' ), '', 'taxonomy' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'taxonomy' ); ?>" id="<?php $this->attr_id( 'taxonomy' ); ?>">
					<?php foreach ( $taxonomies as $taxonomy ) : ?>
						<option value="<?php echo $taxonomy->name; ?>"<?php selected( $taxonomy->name, $this->get_taxonomy() ) ?>><?php echo $taxonomy->label; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php
	}
}