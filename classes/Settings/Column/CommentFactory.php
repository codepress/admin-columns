<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class CommentFactory implements SettingFactory
{

    private $date_factory;

    private $string_limit_factory;

    private $comment_link_factory;

    private $user_factory;

    public function __construct(
        DateFactory $date_factory,
        StringLimitFactory $string_limit_factory,
        CommentLinkFactory $comment_link_factory,
        UserFactory $user_factory
    ) {
        $this->date_factory = $date_factory;
        $this->string_limit_factory = $string_limit_factory;
        $this->comment_link_factory = $comment_link_factory;
        $this->user_factory = $user_factory;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Comment(
            (string)$config->get('comment') ?: Comment::PROPERTY_COMMENT,
            new ComponentCollection([
                $this->date_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Comment::PROPERTY_DATE)
                ),
                $this->string_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Comment::PROPERTY_COMMENT)
                ),
                $this->user_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Comment::PROPERTY_AUTHOR)
                ),
                $this->comment_link_factory->create($config),
            ]),
            $specification
        );
    }

}