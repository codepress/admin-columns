<?php

abstract class AC_Column_Meta extends AC_Column {

	/**
	 * Return the meta_key of this column
	 *
	 * @return string
	 */
	abstract public function get_meta_key();

	/**
	 * Is data stored serialized?
	 *
	 * @var bool
	 */
	private $serialized = false;

	/**
	 * @return bool
	 */
	public function is_serialized() {
		return $this->serialized;
	}

	/**
	 * @param bool $serialized
	 */
	public function set_serialized( $serialized ) {
		$this->serialized = (bool) $serialized;
	}

	/**
	 * @see AC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	// TODO: now that this is single, we need to look at all the Model_Meta and possibily all cols. to maybe remove get_raw_value
	public function get_raw_value( $id ) {
		$raw_value = '';

		// TODO can this be false?
		if ( $meta_key = $this->get_meta_key() ) {
			$raw_value = $this->get_meta_value( $id, $meta_key, true );
		}

		// TODO: maybe make decprecated? So many classes extend from this class and in no way certain it will run.
		// TODO: Used by Pods, needed after creating a addon? Pods can extend from column_meta?
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

}