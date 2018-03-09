<?php

class AC_Notice_Plugin extends AC_Notice {

	/**
	 * @var string
	 */
	protected $plugin_basename;

	/**
	 * @var array
	 */
	protected $status;

	/**
	 * @var string
	 */
	protected $icon;

	/**
	 * @param string $plugin_basename
	 * @param string $message
	 * @param string $type
	 */
	public function __construct( $plugin_basename, $message, $type = null ) {
		if ( null == $type ) {
			$type = self::WARNING;
		}

		parent::__construct( $message, $type );

		$this->set_plugin_basename( $plugin_basename );
		$this->set_status();
	}

	public function register() {
		add_action( 'after_plugin_row_' . $this->plugin_basename, array( $this, 'display' ), 11 );
	}

	public function display() {
		$data = array(
			'plugin_basename' => $this->plugin_basename,
			'icon'            => $this->icon,
			'class'           => $this->class,
			'message'         => $this->message,
			'type'            => $this->type,
			'statis'          => $this->status,
		);

		$view = new AC_View( $data );
		$view->set_template( 'message/plugin-row' );

		echo $view;
	}

	protected function get_icon_by_type( $type ) {
		$mapping = array(
			self::SUCCESS => '\f147',
			self::WARNING => '\f348',
			self::ERROR   => '\f534',
			self::INFO    => '\f463',
		);

		if ( ! isset( $mapping[ $type ] ) ) {
			return false;
		}

		return $mapping[ $type ];
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$icon = $this->get_icon_by_type( $type );

		if ( $icon ) {
			$this->set_icon( $icon );
		}

		return $this;
	}

	/**
	 * @param null|string $class
	 *
	 * @return $this
	 */
	protected function set_class( $class = null ) {
		if ( null === $class ) {
			if ( $this->type === self::SUCCESS ) {
				$class = 'updated-message notice-success';
			}

			if ( $this->type === self::INFO ) {
				$class = self::WARNING;
			}
		}

		parent::set_class( $class );

		return $this;
	}

	/**
	 * @param string $plugin_basename
	 */
	protected function set_plugin_basename( $plugin_basename ) {
		$this->plugin_basename = $plugin_basename;
	}

	protected function set_status() {
		if ( is_plugin_active( $this->plugin_basename ) ) {
			$this->status = 'active';
		}
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