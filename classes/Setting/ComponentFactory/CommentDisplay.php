<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

final class CommentDisplay extends Builder
{

    public const PROPERTY_COMMENT = 'comment';
    public const PROPERTY_DATE = 'date';
    public const PROPERTY_ID = 'id';
    public const PROPERTY_FULL = 'full';
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

    protected function get_label(Config $config): ?string
    {
        return __('Comment Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'comment',
            OptionCollection::from_array([
                self::PROPERTY_FULL         => __('Full comment', 'codepress-admin-column'),
                self::PROPERTY_COMMENT      => __('Comment'),
                self::PROPERTY_ID           => __('ID'),
                self::PROPERTY_AUTHOR       => __('Author'),
                self::PROPERTY_AUTHOR_EMAIL => __('Author Email', 'codepress-admin-column'),
                self::PROPERTY_DATE         => __('Date'),
            ]),
            $config->get('comment', self::PROPERTY_COMMENT)
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
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
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        switch ($config->get('comment')) {
            case self::PROPERTY_DATE:
                $formatters->add(new Formatter\Comment\Property('comment_date'));
                break;
            case self::PROPERTY_AUTHOR:
                $formatters->add(new Formatter\Comment\Property('comment_author'));
                break;
            case self::PROPERTY_AUTHOR_EMAIL:
                $formatters->add(new Formatter\Comment\Property('comment_author_email'));
                break;
            case self::PROPERTY_COMMENT:
                $formatters->add(new Formatter\Comment\Property('comment_content'));
                break;
            case self::PROPERTY_FULL:
                $formatters->add(new Formatter\Comment\FullComment());
                break;
            case self::PROPERTY_ID:
            default:
                $formatters->add(new Formatter\NullFormatter());
        }

        return parent::get_formatters($config, $formatters);
    }

}