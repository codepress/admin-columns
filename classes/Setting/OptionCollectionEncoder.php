<?php

declare(strict_types=1);

namespace AC\Setting;

final class OptionCollectionEncoder
{

    // TODO
    public function encode(OptionCollection $options): array
    {
        $encoded = [];

        foreach ($options as $option) {
            $encoded[] = [
                'value' => $option->get_value(),
                'label' => $option->get_label(),
                'group' => $option->get_group(),
            ];
        }

        return $encoded;
    }

}