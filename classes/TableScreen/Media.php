<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\MetaType;
use AC\PostType;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\TableId;
use AC\Type\PostTypeSlug;
use AC\Type\Url;

class Media extends TableScreen implements ListTable, PostType, TableScreen\MetaType
{

    public function __construct()
    {
        parent::__construct(
            new TableId('wp-media'),
            'upload',
            new Labels(
                __('Media'),
                __('Media')
            ),
            new Url\ListTable\Media()
        );
    }

    public function list_table(): AC\ListTable
    {
        return ListTableFactory::create_media($this->screen_id);
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_post_type(): PostTypeSlug
    {
        return new PostTypeSlug('attachment');
    }

}