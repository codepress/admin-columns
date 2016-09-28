<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Admin_Tab_Addons extends AC_Admin_TabAbstract {

	public function __construct() {
		$this
			->set_slug( 'addons' )
			->set_label( __( 'Add-ons', 'codepress-admin-columns' ) );
	}

	public function display() {
		$addon_groups = cpac()->addons()->get_addon_groups();
		$grouped_addons = cpac()->addons()->get_available_addons( true );
		?>
		<?php foreach ( $grouped_addons as $group_name => $addons ) : ?>
			<h3><?php echo $addon_groups[ $group_name ]; ?></h3>

			<ul class="cpac-addons">
				<?php foreach ( $addons as $addon_name => $addon ) : ?>
					<li>
						<div class="cpac-addon-content">
							<?php if ( ! empty( $addon['image'] ) ) : ?>
								<img src="<?php echo esc_attr( $addon['image'] ); ?>"/>
							<?php else : ?>
								<h3><?php echo esc_html( $addon['title'] ); ?></h3>
							<?php endif; ?>
						</div>
						<div class="cpac-addon-header">
							<h3><?php echo esc_html( $addon['title'] ); ?></h3>
							<p><?php echo esc_html( $addon['description'] ); ?></p>
						</div>
						<div class="cpac-addon-actions">
							<?php

							// Installed..
							if ( $plugin_basename = cpac()->addons()->get_installed_addon_plugin_basename( $addon_name ) ) : ?>
								<?php if ( is_plugin_active( $plugin_basename ) ) : ?>
									<?php $deactivation_url = wp_nonce_url( add_query_arg( array( 'action' => 'deactivate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Active', 'codepress-admin-columns' ); ?></a>
									<a href="<?php echo esc_url( $deactivation_url ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
								<?php else : ?>
									<?php $activation_url = wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Installed', 'codepress-admin-columns' ); ?></a>
									<a href="<?php echo esc_url( $activation_url ); ?>" class="button right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<?php

							// Not installed...
							else :

								if ( cpac_is_pro_active() ) :
									$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install', 'plugin' => $addon_name ), AC()->settings()->get_link( 'addons' ) ), 'install-cac-addon' );
									?>
									<a href="<?php echo esc_url( $install_url ); ?>" class="button"><?php _e( 'Download & Install', 'codepress-admin-columns' ); ?></a>
									<?php
								else : ?>
									<a target="_blank" href="<?php echo esc_url( ac_get_site_url( 'pricing-purchase' ) ); ?>" class="button"><?php _e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; // addons ?>
			</ul>
		<?php endforeach; // grouped_addons
	}

}