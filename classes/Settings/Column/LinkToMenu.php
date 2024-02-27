<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class LinkToMenu extends Settings\Control implements Formatter
{

    private $linked;

    public function __construct(bool $is_linked, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_toggle('link_to_menu', null, $is_linked ? 'on' : 'off'),
            __('Link to menu', 'codepress-admin-columns'),
            __('This will make the title link to the menu.', 'codepress-admin-columns'),
            $conditions
        );

        $this->linked = $is_linked;
    }

    public function format(Value $value): Value
    {
        if ( ! $this->linked) {
            return $value;
        }

        return $value->with_value(
            ac_helper()->html->link(
                add_query_arg(['menu' => $value->get_id()], admin_url('nav-menus.php')),
                $value->get_value()
            )
        );
    }
}