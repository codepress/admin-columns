<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;
use AC\Settings;

class Attachments extends Settings\Control implements AC\Setting\Children, Formatter
{

    use AC\Setting\RecursiveFormatterTrait;

    private $settings;

    private $attachment_type;

    public function __construct(
        string $attachment_type,
        ComponentCollection $settings,
        Specification $specification = null
    ) {
        parent::__construct(
            OptionFactory::create_select(
                'attachment_display',
                OptionCollection::from_array([
                    'thumbnail' => __('Thumbnails', 'codepress-admin-columns'),
                    'count'     => __('Count', 'codepress-admin-columns'),
                ]),
                $attachment_type
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $specification
        );

        $this->settings = $settings;
        $this->attachment_type = $attachment_type;
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_iterator(): ComponentCollection
    {
        return $this->settings;
    }

    private function get_values(int $id): ValueCollection
    {
        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $id,
            'fields'         => 'ids',
        ]);

        return ValueCollection::from_ids($attachment_ids);
    }

    public function format(Value $value): Value
    {
        $values = $this->get_values((int)$value->get_id());

        switch ($this->attachment_type) {
            case 'count':
                return $value->with_value(
                    $values->count()
                );
            case 'thumbnail':
            default:
                $items = [];

                foreach ($values as $_value) {
                    $items[] = $this->get_recursive_formatter($this->attachment_type)->format($_value);
                }

                return $value->with_value(
                    implode($items)
                );
        }
    }

}