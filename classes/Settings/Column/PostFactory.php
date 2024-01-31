<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class PostFactory implements SettingFactory
{

    private $character_limit_factory;

    private $image_factory;

    /**
     * @var UserFactory
     */
    private $user_factory;

    public function __construct(
        CharacterLimitFactory $character_limit_factory,
        ImageFactory $image_factory,
        UserFactory $user_factory
    ) {
        $this->character_limit_factory = $character_limit_factory;
        $this->image_factory = $image_factory;
        $this->user_factory = $user_factory;
    }

    public function create(Config $config, Specification $specification = null): Column
    {
        return new Post(
            $config->has('post') ? (string)$config->get('post') : 'title',
            new SettingCollection([
                $this->character_limit_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_TITLE)
                ),
                $this->image_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_FEATURED_IMAGE)
                ),
                $this->user_factory->create(
                    $config,
                    StringComparisonSpecification::equal(Post::PROPERTY_AUTHOR)
                ),

                // TODO Title formatter
                //                new User(StringComparisonSpecification::equal(self::PROPERTY_AUTHOR)),
                //                new Date(StringComparisonSpecification::equal(self::PROPERTY_DATE)),
                //                new CharacterLimit(StringComparisonSpecification::equal(self::PROPERTY_TITLE)),
                //                new StatusIcon(StringComparisonSpecification::equal(self::PROPERTY_STATUS)),
            ]),
            $specification
        );
    }

}