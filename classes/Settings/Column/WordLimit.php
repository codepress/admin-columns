<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class WordLimit extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var int
	 */
	private $excerpt_length;

	protected function set_name() {
		$this->name = 'word_limit';
	}

	protected function define_options() {
		return [
			'excerpt_length' => 20,
		];
	}

	public function create_view() {
		$setting = $this->create_element( 'number' )
		                ->set_attributes( [
			                'min'  => 0,
			                'step' => 1,
		                ] );

		$view = new View( [
			'label'   => __( 'Word Limit', 'codepress-admin-columns' ),
			'tooltip' => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'setting' => $setting,
		] );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_excerpt_length() {
		return $this->excerpt_length;
	}

	/**
	 * @param int $excerpt_length
	 *
	 * @return bool
	 */
	public function set_excerpt_length( $excerpt_length ) {
		$this->excerpt_length = $excerpt_length;

		return true;
	}

	public function format( $value, $original_value ) {
		$values = [];

		foreach ( (array) $value as $_string ) {
			$values[] = ac_helper()->string->trim_words( $_string, $this->get_excerpt_length() );
		}

		return ac_helper()->html->implode( $values );
	}

}