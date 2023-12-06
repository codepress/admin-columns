<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Option;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\StringComparisonSpecification;

class AttachmentDisplay extends Settings\Column\Recursive implements AC\Setting\Formatter
{

    use AC\Setting\SettingTrait;

    public function __construct(AC\Column $column)
    {
        $this->name = 'attachment_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            new OptionCollection([
                new Option(__('Thumbnails', 'codepress-admin-columns'), 'thumbnail'),
                new Option(__('Count', 'codepress-admin-columns'), 'count'),
            ])
            ,
            'thumbnail'
        );

        parent::__construct($column);
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Images($this->column, StringComparisonSpecification::equal('thumbnail')),
        ]);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        switch ($options->get($this->name)) {
            case 'count':
                $value = $value->with_value(count($value->get_value()));
        }

        return parent::format($value, $options);
    }

    //	private $attachment_display;
    //
    //	protected function define_options() {
    //		return [
    //			'attachment_display' => 'thumbnail',
    //		];
    //	}
    //
    //	public function get_dependent_settings() {
    //		$settings = [];
    //
    //		switch ( $this->get_attachment_display() ) {
    //			case 'thumbnail' :
    //				$settings[] = new Settings\Column\Images( $this->column );
    //
    //				break;
    //		}
    //
    //		return $settings;
    //	}

}