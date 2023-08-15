<?php
declare(strict_types=1);

namespace AC\Admin\Colors\Shipped;

use AC\Admin\Colors\ColorRepository;
use AC\Admin\Colors\Storage\OptionFactory;

final class ColorUpdater
{

    private $shipped_color_parser;

    private $color_repository;

    private $option_factory;

    public function __construct(
        ColorParser $shipped_color_parser,
        ColorRepository $color_repository,
        OptionFactory $option_factory
    ) {
        $this->shipped_color_parser = $shipped_color_parser;
        $this->color_repository = $color_repository;
        $this->option_factory = $option_factory;
    }

    public function update(): void
    {
        global $wp_version;

        $option = $this->option_factory->create('shipped_colors_version');

        if ($option->get() === $wp_version) {
            return;
        }

        foreach ($this->shipped_color_parser->parse() as $color) {
            $this->color_repository->save($color);
        }

        $option->save($wp_version);
    }

}