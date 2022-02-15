<?php

namespace AC;

use AC\Type\Url;

abstract class Integration {

	/** @var string */
	private $basename;

	/** @var string */
	private $title;

	/** @var string */
	private $logo;

	/** @var Url */
	private $url;

	/** @var string */
	private $plugin_link;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @param string $basename
	 * @param string $title
	 * @param string $logo
	 * @param string $description
	 * @param string $plugin_link
	 * @param Url    $url
	 */
	public function __construct( $basename, $title, $logo, $description, $plugin_link = null, Url $url = null ) {
		if ( null === $plugin_link ) {
			$plugin_link = $this->search_plugin( $title );
		}

		if ( null === $url ) {
			$url = new Url\Site( Url\Site::PAGE_PRICING );
		}

		$this->basename = $basename;
		$this->title = $title;
		$this->logo = $logo;
		$this->description = $description;
		$this->plugin_link = $plugin_link;
		$this->url = $url;
	}

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

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	private function search_plugin( $name ) {
		return add_query_arg(
			[
				'tab'  => 'search',
				'type' => 'term',
				's'    => $name,
			],
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
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return ( new Url\UtmTags( $this->url, 'addon', $this->get_slug() ) )->get_url();
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