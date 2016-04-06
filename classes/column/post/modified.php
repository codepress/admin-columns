<?php
/**
 * CPAC_Column_Post_Modified
 *
 * @since 2.0
 */
class CPAC_Column_Post_Modified extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 	= 'column-modified';
		$this->properties['label']	 	= __( 'Last modified', 'codepress-admin-columns' );

		$this->options['date_format'] = '';
	}

	public function get_value( $post_id ) {
		$modified = $this->get_raw_value( $post_id );
		$date_format = $this->get_option( 'date_format' );

		if ( ! $date_format ) {
			$value = $this->get_date( $modified ) . ' ' . $this->get_time( $modified );
		}
		else {
			$value = date_i18n( $date_format, strtotime( $modified ) );
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_modified', $post_id );
	}

	function display_settings() {
		$this->display_field_date_format();
	}
}