<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\MetaType;
use AC\PostType;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\PostTypeSlug;
use AC\Type\Uri;
use AC\Type\Url;
use WP_Post_Type;

class Post extends TableScreen implements PostType, ListTable, TableScreen\MetaType
{

    protected $post_type;

    public function __construct(WP_Post_Type $post_type)
    {
        parent::__construct(
            new ListKey($post_type->name),
            'edit-' . $post_type->name
        );

        $this->post_type = $post_type;
    }

    public function list_table(): AC\ListTable
    {
        return ListTableFactory::create_post($this->screen_id);
    }

    public function get_post_type(): PostTypeSlug
    {
        return new PostTypeSlug($this->post_type->name);
    }

    public function get_query_type(): string
    {
        return 'post';
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_attr_id(): string
    {
        return '#the-list';
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function get_url(): Uri
    {
        return new Url\ListTable\Post($this->post_type->name);
    }

    public function get_labels(): Labels
    {
        return new Labels(
            $this->post_type->labels->singular_name ?? '',
            $this->post_type->labels->name ?? ''
        );
    }

}