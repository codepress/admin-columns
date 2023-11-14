<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ColumnRepository;
use AC\ListScreen\ListTable;
use AC\MetaType;
use AC\PostType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Post extends TableScreen implements PostType, ListTable
{

    private $post_type;

    public function __construct(string $post_type, ListKey $key, string $screen_id)
    {
        parent::__construct($key, $screen_id, false);

        $this->post_type = $post_type;
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Post((new WpListTableFactory())->create_post_table($this->screen_id));
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Post($this->post_type, $column_repository);
    }

    public function get_post_type(): string
    {
        return $this->post_type;
    }

    public function get_group(): string
    {
        return 'post';
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

    public function get_url(): Uri
    {
        return new Url\ListTable\Post($this->post_type);
    }

    public function get_labels(): Labels
    {
        $post_type = get_post_type_object($this->post_type);

        return new Labels(
            $post_type->labels->name ?? '',
            $post_type->labels->singular_name ?? ''
        );
    }

}