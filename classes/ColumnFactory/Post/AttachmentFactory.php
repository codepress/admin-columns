<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\Builder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings\Column\AttachmentsFactory;

class AttachmentFactory implements ColumnFactory
{

    public function can_create(string $type): bool
    {
        return 'column-attachment' === $type;
    }

    public function create(Config $config): Column
    {
        $settings = (new Builder())->set_defaults()
                                   ->set(new AttachmentsFactory())
                                   ->build($config);

        return new Column(
            'column-attachment',
            __('Attachments', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings
        );
    }
}