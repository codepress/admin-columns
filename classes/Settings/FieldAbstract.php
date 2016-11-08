<?php

abstract class AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	private $elements;

	/**
	 * Available settings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * @param array $settings
	 */
	public function __construct( array $settings = array() ) {
		$this->elements = array();

		$this->set_settings( $settings );
	}

	/**
	 * Assign one or more elements that make up this field
	 */
	protected abstract function set_elements();

	protected function render_elements() {
		$elements = '';

		foreach ( $this->get_elements() as $element ) {
			$elements .= $element->render();
		}

		return $elements;
	}

	public function render() {
		$template = '
			<table class="%s" data-handle="%s" data-refresh="%d">
				<tr>
					%s
					<td class="input" data-trigger="%s" colspan="%d">
						%s
					</td>
				</tr>
			</table>';

		$classes = array( 'widefat', $this->name );

		//if ( $this->hide ) {
		//$classes[] = 'hide';
		//}

		//$data_trigger = $this->toggle_trigger ? $this->format_attr( 'id', $this->toggle_trigger ) : '';
		//$data_handle = $this->toggle_handle ? $this->format_attr( 'id', $this->toggle_handle ) : '';

		$colspan = 2;
		$label = '';

		if ( $this->get_label() ) {
			$colspan = 1;
			$label = $this->get_label()->render();
		}

		sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			'//data-handle',
			'//data-refresh',
			$label,
			'//data-trigger',
			$colspan,
			$this->render_elements()
		);
	}

	/**
	 * @return AC_Settings_View_Label|false
	 */
	public function get_label() {
		if ( ! ( $this->label instanceof AC_Settings_View_Label ) ) {
			return false;
		}

		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( AC_Settings_View_Label $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return AC_Settings_Form_ElementAbstract[]
	 */
	public function get_elements() {
		return $this->elements;
	}

	/**
	 * @param AC_Settings_Form_ElementAbstract $element
	 *
	 * @return $this
	 */
	protected function add_element( AC_Settings_Form_ElementAbstract $element ) {
		$this->elements[] = $element;

		return $this;
	}

	/**
	 * Return the first element
	 *
	 * @return $this|false
	 */
	protected function get_first_element() {
		$elements = $this->get_elements();

		if ( empty( $elements ) ) {
			return false;
		}

		return $elements[0];
	}

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function set_settings( $settings ) {
		$this->settings = $settings;

		return $this;
	}

	/**
	 * Retrieve setting (value) for an element
	 *
	 * @param string $name
	 * @param string $default
	 *
	 * @return string
	 */
	protected function get_setting( $name, $default = null ) {
		$setting = $default;

		foreach ( $this->settings as $key => $value ) {
			if ( $key === $name ) {
				$setting = $value;

				break;
			}
		}

		return $setting;
	}

}