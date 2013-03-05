<?php
/**
 * CPAC_Column_Post_Taxonomy
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Taxonomy extends CPAC_Column {

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

		if ( $terms = get_the_terms( $post_id, $this->options->taxonomy ) ) {
			$values = array();

			foreach ( $terms as $term ) {

				$title 		= esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'edit' ) );
				$values[]	= "<a href='edit.php?post_type={$this->storage_model->key}&{$term->taxonomy}={$term->slug}'>{$title}</a>";
			}
			$value = implode( ', ', $values );
		}

		return implode( ', ', $values );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		if ( get_object_taxonomies( $this->storage_model->key ) ) {
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

		$taxonomies = get_object_taxonomies( $this->storage_model->key, 'objects' );
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