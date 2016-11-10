<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_ViewAbstract
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
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	protected $elements;

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
	 * Echo the output from the render function
	 */
	public function display() {
		echo $this->render();
	}

	/**
	 * @return string|void
	 */
	protected function render_description() {
		if ( ! $this->get_description() ) {
			return;
		}

		$template = '<p class="description">%s</p>';

		return sprintf( $template, $this->get_description() );
	}

	/**
	 * @return string|void
	 */
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

	/**
	 * @return string|void
	 */
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
		$element = $this->get_first_element();

		if ( $element ) {
			$for = ac_helper()->html->get_attribute_as_string( 'for', $element->get_id() );
		}

		return sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			$for,
			$this->label,
			$this->render_description(),
			$this->render_more_link()
		);
	}

	/**
	 * @param string|AC_Settings_ViewAbstract|AC_Settings_ViewAbstract[] $view The view that contains the rendered form elements
	 *
	 * @return string|void
	 */
	public function render_layout() {
		$view = $this->render();

		// check for multiple views or single element
		if ( is_array( $view ) ) {
			$view = $this->render_views( $view );
		} elseif ( $view instanceof AC_Settings_Form_ElementAbstract ) {
			$view = $view->render();
		}

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
	 * Render all views in this view
	 *
	 * @return string|void
	 */
	protected function render_views() {
		$views = array();

		foreach ( $this->views as $view ) {
			$views[] = $view->render();
		}

		$views = array_filter( $views );

		if ( empty( $views ) ) {
			return;
		}

		return implode( "\n", $views );
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
	protected function get_elements() {
		return $this->elements;
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
	public function get_first_element() {
		$elements = $this->get_elements();

		if ( $elements ) {
			return array_shift( $elements );
		}

		$view = $this->get_first_view();

		if ( $view && $element = $view->get_first_element() ) {
			return $element;
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