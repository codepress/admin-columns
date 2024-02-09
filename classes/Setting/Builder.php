<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Settings\Column\BeforeAfterFactory;
use AC\Settings\Column\CharacterLimitFactory;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\StringLimitFactory;
use AC\Settings\Column\UserFactory;
use AC\Settings\Column\UserLinkFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\Column\WordLimitFactory;
use AC\Settings\SettingFactory;

class Builder
{

    private $factories = [];

    // TODO return :static not :self (since PHP 8)
    public function set_defaults(): self
    {
        return $this->set(new NameFactory())
                    ->set(new LabelFactory())
                    ->set(new WidthFactory());
    }

    public function set_before_after(Specification $specification = null): self
    {
        return $this->set(new BeforeAfterFactory(), $specification);
    }

    public function set_user(Specification $specification = null): self
    {
        return $this->set(new UserFactory(new UserLinkFactory()), $specification);
    }

    public function set_string_limit(Specification $specification = null): self
    {
        return $this->set(
            new StringLimitFactory(
                new CharacterLimitFactory(),
                new WordLimitFactory()
            ),
            $specification
        );
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