<?php

declare(strict_types=1);

namespace AC\Admin\Banner;

use AC\TableScreen;

class BannerContextResolver
{

    /** @var BannerContext[] */
    private array $contexts;

    /**
     * @param BannerContext[] $contexts
     */
    public function __construct(array $contexts = [])
    {
        $this->contexts = $contexts;
    }

    public function resolve(TableScreen $table_screen): ?BannerContext
    {
        $active = [];

        foreach ($this->contexts as $context) {
            if ($context->is_active($table_screen)) {
                $active[] = $context;
            }
        }

        if (empty($active)) {
            return null;
        }

        usort($active, static function (BannerContext $a, BannerContext $b): int {
            return $a->get_priority() - $b->get_priority();
        });

        return $active[0];
    }

}
