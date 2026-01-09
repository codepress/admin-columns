<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class DateSaveFormat extends BaseComponentFactory
{

    public const FORMAT_UNIX_TIMESTAMP = 'U';
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';
    public const FORMAT_DATE = 'Y-m-d';

    private ?string $default_save_date;

    public function __construct(?string $default_save_date = self::FORMAT_DATE)
    {
        $this->default_save_date = $default_save_date;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Date Save Format', 'codepress-admin-columns');
    }

    public function with_default(string $default_save_date): self
    {
        $clone = clone $this;
        $clone->default_save_date = $default_save_date;

        return $clone;
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'date_save_format',
            OptionCollection::from_array([
                self::FORMAT_DATE           => sprintf(
                    '%s (%s)',
                    __('Date', 'codepress-admin-columns'),
                    'Y-m-d'
                ),
                self::FORMAT_DATETIME       => sprintf(
                    '%s (%s)',
                    __('Datetime', 'codepress-admin-columns'),
                    'Y-m-d H:i:s'
                ),
                self::FORMAT_UNIX_TIMESTAMP => __('Timestamp', 'codepress-admin-columns'),
            ]),
            $config->get('date_save_format', $this->default_save_date)
        );
    }

}