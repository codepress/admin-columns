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
use AC\Value;

abstract class DateFormat extends Builder
{

    private string $source_format;

    public function __construct(string $source_format = 'U')
    {
        $this->source_format = $source_format;
    }

    abstract protected function get_date_options(): OptionCollection;

    abstract protected function get_default_option(): string;

    protected function get_label(Config $config): ?string
    {
        return __('Date Format', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return sprintf(
            __('Learn more about %s.', 'codepress-admin-columns'),
            sprintf(
                '<a target="_blank" href="%s">%s</a>',
                'https://wordpress.org/support/article/formatting-date-and-time/',
                __('date and time formatting', 'codepress-admin-columns')
            )
        );
    }

    protected function get_wp_date_format(): string
    {
        return get_option('date_format');
    }

    protected function get_input(Config $config): ?Input
    {
        return new Custom('date_format', null, [
            'wp_date_format' => $this->get_wp_date_format(),
            'wp_date_info'   => sprintf(
                __('The %s can be changed in %s.', 'codepress-admin-columns'),
                __('WordPress Date Format', 'codepress-admin-columns'),
                ac_helper()->html->link(
                    admin_url('options-general.php') . '#date_format_custom_radio',
                    strtolower(__('General Settings'))
                )
            ),
        ]);
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

    protected function get_date_formatter(string $output_format): ?Formatter
    {
        switch ($output_format) {
            case 'diff':
                return new Value\Formatter\Date\TimeDifference($this->source_format);
            case 'wp_default':
                return new Value\Formatter\Date\WpDateFormat($this->source_format);
            default:
                return new Value\Formatter\Date\DateFormat($output_format, $this->source_format);
        }
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $date_format = $this->get_date_formatter(
            (string)$config->get('date_format') ?: $this->get_default_option()
        );

        if ($date_format) {
            $formatters->add($date_format);
        }
    }

}