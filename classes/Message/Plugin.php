<?php

namespace AC\Message;

use AC\Message;
use AC\View;

class Plugin extends Message {

	/**
	 * @var string
	 */
	protected $plugin_basename;

	/**
	 * @var string
	 */
	protected $icon;

	/**
	 * @param string $plugin_basename
	 * @param string $message
	 * @param string $type
	 */
	public function __construct( $plugin_basename ) {
		parent::__construct();

		$this->plugin_basename = $plugin_basename;
		$this->type = self::WARNING;
		$this->icon = $this->get_icon_by_current_type();
	}

	public function register() {
		add_action( 'after_plugin_row_' . $this->plugin_basename, array( $this, 'display' ), 11 );
	}

	public function create_view() {
		switch ( $this->type ) {
			case self::SUCCESS :
				$class = 'updated-message notice-success';

				break;
			case self::INFO :
				$class = self::WARNING;

				break;
			default:
				$class = $this->type;
		}

		$status = is_plugin_active( $this->plugin_basename )
			? 'active'
			: '';

		$data = array(
			'plugin_basename' => $this->plugin_basename,
			'icon'            => $this->icon,
			'class'           => $class,
			'message'         => $this->message,
			'type'            => $this->type,
			'status'          => $status,
		);

		$view = new View( $data );
		$view->set_template( 'message/plugin' );

		return $view;
	}

	/**
	 * @return string
	 */
	protected function get_icon_by_current_type() {
		$mapping = array(
			self::SUCCESS => '\f147',
			self::WARNING => '\f348',
			self::ERROR   => '\f534',
			self::INFO    => '\f463',
		);

		if ( ! isset( $mapping[ $this->type ] ) ) {
			return false;
		}

		return $mapping[ $this->type ];
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Set the icon of this notice
	 *
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function set_icon( $icon ) {
		$this->icon = $icon;

		return $this;
	}

}