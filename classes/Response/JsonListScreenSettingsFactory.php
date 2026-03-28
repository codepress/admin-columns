<?php

declare(strict_types=1);

namespace AC\Response;

use AC;
use AC\Admin\Banner\BannerContextResolver;
use AC\ListScreen;
use AC\Setting\Encoder;
use AC\Storage\EncoderFactory;
use AC\Type\StartingPrice;
use AC\Type\Url\Preview;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class JsonListScreenSettingsFactory
{

    private EncoderFactory $encoder_factory;

    private AC\ColumnTypeRepository $type_repository;

    private AC\ColumnGroups $column_groups;

    private BannerContextResolver $banner_context_resolver;

    public function __construct(
        EncoderFactory $encoder_factory,
        AC\ColumnTypeRepository $type_repository,
        AC\ColumnGroups $column_groups,
        BannerContextResolver $banner_context_resolver
    ) {
        $this->encoder_factory = $encoder_factory;
        $this->type_repository = $type_repository;
        $this->column_groups = $column_groups;
        $this->banner_context_resolver = $banner_context_resolver;
    }

    public function create(ListScreen $list_screen, bool $is_stored = true, bool $is_template = false): Json
    {
        $encoder = $this->encoder_factory
            ->create()
            ->set_list_screen($list_screen);

        $table_screen = $list_screen->get_table_screen();

        $context = $this->banner_context_resolver->resolve($table_screen);

        return (new Json())->set_parameters([
            'read_only'       => $list_screen->is_read_only(),
            'table_url'       => $is_template
                ? (string)new Preview($list_screen->get_table_url(), 'columns')
                : (string)$list_screen->get_table_url(),
            'settings'        => $encoder->encode(),
            'column_types'    => $this->get_column_types($table_screen),
            'column_settings' => $this->encode_column_settings($list_screen->get_columns()),
            'is_stored'       => $is_stored,
            'is_template'     => $is_template,
            'labels'          => [
                'singular' => $table_screen->get_labels()->get_singular(),
                'plural'   => $table_screen->get_labels()->get_plural(),
            ],
            'pro_banner'      => $context
                ? $context->get_arguments($table_screen)
                : $this->get_default_banner_arguments($table_screen),
        ]);
    }

    private function get_column_types(AC\TableScreen $table_screen): array
    {
        $column_types = [];

        $original_types = $this->get_original_types($table_screen);

        foreach ($this->type_repository->find_all($table_screen) as $column) {
            $group = $this->column_groups->find($column->get_group());
            $label = $this->get_clean_label($column);

            $column_type = [
                'label'            => $label,
                'value'            => $column->get_type(),
                'group'            => $group ? $group->get_label() : __('Default', 'codepress-admin-columns'),
                'group_key'        => $column->get_group(),
                'original'         => in_array($column->get_type(), $original_types, true),
                'searchable_label' => $label,
            ];

            if ('custom' !== $column->get_group()) {
                $column_type['searchable_label'] .= ' ' . $column_type['group'];
            }

            $description = $column->get_description();
            if ($description !== null) {
                $column_type['description'] = $description;
            }

            $column_types[] = $column_type;
        }

        usort($column_types, function ($a, $b) {
            // ignore original columns
            if ($a['original'] || $b['original']) {
                return 0;
            }

            return strcasecmp($a['label'], $b['label']);
        });

        return $column_types;
    }

    private function get_original_types(AC\TableScreen $table_screen): array
    {
        $types = [];
        foreach ($this->type_repository->find_all_by_original($table_screen) as $column) {
            $types[] = $column->get_type();
        }

        return $types;
    }

    private function get_clean_label(AC\Column $column): string
    {
        $label = $column->get_label();

        if (strip_tags($label) === '') {
            $label = ucfirst(str_replace('_', ' ', $column->get_type()));
        }

        return strip_tags($label);
    }

    private function encode_column_settings(AC\ColumnIterator $columns): array
    {
        $settings = [];

        foreach ($columns as $column) {
            $settings[(string)$column->get_id()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

    private function get_default_banner_arguments(AC\TableScreen $table_screen): array
    {
        $upgrade_url = new UtmTags(Site::create_admin_columns_pro(), 'banner');

        $plural = $table_screen->get_labels()->get_plural();
        $singular = $table_screen->get_labels()->get_singular();

        if (mb_strlen($plural) > 30) {
            $plural = __('content', 'codepress-admin-columns');
            $singular = __('item', 'codepress-admin-columns');
        }

        $plural_lower = mb_strtolower($plural);
        $singular_lower = mb_strtolower($singular);

        return [
            'title'             => sprintf(
                __('Manage your %s faster', 'codepress-admin-columns'),
                $plural_lower
            ),
            'description'       => sprintf(
                __('Turn your %1$s overview into a workspace for sorting, editing, filtering, and exporting - without opening a single %2$s.', 'codepress-admin-columns'),
                $plural_lower,
                $singular_lower
            ),
            'upgrade_cta'       => sprintf(__('Manage your %s faster', 'codepress-admin-columns'), $plural_lower),
            'upgrade_cta_price' => sprintf(
                '%s · %s',
                sprintf(
                /* translators: %s: price (e.g. $79) */
                    __('from %s/year', 'codepress-admin-columns'),
                    StartingPrice::get()
                ),
                __('all features included', 'codepress-admin-columns')
            ),
            'features'          => [
                [
                    'url'   => $upgrade_url->with_content('usp-editing')->get_url(),
                    'label' => __('Inline edit directly in the table', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-sorting')->get_url(),
                    'label' => __('Sort and filter on any column', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-bulk-edit')->get_url(),
                    'label' => sprintf(
                        __('Bulk edit hundreds of %s at once', 'codepress-admin-columns'),
                        $plural_lower
                    ),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-export')->get_url(),
                    'label' => __('Export table data to CSV', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-column-sets')->get_url(),
                    'label' => __('Multiple views per screen', 'codepress-admin-columns'),
                ],
            ],
            'promo_url'         => $upgrade_url->get_url(),
        ];
    }

}