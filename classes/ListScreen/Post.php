<?php

namespace AC\ListScreen;

use AC\Column;
use AC\ListScreenPost;
use AC\WpListTableFactory;
use WP_Posts_List_Table;

class Post extends ListScreenPost {

	public function __construct( $post_type ) {
		parent::__construct( $post_type );

		// TODO `set_screen_base` can be removed when `get_screen_link` has made abstract
		$this->set_screen_base( 'edit' )
			// TODO `set_group` can be removed
		     ->set_group( 'post' )
		     ->set_key( $post_type )
			// TODO `set_screen_id` can be removed
		     ->set_screen_id( $this->get_screen_base() . '-' . $post_type );
	}

	/**
	 * @see WP_Posts_List_Table::column_default
	 */
	public function set_manage_value_callback() {
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", [ $this, 'manage_value' ], 100, 2 );
	}

	/**
	 * @return WP_Posts_List_Table
	 */
	// TODO `get_list_table` can this be removed?
	protected function get_list_table() {
		return ( new WpListTableFactory() )->create_post_table( $this->get_screen_id() );
	}

	/**
	 * @since 2.0
	 */
	public function get_screen_link() {
		return add_query_arg( [ 'post_type' => $this->get_post_type() ], parent::get_screen_link() );
	}

	/**
	 * @return string|false
	 */
	public function get_label() {
		return $this->get_post_type_label_var( 'name' );
	}

	/**
	 * @return false|string
	 */
	public function get_singular_label() {
		return $this->get_post_type_label_var( 'singular_name' );
	}

	/**
	 * @param $column_name
	 * @param $id
	 *
	 * @since 2.4.7
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

	protected function register_column_types() {
		parent::register_column_types();

		$this->register_column_types_from_list( [
			Column\Post\Attachment::class,
			Column\Post\Author::class,
			Column\Post\AuthorName::class,
			Column\Post\BeforeMoreTag::class,
			Column\Post\Categories::class,
			Column\Post\CommentCount::class,
			Column\Post\Comments::class,
			Column\Post\CommentStatus::class,
			Column\Post\Content::class,
			Column\Post\Date::class,
			Column\Post\DatePublished::class,
			Column\Post\Depth::class,
			Column\Post\EstimatedReadingTime::class,
			Column\Post\Excerpt::class,
			Column\Post\FeaturedImage::class,
			Column\Post\Formats::class,
			Column\Post\ID::class,
			Column\Post\LastModifiedAuthor::class,
			Column\Post\Menu::class,
			Column\Post\Modified::class,
			Column\Post\Order::class,
			Column\Post\PageTemplate::class,
			Column\Post\PasswordProtected::class,
			Column\Post\Path::class,
			Column\Post\Permalink::class,
			Column\Post\PingStatus::class,
			Column\Post\PostParent::class,
			Column\Post\Shortcodes::class,
			Column\Post\Shortlink::class,
			Column\Post\Slug::class,
			Column\Post\Status::class,
			Column\Post\Sticky::class,
			Column\Post\Tags::class,
			Column\Post\Taxonomy::class,
			Column\Post\Title::class,
			Column\Post\TitleRaw::class,
			Column\Post\WordCount::class,
		] );
	}

}