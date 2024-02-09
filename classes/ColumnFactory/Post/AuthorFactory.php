<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\Author;
use AC\Setting\SettingCollection;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\UserFactory;
use AC\Settings\Column\WidthFactory;

class AuthorFactory implements ColumnFactory
{

    private $name_factory;

    private $label_factory;

    private $width_factory;

    private $user_factory;

    private $before_after_factory;

    public function __construct(
        NameFactory $name_factory,
        LabelFactory $label_factory,
        WidthFactory $width_factory,
        UserFactory $user_factory,
        BeforeAfterFactory $before_after_factory
    ) {
        $this->name_factory = $name_factory;
        $this->label_factory = $label_factory;
        $this->width_factory = $width_factory;
        $this->user_factory = $user_factory;
        $this->before_after_factory = $before_after_factory;
    }

    public function create(Config $config): Column
    {
        $settings = new SettingCollection([
            $this->name_factory->create($config),
            $this->label_factory->create($config),
            $this->width_factory->create($config),
            $this->user_factory->create($config),
            $this->before_after_factory->create($config),
        ]);

        //        $settings = new SettingCollection([
        //            $this->container->get(NameFactory::class)->create($config),
        //            $this->container->get(LabelFactory::class)->create($config),
        //            $this->container->get(WidthFactory::class)->create($config),
        //            $this->container->get(UserFactory::class)->create($config),
        //            $this->container->get(BeforeAfterFactory::class)->create($config),
        //        ]);

        //        $settings = (new DefaultsFactory())->create($config);
        //
        //        $settings->add(new UserFactory(new UserLinkFactory()));
        //
        //        $settings = (new Builder())->set_defaults()
        //                                   ->set_user()
        //                                   ->set_before_after()
        //                                   ->build($config);

        return new Column(
            'column-author_name',
            __('Author', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Author()),
            $settings
        );
    }

}