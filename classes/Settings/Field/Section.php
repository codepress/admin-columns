<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_View_Section
	implements AC_Settings_ViewInterface  {

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $read_more;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var bool
	 */
	protected $hidden;

	/**
	 * @var AC_Settings_Form_ElementAbstract
	 */
	protected $element;

	/**
	 * @var AC_Settings_ViewAbstract[]
	 */
	protected $views;

	/**
	 * Available settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Events to listen to
	 *
	 * @var AC_Settings_Form_Event[]
	 */
	protected $events;

	/**
	 * @var AC_Column
	 */
	protected $column;

	/**
	 * @param array $settings
	 */
	public function __construct( AC_Column $column ) {
		$this->column = $column;
		$this->elements = array();
		$this->events = array();
	}

	/**
	 * Access input elements by their name
	 *
	 * @param string $name
	 *
	 * @return AC_Settings_Form_ElementAbstract|false
	 */
	public function __get( $name ) {
		if ( ! isset( $this->elements[ $name ] ) ) {
			return false;
		}

		return $this->elements[ $name ];
	}

	/**
	 * Echo the output from the render function
	 */
	public function display() {
		echo $this->render();
	}

	/**
	 * @return string|false
	 */
	protected function render_description() {
		if ( ! $this->get_description() ) {
			return false;
		}

		$template = '<p class="description">%s</p>';

		return sprintf( $template, $this->get_description() );
	}

	/**
	 * @return string
	 */
	protected function render_elements() {
		$elements = array();

		foreach ( $this->get_elements() as $element ) {
			$elements[] = $element->render();
		}

		return implode( "\n", $elements );
	}

	/**
	 * Render current views
	 *
	 *
	 * @return string|false
	 */
	protected function render_views() {
		$views = array();

		foreach ( $this->get_views() as $view ) {
			$views[] = $view->render();
		}

		$views = array_filter( $views );

		if ( empty( $views ) ) {
			return false;
		}

		return implode( "\n", $views );
	}

	/**
	 * @return string|false
	 */
	protected function render_read_more() {
		if ( ! $this->get_read_more() ) {
			return false;
		}

		$template = '
			<a target="_blank" class="more-link" title="%s" href="%s">
				<span class="dashicons dashicons-external"></span>
			</a>';

		return sprintf( $template, esc_attr( __( 'View more', 'codepress-admin-columns' ) ), esc_url( $this->get_read_more() ) );
	}

	/**
	 * @return string|false
	 */
	protected function render_label() {
		if ( ! $this->get_label() ) {
			return false;
		}

		$template = '
			<td class="%s">
				<label for="%s">
					<span>%s</span>
					%s %s
				</label>
			</td>';

		$class = 'label';

		if ( $this->get_description() ) {
			$class .= ' description';
		}

		return sprintf( $template, esc_attr( $class ), esc_attr( $this->get_for() ), $this->get_label(), $this->render_description(), $this->render_read_more() );
	}

	/**
	 * @param string|AC_Settings_ViewAbstract|AC_Settings_ViewAbstract[] $view The view that contains the rendered form elements
	 *
	 * @return string|void
	 */
	protected function render_layout() {
		// render subviews or just elements
		$method = $this->get_views()
			? 'render_views'
			: 'render_elements';

		$view = $this->$method;

		// todo: render elements and fields consecutive?

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

		return sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			json_encode( $this->events ),
			$this->render_label(),
			$colspan,
			$view
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
	public function get_read_more() {
		return $this->read_more;
	}

	/**
	 * @param string $link
	 *
	 * @return $this;
	 */
	public function set_read_more( $url ) {
		$this->read_more = $url;

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
	 * @return $this
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
	 * @return AC_Settings_ViewAbstract
	 */
	public function set_hidden( $hidden ) {
		$this->hidden = (bool) $hidden;

		return $this;
	}

	/**
	 * @return AC_Settings_Form_ElementAbstract[]
	 */
	public function get_elements() {
		return $this->elements;
	}

	/**
	 * @return AC_Settings_Form_ElementAbstract|false
	 */
	public function get_first_element() {
		$elements = $this->get_elements();

		if ( ! $elements ) {
			return false;
		}

		return array_shift( $elements );
	}

	/**
	 * @param AC_Settings_Form_ElementAbstract $element
	 *
	 * @return $this
	 */
	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		$name = $element->get_name();
		$value = $this->get_setting( $name );

		if ( $value ) {
			$element->set_value( $value );
		}

		// todo: this is not very elegant... maybe a helper and be more verbose when registering?
		$element->set_name( sprintf( 'columns[%s][%s]', $this->column->get_name(), $name ) );
		$element->set_id( sprintf( 'cpac-%s-%s', $this->column->get_name(), $name ) );

		$this->elements[] = $element;

		return $this;
	}

	/**
	 * Return the first element in this view
	 *
	 * If there is no element, it will take the first element of the first view.
	 *
	 * @return AC_Settings_Form_ElementAbstract|false
	 */
	public function get_for() {
		if ( $this->get_elements() ) {
			return $this->get_first_element()->get_id();
		}

		if ( $this->get_views() ) {
			return $this->get_first_view()->get_for();
		}

		return false;
	}

	/**
	 * @param AC_Settings_ViewInterface $element
	 *
	 * @return AC_Settings_ViewInterface
	 */
	public function add_view( AC_Settings_ViewInterface $view ) {
		$this->views[] = $view;

		return $view;
	}

	/**
	 * @return AC_Settings_ViewAbstract[]
	 */
	public function get_views() {
		return $this->views;
	}

	/**
	 * Return the first element
	 *
	 * @return $this|false
	 */
	public function get_first_view() {
		$views = $this->get_views();

		if ( empty( $views ) ) {
			return false;
		}

		return array_shift( $views );
	}

	/**
	 * Retrieve setting (value) for an element
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected function get_setting( $name ) {
		return $this->column->settings()->get_option( $name );
	}

	/**
	 * Listen to events
	 *
	 * @param AC_Settings_Form_Event $event
	 *
	 * @return $this
	 */
	protected function add_event( AC_Settings_Form_Event $event ) {
		$this->events[] = $event;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_events( $format = 'json' ) {
		$events = $this->events;

		switch ( $format ) {
			case 'json': {
				foreach ( $events as $k => $event ) {
					$events[ $k ] = $event->to_array();
				}

				$events = json_encode( $events );
			}
		}

		return $events;
	}

}