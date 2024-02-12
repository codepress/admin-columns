<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings\Column\AttachmentsFactory;

class AttachmentFactory implements ColumnFactory
{

    private $builder;

    private $attachments_factory;

    public function __construct(ComponentCollectionBuilderFactory $builder, AttachmentsFactory $attachments_factory)
    {
        $this->builder = $builder;
        $this->attachments_factory = $attachments_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->add($this->attachments_factory)
                                  ->build($config);

        return new Column(
            'column-attachment',
            __('Attachments', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings
        );
    }
}