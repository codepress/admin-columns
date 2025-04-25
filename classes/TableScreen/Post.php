<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\MetaType;
use AC\PostType;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\PostTypeSlug;
use AC\Type\TableId;
use AC\Type\Url;
use WP_Post_Type;

class Post extends TableScreen implements PostType, ListTable, TableScreen\MetaType
{

    protected WP_Post_Type $post_type;

    public function __construct(WP_Post_Type $post_type)
    {
        parent::__construct(
            new TableId($post_type->name),
            'edit-' . $post_type->name,
            new Labels(
                $post_type->labels->singular_name ?? $post_type->name,
                $post_type->labels->name ?? $post_type->name
            ),
            new Url\ListTable\Post($post_type->name)
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

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

}