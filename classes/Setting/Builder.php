<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\CharacterLimitFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\StringLimitFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\Column\WordLimitFactory;
use AC\Settings\SettingFactory;

class Builder
{

    private $factories = [];

    public function set_defaults(): self
    {
        $this->set(new NameFactory());
        $this->set(new LabelFactory());
        $this->set(new WidthFactory());

        return $this;
    }

    public function set_before_after(): self
    {
        $this->set(new BeforeAfterFactory());

        return $this;
    }

    public function set_string_limit(Specification $specification = null): self
    {
        $this->set(
            new StringLimitFactory(
                new CharacterLimitFactory(),
                new WordLimitFactory()
            ),
            $specification
        );

        return $this;
    }

    public function set(SettingFactory $factory, Specification $specification = null): self
    {
        $this->factories[] = [
            'factory'       => $factory,
            'specification' => $specification,
        ];

        return $this;
    }

    public function build(Config $config): SettingCollection
    {
        $collection = new SettingCollection();

        foreach ($this->factories as $factory) {
            $collection->add(
                $factory['factory']->create($config, $factory['specification'])
            );
        }

        return $collection;
    }

}