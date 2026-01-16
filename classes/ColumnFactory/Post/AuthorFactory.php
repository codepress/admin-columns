<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\UserLinkFactory;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter;

class AuthorFactory extends BaseColumnFactory
{

    private UserProperty $user_factory;

    private BeforeAfter $before_after_factory;

    private UserLinkFactory $user_link;

    private PostTypeSlug $post_type;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        UserProperty $user_factory,
        UserLinkFactory $user_link,
        BeforeAfter $before_after_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($default_settings_builder);

        $this->user_factory = $user_factory;
        $this->before_after_factory = $before_after_factory;
        $this->user_link = $user_link;
        $this->post_type = $post_type;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->user_factory->create($config),
            $this->user_link->create($this->post_type)->create($config),
            $this->before_after_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)->prepend(new \AC\Formatter\Post\Author());
    }

    public function get_column_type(): string
    {
        return 'column-author_name';
    }

    public function get_label(): string
    {
        return __('Author', 'codepress-admin-columns');
    }

}
