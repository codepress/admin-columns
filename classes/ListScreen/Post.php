<?php

namespace AC\ListScreen;

use AC;
use AC\ListScreenPost;
use AC\WpListTableFactory;
use WP_Posts_List_Table;

class Post extends ListScreenPost {

	public function __construct( $post_type ) {
		parent::__construct( $post_type );

		$this->set_screen_base( 'edit' )
		     ->set_group( 'post' )
		     ->set_key( $post_type )
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
			AC\Column\Post\Attachment::class,
			AC\Column\Post\Author::class,
			AC\Column\Post\AuthorName::class,
			AC\Column\Post\BeforeMoreTag::class,
			AC\Column\Post\Categories::class,
			AC\Column\Post\CommentCount::class,
			AC\Column\Post\Comments::class,
			AC\Column\Post\CommentStatus::class,
			AC\Column\Post\Content::class,
			AC\Column\Post\Date::class,
			AC\Column\Post\DatePublished::class,
			AC\Column\Post\Depth::class,
			AC\Column\Post\EstimatedReadingTime::class,
			AC\Column\Post\Excerpt::class,
			AC\Column\Post\FeaturedImage::class,
			AC\Column\Post\Formats::class,
			AC\Column\Post\ID::class,
			AC\Column\Post\LastModifiedAuthor::class,
			AC\Column\Post\Menu::class,
			AC\Column\Post\Modified::class,
			AC\Column\Post\Order::class,
			AC\Column\Post\PageTemplate::class,
			AC\Column\Post\PasswordProtected::class,
			AC\Column\Post\Path::class,
			AC\Column\Post\Permalink::class,
			AC\Column\Post\PingStatus::class,
			AC\Column\Post\PostParent::class,
			AC\Column\Post\Shortcodes::class,
			AC\Column\Post\Shortlink::class,
			AC\Column\Post\Slug::class,
			AC\Column\Post\Status::class,
			AC\Column\Post\Sticky::class,
			AC\Column\Post\Tags::class,
			AC\Column\Post\Taxonomy::class,
			AC\Column\Post\Title::class,
			AC\Column\Post\TitleRaw::class,
			AC\Column\Post\WordCount::class,
		] );
	}

}