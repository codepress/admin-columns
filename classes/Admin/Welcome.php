<?php
defined( 'ABSPATH' ) or die();

class AC_Admin_Welcome {

	public function has_upgrade_run() {
		// Should only be set after upgrade
		$show_welcome = false !== get_transient( 'cpac_show_welcome' );

		// Should only be set manual
		if ( isset( $_GET['info'] ) ) {
			$show_welcome = true;
		}

		if ( ! $show_welcome ) {
			return false;
		}

		// Set check that welcome should not be displayed.
		delete_transient( 'cpac_show_welcome' );

		return true;
	}

	public function display() {
		$tab = ! empty( $_GET['info'] ) ? $_GET['info'] : 'whats-new';
		?>

		<div id="cpac-welcome" class="wrap about-wrap">

			<h1><?php _e( "Welcome to Admin Columns", 'codepress-admin-columns' ); ?><?php echo cpac()->get_version(); ?></h1>

			<div class="about-text">
				<?php _e( "Thank you for updating to the latest version!", 'codepress-admin-columns' ); ?>
				<?php _e( "Admin Columns is more polished and enjoyable than ever before. We hope you like it.", 'codepress-admin-columns' ); ?>
			</div>

			<div class="cpac-content-body">
				<h2 class="nav-tab-wrapper">
					<a class="cpac-tab-toggle nav-tab <?php if ( $tab == 'whats-new' ) {
						echo 'nav-tab-active';
					} ?>" href="<?php echo esc_url( cpac()->settings()->get_settings_url( 'info' ) ); ?>whats-new"><?php _e( "What’s New", 'codepress-admin-columns' ); ?></a>
					<a class="cpac-tab-toggle nav-tab <?php if ( $tab == 'changelog' ) {
						echo 'nav-tab-active';
					} ?>" href="<?php echo esc_url( cpac()->settings()->get_settings_url( 'info' ) ); ?>changelog"><?php _e( "Changelog", 'codepress-admin-columns' ); ?></a>
				</h2>

				<?php if ( 'whats-new' === $tab ) : ?>

					<h3><?php _e( "Important", 'codepress-admin-columns' ); ?></h3>

					<h4><?php _e( "Database Changes", 'codepress-admin-columns' ); ?></h4>
					<p><?php _e( "The database has been changed between versions 1 and 2. But we made sure you can still roll back to version 1x without any issues.", 'codepress-admin-columns' ); ?></p>

					<?php if ( get_option( 'cpac_version', false ) < cpac()->get_upgrade_version() ) : ?>
						<p><?php _e( "Make sure you backup your database and then click", 'codepress-admin-columns' ); ?>
							<a href="<?php echo esc_url( cpac()->settings()->get_settings_url( 'upgrade' ) ); ?>" class="button-primary"><?php _e( "Upgrade Database", 'codepress-admin-columns' ); ?></a>
						</p>
					<?php endif; ?>

					<h4><?php _e( "Potential Issues", 'codepress-admin-columns' ); ?></h4>
					<p><?php _e( "Do to the sizable refactoring the code, surounding Addons and action/filters, your website may not operate correctly. It is important that you read the full", 'codepress-admin-columns' ); ?>
						<a href="<?php ac_site_url(); ?>migrating-from-v1-to-v2" target="_blank"><?php _e( "Migrating from v1 to v2", 'codepress-admin-columns' ); ?></a> <?php _e( "guide to view the full list of changes.", 'codepress-admin-columns' ); ?> <?php printf( __( "When you have found a bug please <a href='%s'>report them to us</a> so we can fix it in the next release.", 'codepress-admin-columns' ), 'mailto:info@codepress.nl' ); ?>
					</p>

					<div class="cpac-alert cpac-alert-error">
						<p>
							<strong><?php _e( "Important!", 'codepress-admin-columns' ); ?></strong> <?php _e( "If you updated the Admin Columns plugin without prior knowledge of such changes, Please roll back to the latest", 'codepress-admin-columns' ); ?>
							<a href="http://downloads.wordpress.org/plugin/codepress-admin-columns.1.4.9.zip"> <?php _e( "version 1", 'codepress-admin-columns' ); ?></a> <?php _e( "of this plugin.", 'codepress-admin-columns' ); ?>
						</p>
					</div>

				<?php endif; ?>
				<?php if ( 'changelog' === $tab ) : ?>

					<h3><?php _e( "Changelog for", 'codepress-admin-columns' ); ?><?php echo cpac()->get_version(); ?></h3>
					<?php

					$items = file_get_contents( cpac()->get_plugin_dir() . 'readme.txt' );
					$items = explode( '= ' . cpac()->get_version() . ' =', $items );
					$items = end( $items );
					$items = current( explode( "\n\n", $items ) );
					$items = current( explode( "= ", $items ) );
					$items = array_filter( array_map( 'trim', explode( "*", $items ) ) );

					?>
					<ul class="cpac-changelog">
						<?php foreach ( $items as $item ) :
							$item = explode( 'http', $item );
							?>
							<li><?php echo $item[0]; ?><?php if ( isset( $item[1] ) ): ?><a
									href="http<?php echo $item[1]; ?>"
									target="_blank"><?php _e( "Learn more", 'codepress-admin-columns' ); ?></a><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>

				<?php endif; ?>
				<hr/>

			</div><!--.cpac-content-body-->

			<div class="cpac-content-footer">
				<a class="button-primary button-large" href="<?php echo esc_url( cpac()->settings()->get_settings_url( 'general' ) ); ?>"><?php _e( "Start using Admin Columns", 'codepress-admin-columns' ); ?></a>
			</div><!--.cpac-content-footer-->

		</div>
		<?php
	}
}