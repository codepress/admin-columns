<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListScreen;
use AC\MetaType;
use AC\PostType;
use AC\Table;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use AC\WpListTableFactory;

class Media extends TableScreen implements ListTable, PostType, TableScreen\MetaType
{

    public function __construct()
    {
        parent::__construct(new ListKey('wp-media'), 'upload');
    }

    public function manage_value(ListScreen $list_screen): AC\Table\ManageValue
    {
        return new Table\ManageValue\Media($list_screen);
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ListTable\Media((new WpListTableFactory())->create_media_table($this->screen_id));
    }

    public function get_query_type(): string
    {
        return 'post';
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

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

}