<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Url;

class Comment extends TableScreen implements ListTable, MetaType
{

    public function __construct()
    {
        parent::__construct(
            new ListKey('wp-comments'),
            'edit-comments',
            new Labels(
                __('Comments'),
                __('Comment')
            ),
            new Url\ListTable('edit-comments.php'),
            '#the-comment-list'
        );
    }

    public function list_table(): AC\ListTable
    {
        return ListTableFactory::create_comment($this->screen_id);
    }

    public function get_meta_type(): AC\MetaType
    {
        return AC\MetaType::create_comment_type();
    }

}