<?php

namespace AC\Plugin\Update;

use AC\Container;
use AC\Plugin\Update;
use AC\Plugin\Version;
use AC\Preferences;
use DirectoryIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class V3201 extends Update {

	public function __construct() {
		parent::__construct( new Version( '3.2.1' ) );
	}

	public function apply_update() {
		$this->uppercase_class_files( Container::get_dir() . '/classes' );
		$this->update_notice_preference_review();
		$this->update_notice_preference_addons();
	}

	/**
	 * Set all files to the proper case
	 */
	protected function uppercase_class_files( $directory ) {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $directory, FilesystemIterator::SKIP_DOTS )
		);

		/** @var DirectoryIterator $leaf */
		foreach ( $iterator as $leaf ) {
			$file = $leaf->getFilename();

			if ( $leaf->isFile() && 'php' === $leaf->getExtension() && $file == strtolower( $file ) ) {
				@rename( $leaf->getPathname(), trailingslashit( $leaf->getPath() ) . ucfirst( $file ) );
			}
		}
	}

	/**
	 * Update user preferences for review
	 */
	private function update_notice_preference_review() {
		$mapping = [
			'ac_hide_notice_review'    => 'dismiss-review',
			'ac-first-login-timestamp' => 'first-login-review',
		];

		foreach ( $mapping as $old => $new ) {
			foreach ( $this->get_users_by_meta_key( $old ) as $user_id ) {

				$value = get_user_meta( $user_id, $old, true );

				$option = new Preferences\User( 'check-review', $user_id );
				$option->set( $new, $value, true );

				delete_user_meta( $user_id, $old );
			}
		}
	}

	/**
	 * Update user preferences for addons
	 */
	private function update_notice_preference_addons() {
		$mapping = [
			'ac_hide_notice_addons' => 'dismiss-notice',
		];

		foreach ( $mapping as $old => $new ) {
			foreach ( $this->get_users_by_meta_key( $old ) as $user_id ) {

				$value = get_user_meta( $user_id, $old, true );

				$option = new Preferences\User( 'check-addon-available', $user_id );
				$option->set( $new, $value, true );

				delete_user_meta( $user_id, $old );
			}
		}
	}

	/**
	 * @param string $key
	 *
	 * @return array ID's
	 */
	protected function get_users_by_meta_key( $key ) {
		$user_ids = get_users( [
			'fields'     => 'ids',
			'meta_query' => [
				[
					'key'     => $key,
					'compare' => 'EXISTS',
				],
			],
		] );

		if ( ! $user_ids ) {
			return [];
		}

		return $user_ids;
	}

}