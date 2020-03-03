<?php

namespace AC\Helper\Select\Group\CustomField;

use AC\Helper\Select\Formatter;
use AC\Helper\Select\Group;
use AC\Helper\Select\Option;

class MultiSite extends Group {

	/**
	 * @var array $groups
	 */
	private $groups;

	public function __construct( Formatter $formatter ) {
		$this->groups = $this->get_groups();

		parent::__construct( $formatter );
	}

	private function get_groups() {
		global $wpdb;

		$groups = [];

		foreach ( get_sites() as $site ) {
			$label = __( 'Network Site:', 'codepress-admin-columns' ) . ' ' . ac_helper()->network->get_site_option( $site->blog_id, 'blogname' );

			if ( get_current_blog_id() == $site->blog_id ) {
				$label .= ' (' . __( 'current', 'codepress-admin-columns' ) . ')';
			}

			$groups[ $wpdb->get_blog_prefix( $site->blog_id ) ] = $label;
		}

		return array_reverse( $groups );
	}

	/**
	 * @param        $entity
	 * @param Option $option
	 *
	 * @return string
	 */
	public function get_label( $entity, Option $option ) {
		foreach ( $this->groups as $key => $label ) {
			if ( strpos( $entity, $key ) === 0 ) {
				return $label;
			}
		}

		return __( 'Default', 'codepress-admin-columns' );
	}

}