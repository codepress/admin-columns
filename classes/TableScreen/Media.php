<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ColumnRepository;
use AC\MetaType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Media extends TableScreen implements AC\ListScreen\ListTable, AC\PostType
{

    public function __construct(ListKey $key)
    {
        parent::__construct($key, 'upload', false);
    }

    public function manage_value(ColumnRepository $column_repository): AC\Table\ManageValue
    {
        return new Table\ManageValue\Media($column_repository);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Media((new WpListTableFactory())->create_media_table($this->screen_id));
    }

    public function get_group(): string
    {
        return 'media';
    }

    public function get_query_type(): string
    {
        return MetaType::POST;
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_post_type(): string
    {
        return 'attachment';
    }

    public function get_attr_id(): string
    {
        return '#the-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable\Media();
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Media'),
            __('Media')
        );
    }

}