<?php

abstract class AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $more_link;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var bool
	 */
	protected $hidden;

	/**
	 * @var AC_Settings_FieldAbstract[]
	 */
	protected $fields;

	/**
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	protected $elements;

	/**
	 * Available settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Events to listen to
	 *
	 * @var array
	 */
	protected $events;

	/**
	 * @param array $settings
	 */
	public function __construct( array $settings = array() ) {
		$this->elements = array();
		$this->events = array();

		$this->set_settings( $settings );
	}

	abstract protected function render_field();

	protected function render_description() {
		if ( ! $this->get_description() ) {
			return;
		}

		$template = '<p class="description">%s</p>';

		return sprintf( $template, $this->get_description() );
	}

	protected function render_more_link() {
		if ( ! $this->get_more_link() ) {
			return;
		}

		$template = '
			<a target="_blank" class="more-link" title="%s" href="%s">
				<span class="dashicons dashicons-external"></span>
			</a>';

		return sprintf( $template, esc_attr( __( 'View more', 'codepress-admin-columns' ) ), esc_url( $this->get_more_link() ) );
	}

	protected function render_label() {
		if ( ! $this->get_label() ) {
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

		$classes = array();

		if ( $this->get_description() ) {
			$classes[] = 'description';
		}

		$for = '';

		if ( $this->get_first_element() ) {
			$for = ac_helper()->html->get_attribute_as_string( 'for', $this->get_first_element()->get_id() );
		}

		return sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			$for,
			$this->label,
			$this->render_description(),
			$this->render_link()
		);
	}

	/**
	 * @param string $field
	 */
	public function render() {
		$template = '
			<table class="widefat %s" data-events="%s">
				<tr>
					%s
					<td class="input" colspan="%d">
						%s
					</td>
				</tr>
			</table>';

		$classes = array();

		if ( $this->is_hidden() ) {
			$classes[] = 'hide';
		}

		$colspan = $this->get_label() ? 1 : 2;

		sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			json_encode( $this->events ),
			$this->render_label(),
			$colspan,
			$this->render_field()
		);
	}

	/**
	 * @return $this
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_more_link() {
		return $this->more_link;
	}

	/**
	 * @param string $link
	 *
	 * @return $this;
	 */
	public function set_more_link( $link ) {
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

	/**
	 * @return boolean
	 */
	public function is_hidden() {
		return $this->hidden;
	}

	/**
	 * @param boolean $hidden
	 *
	 * @return AC_Settings_FieldAbstract
	 */
	public function set_hidden( $hidden ) {
		$this->hidden = (bool) $hidden;

		return $this;
	}

	/**
	 * @return AC_Settings_FieldAbstract[]
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * @param AC_Settings_FieldAbstract $field
	 *
	 * @return $this
	 */
	public function add_field( AC_Settings_FieldAbstract $field ) {
		$this->fields[] = $field;

		return $this;
	}

	/**
	 * Return the first element
	 *
	 * @return $this|false
	 */
	protected function get_first_field() {
		$fields = $this->get_fields();

		if ( empty( $fields ) ) {
			return false;
		}

		return $fields[0];
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
	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
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

	/**
	 * Listen to change events
	 *
	 * @param string $target
	 * @param string $action Defaults to toggle
	 *
	 * @return $this
	 */
	protected function add_event_change( $target, $action = 'toggle' ) {
		return $this->add_event( 'change', $target, $action );
	}

	/**
	 * Listen to events
	 *
	 * @param string $event Type of event like change, focus
	 * @param string $target A valid target in the dom.
	 * @param string $action
	 *
	 * @return $this
	 */
	protected function add_event( $event, $target, $action ) {
		$this->events[] = array(
			'type'   => $event,
			'target' => $target,
			'action' => $action,
		);

		return $this;
	}

}