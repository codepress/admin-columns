<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

final class CommentDisplay implements ComponentFactory
{

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_AUTHOR = 'author';
    public const PROPERTY_AUTHOR_EMAIL = 'author_email';

    private $string_limit;

    private $comment_link;

    private $user_display;

    public function __construct(
        StringLimit $string_limit,
        CommentLink $comment_link,
        UserProperty $user_display
    ) {
        $this->string_limit = $string_limit;
        $this->comment_link = $comment_link;
        $this->user_display = $user_display;
    }

    // Todo implement formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Comment Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'comment',
                    OptionCollection::from_array([
                        self::PROPERTY_COMMENT      => __('Comment'),
                        self::PROPERTY_ID           => __('ID'),
                        self::PROPERTY_AUTHOR       => __('Author'),
                        self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                        self::PROPERTY_DATE         => __('Date'),
                    ]),
                    (string)$config->get('comment') ?: self::PROPERTY_COMMENT
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->string_limit->create(
                            $config,
                            StringComparisonSpecification::equal(self::PROPERTY_COMMENT)
                        ),
                        $this->comment_link->create(
                            $config,
                            StringComparisonSpecification::equal(self::PROPERTY_COMMENT)
                        ),
                        $this->user_display->create(
                            $config,
                            StringComparisonSpecification::equal(self::PROPERTY_AUTHOR)
                        ),
                    ])
                )
            )
            // TODO use the child formatters as well (Aggregate)
            ->set_formatter(
                $this->create_formatter($config)
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function create_formatter(Config $config): Formatter
    {
        switch ($config->get('comment')) {
            case self::PROPERTY_DATE:
                return new Formatter\Comment\Property('comment_date');
            case self::PROPERTY_AUTHOR:
                return new Formatter\Comment\Property('comment_author');
            case self::PROPERTY_AUTHOR_EMAIL:
                return new Formatter\Comment\Property('comment_author_email');
            case self::PROPERTY_COMMENT:
                return new Formatter\Comment\Property('comment_content');
            case self::PROPERTY_ID:
            default:
                return new Formatter\NullFormatter();
        }
    }

}