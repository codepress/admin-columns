<?php

namespace AC\Admin;

use AC\View;

class Tooltip {

	/** @var string */
	private $id;

	/** @var string */
	private $content;

	/** @var string */
	private $link_label;

	/** @var string */
	private $title;

	/** @var string */
	private $position = 'right';

	public function __construct( $id, array $args ) {
		$this->id = $id;
		$this->title = __( 'Notice', 'codepress-admin-columns' );
		$this->link_label = __( 'Instructions', 'codepress-admin-columns' );

		$this->populate( $args );
	}

	/**
	 * @param array $args
	 */
	private function populate( $args ) {
		foreach ( $args as $key => $value ) {
			$method = 'set_' . $key;

			if ( method_exists( $this, $method ) ) {
				call_user_func( [ $this, $method ], $value );
			}
		}
	}

	/**
	 * @param string $id
	 *
	 * @return Tooltip
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @param string $content
	 *
	 * @return Tooltip
	 */
	public function set_content( $content ) {
		$this->content = $content;

		return $this;
	}

	/**
	 * @param string $title
	 *
	 * @return Tooltip
	 */
	public function set_title( $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_link_label( $label ) {
		$this->link_label = $label;

		return $this;
	}

	/**
	 * @param string $position
	 *
	 * @return Tooltip
	 */
	public function set_position( $position ) {
		$this->position = $position;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		$view = new View( [
			'id'       => $this->id,
			'position' => $this->position,
			'label'    => $this->link_label,
		] );

		$view->set_template( 'admin/tooltip-label' );

		return $view->render();
	}

	/**
	 * @return string
	 */
	public function get_instructions() {
		$view = new View( [
			'id'       => $this->id,
			'title'    => $this->title,
			'content'  => $this->content,
			'position' => $this->position,
		] );

		$view->set_template( 'admin/tooltip-body' );

		return $view->render();
	}

}