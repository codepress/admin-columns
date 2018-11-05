<?php

namespace AC;

abstract class Integration {

	/** @var string */
	private $basename;

	/** @var string */
	private $title;

	/** @var string */
	private $logo;

	/** @var string */
	private $page;

	/** @var string */
	private $plugin_link;

	/**
	 * @return bool
	 */
	abstract public function is_plugin_active();

	/**
	 * @param Screen $screen
	 *
	 * @return bool
	 */
	abstract public function show_notice( Screen $screen );

	public function __construct( $basename, $title, $logo, $plugin_link = null, $page = null ) {
		if ( null === $plugin_link ) {
			$plugin_link = $this->search_plugin( $title );
		}
		if ( null === $page ) {
			$page = 'pricing-purchase';
		}

		$this->basename = $basename;
		$this->title = $title;
		$this->plugin_link = $plugin_link;
		$this->logo = $logo;
		$this->page = $page;
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	private function search_plugin( $name ) {
		return add_query_arg(
			array(
				'tab'  => 'search',
				'type' => 'term',
				's'    => $name,
			),
			admin_url( 'plugin-install.php' )
		);
	}

	/**
	 * @return string
	 */
	public function get_basename() {
		return $this->basename;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return dirname( $this->basename );
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_logo() {
		return $this->logo;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return sprintf( __( 'Display and edit %s fields in the posts overview in seconds!', 'codepress-admin-columns' ), $this->title );
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return ac_get_site_utm_url( $this->page, 'addon', dirname( $this->basename ) );
	}

	/**
	 * @return string
	 */
	public function get_plugin_link() {
		return $this->plugin_link;
	}

	/**
	 * Determines when the placeholder column is shown for a particular list screen.
	 *
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public function show_placeholder( ListScreen $list_screen ) {
		return true;
	}

}