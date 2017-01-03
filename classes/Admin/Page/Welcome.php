<?php

class AC_Admin_Page_Welcome extends AC_Admin_Page {

	public function __construct() {
		$this
			->set_slug( 'welcome' )
			->set_label( __( 'Welcome', 'codepress-admin-columns' ) )
			->set_show_in_menu( false );
	}

	private function get_sub_tabs() {
		return array(
			''          => 'Welcome',
			'changelog' => 'Changelog',
		);
	}

	private function get_current_tab() {
		return filter_input( INPUT_GET, 'sub_tab' );
	}

	private function display_changelog() {
		?>
        <h3><?php echo __( "Changelog for", 'codepress-admin-columns' ) . ' ' . AC()->get_version(); ?></h3>
		<?php

		$items = file_get_contents( AC()->get_plugin_dir() . 'readme.txt' );
		$items = explode( '= ' . AC()->get_version() . ' =', $items );
		$items = end( $items );
		$items = explode( "\n\n", $items );

		$changelog = false;
		foreach ( $items as $item ) {
			if ( 0 === strpos( $item, '*' ) ) {
				$changelog = $item;
				break;
			}
		}

		$items = array_filter( array_map( 'trim', explode( "*", $changelog ) ) );
		?>
        <ul class="cpac-changelog">
			<?php echo implode( '<br/>', $items ); ?>
        </ul>
		<?php
	}

	public function display() {
		?>

        <div id="cpac-welcome" class="wrap about-wrap">

            <h1><?php echo __( "Welcome to Admin Columns", 'codepress-admin-columns' ) . ' ' . AC()->get_version(); ?></h1>

            <div class="about-text">
				<?php _e( "Thank you for updating to the latest version!", 'codepress-admin-columns' ); ?>
				<?php _e( "Admin Columns is more polished and enjoyable than ever before. We hope you like it.", 'codepress-admin-columns' ); ?>
            </div>

            <div class="cpac-content-body">
                <h2 class="nav-tab-wrapper">
					<?php foreach ( $this->get_sub_tabs() as $slug => $label ) {
						echo ac_helper()->html->link( add_query_arg( array( 'sub_tab' => $slug ), $this->get_link() ), $label, array( 'class' => 'cpac-tab-toggle nav-tab' . ( $this->get_current_tab() == $slug ? ' nav-tab-active' : '' ) ) );
					} ?>

                </h2>

				<?php switch ( $this->get_current_tab() ) {

					case 'changelog' :
						$this->display_changelog();
						break;

					default :
						// TODO
						?>
                        <h3>Changes</h3>
                        <p>*</p>

						<?php
				}
				?>

            </div>

            <div class="cpac-content-footer">
                <a class="button-primary button-large" href="<?php echo esc_url( AC()->admin()->get_link( 'columns' ) ); ?>"><?php _e( "Start using Admin Columns", 'codepress-admin-columns' ); ?></a>
            </div>

        </div>
		<?php
	}

}