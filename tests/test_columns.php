<?php

/**
 * Unit Test: Columns
 *
 */
class CAC_Columns_Test extends WP_UnitTestCase {

	public function test_post_columns() {
		// populate storage models
		$cac = new CPAC;
		add_filter( 'cac/is_cac_screen', '__return_true' ); // fake current screen for storage model to work
		$cac->set_storage_models();

		// get registered columns
		$storage_model = $cac->get_storage_model('post');
		$storage_model->set_columns( true ); // ignore the screen check
		$registered_columns = $storage_model->get_registered_columns();

		// create dummy post
		$post_id = $this->factory->post->create( array( 'post_type' => 'post' ) );

		// test all columns
		$this->assertEquals( $post_id, $registered_columns[ 'column-postid' ]->get_raw_value( $post_id ) );
		$this->assertNotEmpty( $registered_columns[ 'column-permalink' ]->get_raw_value( $post_id ), 'Page has no permalink.' );
		$this->assertNotEmpty( $post_id, $registered_columns[ 'column-actions' ]->get_raw_value( $post_id ) );
	}

	public function test_page_specific_columns() {
		// populate storage models
		$cac = new CPAC;
		add_filter( 'cac/is_cac_screen', '__return_true' ); // fake current screen for storage model to work
		$cac->set_storage_models();

		// get registered columns
		$storage_model = $cac->get_storage_model('page');
		$storage_model->set_columns( true ); // ignore the screen check
		$registered_columns = $storage_model->get_registered_columns();

		// create dummy post
		$page_id = $this->factory->post->create( array( 'post_type' => 'page' ) );
		update_post_meta( $page_id, '_wp_page_template', 'foo.php' );

		// test all columns
		$this->assertEquals( 'foo.php', $registered_columns[ 'column-page_template' ]->get_raw_value( $page_id ) );
	}

	public function test_populating_storage_models() {
		// populate storage models
		$cac = new CPAC;
		add_filter( 'cac/is_cac_screen', '__return_true' ); // fake current screen for storage model to work
		$cac->set_storage_models();

		$this->assertNotEmpty( $cac->storage_models, 'No storage models found.' );

		if ( $cac->storage_models ) {
			foreach( $cac->storage_models as $storage_model ) {

				$this->assertNotEmpty( $storage_model->get_default_columns(), sprintf( 'No columns models found for %s.', $storage_model->key ) );
			}
		}
	}

	public function test_posttypes() {
		$cac = new CPAC();
		$this->assertNotEmpty( $cac->get_post_types(), 'No post types found.' );
	}

	public function test_ajax() {
		$cac = new CPAC();
		$this->assertFalse( $cac->is_doing_ajax(), 'Doing Ajax.' );
	}

    /**
     * @dataProvider get_required_plugins_provider
     */
    public function test_required_plugins_are_active( $plugin ) {
		$this->assertTrue( is_plugin_active( $plugin ), sprintf('%s is not activated.', $plugin ) );
    }

    /**
     * @return array
     */
    public function get_required_plugins_provider() {
		return array( array( 'codepress-admin-columns/codepress-admin-columns.php' ) );
    }
}