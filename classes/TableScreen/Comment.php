<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC;
use AC\ListTableFactory;
use AC\TableScreen;
use AC\Type\Labels;
use AC\Type\TableId;
use AC\Type\Url;

class Comment extends TableScreen implements ListTable, MetaType, TotalItems
{

    use AC\ListTable\TotalItemsTrait;

    public function __construct()
    {
        parent::__construct(
            new TableId('wp-comments'),
            'edit-comments',
            new Labels(
                __('Comment'),
                __('Comments')
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
        return AC\MetaType::create_comment_meta();
    }

}