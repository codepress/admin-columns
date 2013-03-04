<?php

/**
 * Upgrade
 *
 * Class largely based on code from Elliot Condon ( props goes to him )
 *
 * @since 2.0.0
 */
class CPAC_Upgrade {

	function __construct() {

		// DEV
		update_option( 'cpac_version', '1.0.0' );

		// run upgrade based on version
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_action( 'wp_ajax_cpac_upgrade', array( $this, 'upgrade' ) );
	}

	/**
	 * Add submenu page
	 *
	 * @since 2.0.0
	 */
	public function admin_menu() {

		$page = add_submenu_page( 'codepress-admin-columns', __( 'Upgrade', 'cpac' ), __( 'Upgrade', 'cpac' ), 'manage_options', 'cpac-upgrade', array( $this, 'start_upgrade' ) );

		add_action( "admin_print_scripts-{$page}", array( $this, 'admin_scripts' ) );
	}

	/**
	 * Scripts
	 *
	 * @since 2.0.0
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'cpac-upgrade', CPAC_URL . 'assets/js/upgrade.js', array( 'jquery' ), CPAC_VERSION );
		wp_enqueue_style( 'cpac-admin', CPAC_URL . 'assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * init
	 *
	 * @since 2.0.0
	 */
	public function init() {

		$version = get_option( 'cpac_version', false );

		// Maybe upgrade?
		if ( $version ) {

			// run every upgrade
			if ( $version < CPAC_VERSION ) {

				// flush this transient so new custom columns get's added.
				delete_transient( 'cpac_custom_columns' );
			}

			// run only when upgrade is needed
			if ( $version < CPAC_UPGRADE_VERSION ) {

				// display upgrade message on every page except upgrade page itself
				if ( ! ( isset( $_REQUEST['page'] ) && 'cpac-upgrade' === $_REQUEST['page'] ) ) {

					$message = 	__( 'Admin Columns', 'cpac' ) . ' v' . CPAC_VERSION . ' ' .
								__( 'requires a database upgrade','cpac' ) .
								' (<a class="thickbox" href="' . admin_url() .
								'plugin-install.php?tab=plugin-information&plugin=codepress-admin-columns&section=changelog&TB_iframe=true&width=640&height=559">' .
								__( 'why?', 'cpac' ) .'</a>). '	.
								__( "Please", 'cpac' ) .' <a href="http://codex.wordpress.org/Backing_Up_Your_Database">' .
								__( "backup your database", 'cpac' ) .'</a>, '.
								__( "then click", 'cpac' ) . ' <a href="' . admin_url() . 'admin.php?page=cpac-upgrade" class="button">' .
								__( "Upgrade Database", 'cpac' ) . '</a>';

					cpac_admin_message( $message, 'updated' );
				}
			}

			// run when NO upgrade is needed
			elseif ( $version < CPAC_VERSION ) {

				update_option( 'cpac_version', CPAC_VERSION );
			}

		}

		// Fresh install
		else {
			update_option( 'cpac_version', CPAC_VERSION );
		}
	}

	/**
	 * Init Upgrade Process
	 *
	 * @since 2.0.0
	 */
	public function upgrade() {

		// vars
		$return = array(
			'status'	=>	false,
			'message'	=>	"",
			'next'		=>	false,
		);

		$version = $_POST['version'];

		// versions
		switch( $version ) {

			case '2.0.0' :

				// old settings
				if ( $old_settings = get_option( 'cpac_options' ) ) {

					print_r( $old_settings );

					foreach ( $old_settings as $storage_key => $old_columns ){

						$columns = array();

						if ( $old_columns ) {
							foreach ( $old_columns as $old_column_name => $old_column ) {

								// rename type
								$type = '';

								// set clone
								$clone = '';

								// convert old settings to new
								$columns[ $type ] = array(
									'type' 	=> $type,
						            'clone' => $clone,
						            'state' => $old_column['state'],
						            'label' => $old_column['label'],
						            'width' => $old_column['width']
								);
							}
						}

						//update_option( "cpac_options_{$storage_key}", $columns );

					}
				}


				// update version
				// update_option( 'cpac_version', $version );

				$return = array(
			    	'status'	=>	true,
					'message'	=>	__( "Migrating Column Settings", 'cpac' ) . '...',
					'next'		=>	false,
			    );

				break;

		}

		// return json
		echo json_encode( $return );
		die;

	}

	/*
	* Starting points of the upgrade process
	*
	* @since 2.0.0
	*/
	public function start_upgrade() {

$current = get_option( "cpac_options_post" );
		print_r( $current );
		//exit;

		$version 	= get_option( 'cpac_version', '1.0.0' );
		$next 		= false;

		// list of starting points
		if( $version < '2.0.0' ) {
			$next = '2.0.0';
		}

		// Run upgrade?
		if( $next ) : ?>
		<script type="text/javascript">
			run_upgrade("<?php echo $next; ?>");
		</script>
		<?php

		// No update required
		else :
			echo '<p>No Upgrade Required</p>';
		endif;
	}

}