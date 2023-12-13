<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

// TODO formatter
class LinkLabel extends Settings\Column implements Formatter
{

    use SettingTrait;

    public function __construct(Column $column, Specification $specification)
    {
        $this->name = 'link_label';
        $this->label = __('Link Label', 'codepress-admin-columns');
        $this->description = __('Leave blank to display the URL', 'codepress-admin-columns');
        $this->input = Input\Open::create_text();

        parent::__construct($column, $specification);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $url = $value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            $label = $options->get('link_label');

            if ( ! $label) {
                $label = $url;
            }

            return $value->with_value(ac_helper()->html->link($url, $label));
        }

        return $value;
    }

}