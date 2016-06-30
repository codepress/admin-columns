<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Post_Attachment
 *
 * @since 2.0
 */
class CPAC_Column_Post_Attachment extends CPAC_Column {

	public function init() {
		parent::init();

		// Properties
		$this->properties['type'] = 'column-attachment';
		$this->properties['label'] = __( 'Attachments', 'codepress-admin-columns' );

		// Options
		$this->options['image_size'] = '';
		$this->options['image_size_w'] = 80;
		$this->options['image_size_h'] = 80;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$values = array();

		$ids = (array) $this->get_raw_value( $post_id );
		foreach ( $ids as $id ) {
			if ( $image = $this->get_image_formatted( $id ) ) {
				$values[] = '<div class="cacie-item" data-cacie-id="' . esc_attr( $id ) . '">' . $image . '</div>';
			}
		}

		return implode( $values );
	}

	public function get_raw_value( $post_id ) {
		$attachments = get_posts( array(
			'post_type'   => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $post_id,
			'fields'      => 'ids',
		) );

		return $attachments;
	}

	public function display_settings() {
		$this->display_field_preview_size();
	}

}