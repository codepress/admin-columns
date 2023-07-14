<?php
declare(strict_types=1);

namespace AC\Admin\Colors\Shipped;

use AC\Admin\Colors\ColorCollection;
use AC\Admin\Colors\Colors;
use AC\Admin\Colors\Type\Color;

final class ColorParser
{

    private $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function parse(): ColorCollection
    {
        $collection = new ColorCollection();
        $contents = file_get_contents($this->file);

        if ( ! $contents) {
            return $collection;
        }

        $colors = [
            [
                'sub_pattern' => '[^.]',
                'mapping'     => [
                    Colors::SUCCESS => 'success',
                    Colors::WARNING => 'warning',
                    Colors::ERROR   => 'error',
                    Colors::INFO    => 'info',
                ],
            ],
            [
                'sub_pattern' => '\.notice-alt',
                'mapping'     => [
                    Colors::SUCCESS_ALT => 'success',
                    Colors::WARNING_ALT => 'warning',
                    Colors::ERROR_ALT   => 'error',
                    Colors::INFO_ALT    => 'info',
                ],
            ],
        ];

        $pattern_tpl = '/.notice-%s%s(.|\n)*?(#[a-f0-9]+);/m';

        foreach ($colors as $definition) {
            foreach ($definition['mapping'] as $name => $target) {
                $pattern = sprintf($pattern_tpl, $target, $definition['sub_pattern']);

                preg_match($pattern, $contents, $matches);

                $color = $matches[2] ?? null;

                if ($color) {
                    $collection->add(new Color($color, $name));
                }
            }
        }

        return $collection;
    }

}