<?php
namespace AC;

class InitializeColumns implements Registrable {

	/**
	 * @var DefaultColumns
	 */
	private $default_columns;

	public function __construct( DefaultColumns $default_columns ) {
		$this->default_columns = $default_columns;
	}

	public function register() {
		add_action( 'ac/settings/scripts', array( $this, 'scripts' ) );
		add_action( 'admin_body_class', function( $classes ){
			$classes .= ' ac-blocking';


			return $classes;
		},100);


	}

	public function scripts() {
		$urls = array();

		foreach ( $this->get_uninitialized_list_screens() as $list_screen ) {
			$urls[] = array(
				'screen_link' => add_query_arg( array( 'acp_action' => 'store_default_columns' ), $list_screen->get_screen_link() ),
				'label'       => $list_screen->get_label()
			);
		}

		wp_enqueue_script( 'ac-initialize-columns', AC()->get_url() . '/assets/js/initialize-columns.js' );
		wp_localize_script( 'ac-initialize-columns', 'AC_INIT_LISTSCREENS', $urls );
	}

	private function get_uninitialized_list_screens() {
		$list_screens = AC()->get_list_screens();

		foreach ( $list_screens as $key => $list_screen ) {
			$columns = $this->default_columns->get( $list_screen->get_key() );

			if ( ! empty( $columns ) ) {
				unset( $list_screens[ $key ] );
			}
		}

		return $list_screens;
	}

}