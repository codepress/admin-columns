<?php
/**
 * CPAC_Column_Post_Actions
 *
 * @since 2.0
 */
class CPAC_Column_Post_Actions extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
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
	 * @since 2.0
	 */
	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_raw_value( $post_id ) {
		return $this->get_column_value_actions( $post_id );
	}

	/**
	 * Get column value of post actions
	 *
	 * This part is copied from the Posts List Table class
	 *
	 * @since 1.4.2
	 *
	 * @param int $post_id
	 * @return string Actions
	 */
	private function get_column_value_actions( $post_id ) {
		$actions = array();

		$post 				= get_post($post_id);
		$title 				= _draft_or_post_title();
		$post_type_object 	= get_post_type_object( $post->post_type );
		$can_edit_post 		= current_user_can( $post_type_object->cap->edit_post, $post->ID );
		$quickedit_enabled	= false;

		$stored_columns = $this->storage_model->get_stored_columns();

		foreach ( $stored_columns as $column ) {
			if ( $column['type'] == 'title' ) {
				$quickedit_enabled = true;
			}
		}

		if ( $can_edit_post && 'trash' != $post->post_status ) {
			$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';

			if ( $quickedit_enabled ) {
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
		}
		if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
			if ( 'trash' == $post->post_status )
				$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
			elseif ( EMPTY_TRASH_DAYS )
				$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
			if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
				$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
		}
		if ( $post_type_object->public ) {
			if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
				if ( $can_edit_post )
					$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
			} elseif ( 'trash' != $post->post_status ) {
				$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
			}
		}

		// Use icons instead of links
		/*
		@todo: debug first
		if ( ! empty( $this->options->use_icons ) ) {
			$icons = array(
				'edit' => 'edit',
				'trash' => 'trash',
				'delete' => 'trash',
				'untrash' => 'undo',
				'view' => 'visibility',
				'inline hide-if-no-js' => 'welcome-write-blog'
			);

			foreach ( $actions as $action => $link ) {
				if ( isset( $icons[ $action ] ) ) {
					if ( strpos( $link, 'class=' ) === false ) {
						$link = str_replace( '<a ', '<a class="" ', $link );
					}

					$link = preg_replace( '/class=["\'](.*?)["\']/', 'class="$1 cpac-tip button cpac-button-action dashicons hide-content dashicons-' . $icons[ $action ] . '"', $link, 1 );
					$link = preg_replace_callback( '/>(.*?)<\/a>/', function( $matches ) {
						return ' data-tip="' . esc_attr( $matches[1] ) . '">' . $matches[1] . '</a>';
					}, $link );

					$actions[ $action ] = $link;
				}
			}

			return implode( '', $actions );
		}
		*/

		return implode(' | ', $actions);
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.2.4
	 */
	public function display_settings() {

		parent::display_settings();

		//$this->display_field_use_icons();
	}

	/**
	 * @since 2.2.4
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
}