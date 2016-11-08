<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View_Label
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	protected $link;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var AC_Settings_Form_ElementAbstract
	 */
	protected $element;

	public function __construct( $label, AC_Settings_Form_ElementAbstract $element = null ) {
		$this->label = $label;
		$this->element = $element;
	}

	protected function render_description() {
		if ( ! $this->get_description() ) {
			return;
		}

		$template = '<p class="description">%s</p>';

		return sprintf( $template, $this->get_description() );
	}

	protected function render_link() {
		if ( ! $this->get_link() ) {
			return;
		}

		$template = '
			<a target="_blank" class="more-link" title="%s" href="%s">
				<span class="dashicons dashicons-external"></span>
			</a>';

		return sprintf( $template, esc_attr( __( 'View more', 'codepress-admin-columns' ) ), esc_url( $this->get_link() ) );
	}

	public function render() {
		if ( ! $this->label ) {
			return;
		}

		$template = '
			<td class="label %s">
				<label %s>
					<span class="label">%s</span>
					%s
					%s
				</label>
			</td>';

		$class = '';

		if ( $this->get_description() ) {
			$class = 'description';
		}

		$for = '';

		if ( $this->element ) {
			$for = ac_helper()->html->get_attribute_as_string( 'for', $this->element->get_id() );
		}

		return sprintf(
			$template,
			$class,
			$for,
			$this->label,
			$this->render_description(), $this->render_link()
		);
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return $this->link;
	}

	/**
	 * @param string $link
	 *
	 * @return AC_Settings_View_Label
	 */
	public function set_link( $link ) {
		$this->more_link = $link;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return AC_Settings_View_Label
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

}