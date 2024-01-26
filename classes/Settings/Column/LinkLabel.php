<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component;
use AC\Setting\Formatter;
<<<<<<< HEAD
use AC\Setting\SettingTrait;
=======
use AC\Setting\Input;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\Type\Value;
use AC\Settings;

// TODO formatter
class LinkLabel extends Settings\Column implements Formatter
{

    public function __construct(Specification $specification = null)
    {
<<<<<<< HEAD
        $this->name = 'link_label';
        $this->label = __('Link Label', 'codepress-admin-columns');
        $this->description = __('Leave blank to display the URL', 'codepress-admin-columns');
        $this->input = Component\OpenFactory::create_text();

        parent::__construct($column, $specification);
=======
        parent::__construct(
            'link_label',
            __('Link Label', 'codepress-admin-columns'),
            __('Leave blank to display the URL', 'codepress-admin-columns'),
            Input\Open::create_text(),
            $specification
        );
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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