<?php
/**
 * CPAC_Column_Post_Taxonomy
 *
 * @since 2.0.0
 */
class CPAC_Column_Taxonomy extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']			= 'column-taxonomy';
		$this->properties['label']			= __( 'Taxonomy', 'cpac' );
		$this->properties['is_cloneable']	= true;

		// define additional options
		$this->options['taxonomy']	= ''; // taxonomy slug

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		$values = array();

		$term_ids = $this->get_raw_value( $post_id );

		$post_type = $this->get_post_type();

		if ( $term_ids && ! is_wp_error( $term_ids ) ) {
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id, $this->options->taxonomy );
				$title = esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'edit' ) );

				$link = "<a href='edit.php?post_type={$post_type}&{$term->taxonomy}={$term->slug}'>{$title}</a>";
				if ( $post_type == 'attachment' )
					$link = "<a href='upload.php?taxonomy={$term->taxonomy}&term={$term->slug}'>{$title}</a>";

				$values[] = $link;
			}
		}

		return implode( ', ', $values );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return wp_get_post_terms( $post_id, $this->options->taxonomy, array( 'fields' => 'ids' ) );
	}

	/**
	 * Get post type
	 *
	 * @since 2.1.1
	 */
	function get_post_type() {

		return isset( $this->storage_model->post_type ) ? $this->storage_model->post_type : false;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		$post_type = $this->get_post_type();

		if ( $post_type && get_object_taxonomies( $post_type ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0.0
	 */
	function display_settings() {

		$taxonomies = get_object_taxonomies( $this->get_post_type(), 'objects' );
		?>

		<tr class="column_taxonomy">
			<?php $this->label_view( __( "Taxonomy", 'cpac' ), '', 'taxonomy' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'taxonomy' ); ?>" id="<?php $this->attr_id( 'taxonomy' ); ?>">
				<?php foreach ( $taxonomies as $taxonomy ) : ?>
					<option value="<?php echo $taxonomy->name; ?>"<?php selected( $taxonomy->name, $this->options->taxonomy ) ?>><?php echo $taxonomy->label; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php
	}
}