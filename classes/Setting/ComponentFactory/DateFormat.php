<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

// TODO formatter
// TODO do we still want the extra description/tooltips as in the old version?
abstract class DateFormat extends Builder
{

    abstract protected function get_date_options(): OptionCollection;

    abstract protected function get_default_option(): string;

    protected function get_label(Config $config): ?string
    {
        return __('Date Format', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This will determine how the date will be displayed.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return new Custom('date_format');
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(new ComponentCollection([
            new Component(
                null,
                null,
                OptionFactory::create_radio(
                    'date_format',
                    $this->get_date_options(),
                    (string)$config->get('date_format') ?: $this->get_default_option()
                )
            ),
        ]));
    }

    protected function get_date_formatter(string $format): ?Formatter
    {
        switch ($format) {
            case 'diff':
                return new Formatter\Date\TimeDifference();

            case 'wp_default':
                return new Formatter\Date\WpDateFormat();

            default:
                return new Formatter\Date\DateFormat($format);
        }
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $format = (string)$config->get('date_format');
        $formatters->add(new Formatter\TimeStamp());

        $date_format = $this->get_date_formatter($format);

        if ($date_format) {
            $formatters->add($date_format);
        }

        return parent::get_formatters($config, $formatters);
    }

}