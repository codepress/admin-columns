<?php
namespace AC\Admin\Section;

use AC\ListScreenRepository\DataBase;
use AC\Message;

class Restore extends Custom {

	/** @var string */
	private $database_repository;

	public function __construct( DataBase $repo ) {
		parent::__construct(
			'restore',
			__( 'Restore Settings', 'codepress-admin-columns' ),
			__( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' )
		);

		$this->database_repository = $repo;
	}

	public function register() {
		add_action( 'admin_head', array( $this, 'request' ) );
	}

	public function request() {
		if ( ! $this->validate_request() ) {
			return;
		}

		foreach ( $this->database_repository->find_all() as $list_screen ) {
			$this->database_repository->delete( $list_screen->get_layout_id() );
		}

		do_action( 'ac/restore_all_columns' );

		$notice = new Message\Notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ) );
		$notice->register();
	}

	protected function display_fields() {
		?>
		<form method="post">

			<?php
			$this->nonce_field();
			$this->action_field();
			?>

			<input type="submit" class="button" name="ac-restore-defaults" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
		</form>
		<?php
	}

}