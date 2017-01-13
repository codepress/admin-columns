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
	// TODO: maybe use single? It makes more sense in a way...
	public function get_raw_value( $id ) {
		$raw_value = '';

		if ( $meta_key = $this->get_meta_key() ) {
			$raw_value = $this->get_meta_value( $id, $meta_key, false );
		}

		// TODO: maybe make decprecated? So many classes extend from this class and in no way certain it will run
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
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_meta_values( array $args = array() ) {
		$defaults = array(
			'type' => $this->get_meta_type(),
			'key'  => $this->get_meta_key(),
		);

		$query = new AC_Meta_Query( array_merge( $defaults, $args ) );

		return $query->get_results();
	}

}