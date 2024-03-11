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

final class StringLimit implements ComponentFactory
{

    private $character_limit;

    private $word_limit;

    public function __construct(CharacterLimit $character_limit, WordLimit $word_limit)
    {
        $this->character_limit = $character_limit;
        $this->word_limit = $word_limit;
    }

    // TODO formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Text Limit', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'string_limit',
                    OptionCollection::from_array([
                        ''                => __('No limit', 'codepress-admin-columns'),
                        'character_limit' => __('Character Limit', 'codepress-admin-columns'),
                        'excerpt_length'  => __('Word Limit', 'codepress-admin-columns'),
                    ]),
                    (string)$config->get('string_limit')
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->character_limit->create(
                            $config,
                            StringComparisonSpecification::equal('character_limit')
                        ),
                        $this->word_limit->create(
                            $config,
                            StringComparisonSpecification::equal('excerpt_length')
                        ),
                    ])
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}