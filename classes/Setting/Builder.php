<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Settings\Column\BeforeAfter;
use AC\Settings\Column\CharacterLimitFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\StringLimitFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\Column\WordLimitFactory;
use AC\Settings\SettingFactory;

class Builder
{

    /**
     * @var SettingFactory[]
     */
    private $factories = [];

    public function set_defaults(): self
    {
        $this->factories[] = new NameFactory();
        $this->factories[] = new LabelFactory();
        $this->factories[] = new WidthFactory();

        return $this;
    }

    public function set_before_after(): self
    {
        $this->factories[] = new BeforeAfter();

        return $this;
    }

    public function set_string_limit(): self
    {
        $this->factories[] = new StringLimitFactory(
            new CharacterLimitFactory(),
            new WordLimitFactory()
        );

        return $this;
    }

    public function set(SettingFactory $factory): self
    {
        $this->factories[] = $factory;

        return $this;
    }

    public function build(Config $config): SettingCollection
    {
        $collection = new SettingCollection();

        foreach ($this->factories as $factory) {
            $collection->add($factory->create($config));
        }

        return $collection;
    }

}