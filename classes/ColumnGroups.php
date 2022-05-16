<?php

namespace AC;

class ColumnGroups {

	/**
	 * @return Groups
	 */
	public static function get_groups() {
		$groups = new Groups();

		$groups->register_group( 'default', __( 'Default', 'codepress-admin-columns' ) );
		$groups->register_group( 'plugin', __( 'Plugins' ), 20 );
		$groups->register_group( 'custom_field', __( 'Custom Fields', 'codepress-admin-columns' ), 30 );
		$groups->register_group( 'media-meta', __( 'Meta', 'codepress-admin-columns' ), 32 );
		$groups->register_group( 'media-image', __( 'Image', 'codepress-admin-columns' ), 33 );
		$groups->register_group( 'media-video', __( 'Video', 'codepress-admin-columns' ), 34 );
		$groups->register_group( 'media-audio', __( 'Audio', 'codepress-admin-columns' ), 35 );
		$groups->register_group( 'media-document', __( 'Document', 'codepress-admin-columns' ), 35 );
		$groups->register_group( 'media-file', __( 'File', 'codepress-admin-columns' ), 35 );
		$groups->register_group( 'custom', __( 'Custom', 'codepress-admin-columns' ), 40 );

		$repo = new IntegrationRepository();

		foreach ( $repo->find_all() as $integration ) {
			$integration_plugin = new PluginInformation( $integration->get_basename() );

			if ( $integration->is_plugin_active() && ! $integration_plugin->is_active() ) {
				$groups->register_group( $integration->get_slug(), $integration->get_title(), 11 );
			}
		}

		do_action( 'ac/column_groups', $groups );

		return $groups;
	}

}