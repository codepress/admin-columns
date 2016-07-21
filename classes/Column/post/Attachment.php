<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Attachment extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-attachment';
		$this->properties['label'] = __( 'Attachments', 'codepress-admin-columns' );

		$this->options['image_size'] = '';
		$this->options['image_size_w'] = 80;
		$this->options['image_size_h'] = 80;
	}

	public function get_value( $post_id ) {
		$values = array();

		$ids = (array) $this->get_raw_value( $post_id );
		foreach ( $ids as $id ) {
			if ( $image = $this->format->images( $id ) ) {
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
		$this->field_settings->image();
	}

}