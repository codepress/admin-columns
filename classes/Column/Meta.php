<?php

abstract class AC_Column_Meta extends AC_Column {

	/**
	 * Return the meta_key of this column
	 *
	 * @return string
	 */
	abstract public function get_meta_key();

	/**
	 * @see AC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	// TODO: maybe use single? It makes more sense in a way...
	public function get_raw_value( $id ) {
		$raw_value = '';

		if ( $meta_key = $this->get_meta_key() ) {
			$raw_value = $this->get_meta_value( $id, $meta_key, false );
		}

		return apply_filters( 'cac/column/meta/raw_value', $raw_value, $id, $meta_key, $this );
	}

	/**
	 * Retrieve metadata object type (e.g., comment, post, or user)
	 *
	 * @since NEWVERSION
	 * @return bool
	 */
	public function get_meta_type() {
		return $this->get_list_screen()->get_meta_type();
	}

	/**
	 * Get meta value
	 *
	 * @param int $id
	 * @param string $meta_key
	 * @param bool $single
	 *
	 * @return mixed
	 */
	public function get_meta_value( $id, $meta_key, $single = true ) {
		return get_metadata( $this->get_meta_type(), $id, $meta_key, $single );
	}

	/**
	 * @param int $id
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_meta_values( $ids, array $args = array() ) {
		return ac_helper()->meta->get_values_by_ids( $ids, $this->get_meta_key(), $this->get_meta_type(), $args );
	}

	/**
	 * Returns the properties needed to write custom SQL for the current meta table
	 *
	 * @return false|object
	 */
	public function get_meta_table_properties() {
		return ac_helper()->meta->get_meta_table_properties( $this->get_meta_type() );
	}

}