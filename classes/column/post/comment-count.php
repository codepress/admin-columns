<?php
/**
 * Column displaying the number of comments for an item, displaying either the total
 * amount of comments, or the amount per status (e.g. "Approved", "Pending").
 *
 * @since 2.0
 */
class CPAC_Column_Post_Comment_Count extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 		= 'column-comment_count';
		$this->properties['label']	 		= __( 'Comment count', 'cpac' );
		$this->properties['is_cloneable']	= true;

		// Options
		$this->options['comment_status'] = '';
	}

	/**
	 * get_comment_stati
	 * @since 2.0
	 */
	function get_comment_stati() {

		return array(
			'total_comments'	=> __( 'Total', 'cpac' ),
			'approved'			=> __( 'Approved', 'cpac' ),
			'moderated'			=> __( 'Pending', 'cpac' ),
			'spam'				=> __( 'Spam', 'cpac' ),
			'trash'				=> __( 'Trash', 'cpac' ),
		);
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		$value = '';

		$status = $this->options->comment_status;
		$count 	= $this->get_raw_value( $post_id );

		if ( $count !== '' ) {
			$names = $this->get_comment_stati();

			$url   = esc_url( add_query_arg( array( 'p' => $post_id, 'comment_status' => $status ), admin_url( 'edit-comments.php' ) ) );
			$value = "<a href='{$url}' class='cp-{$status}' title='" . $names[ $status ] . "'>{$count}</a>";
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		$value = '';

		$status = $this->options->comment_status;
		$count 	= wp_count_comments( $post_id );

		if ( isset( $count->{$status} ) ) {
			$value = $count->{$status};
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	function apply_conditional() {

		return post_type_supports( $this->storage_model->key, 'comments' );
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {
		?>
		<tr class="column_comment-count">
			<?php $this->label_view( __( 'Comment status', 'cpac' ), __( 'Select which comment status you like to display.', 'cpac' ), 'comment-status' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'comment_status' ); ?>" id="<?php $this->attr_id( 'comment-status' ); ?>">
				<?php foreach ( $this->get_comment_stati() as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $this->options->comment_status ) ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	}
}