<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\ColumnMatcher;
use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\TableId;
use AC\Type\Uri;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings implements Registerable
{

    private const PRO_ONLY_ACF_TYPES = [
        'flexible_content',
        'gallery',
        'google_map',
        'group',
        'link',
        'relationship',
        'repeater',
        'taxonomy',
    ];

    private const SUPPORTED_ACF_TYPES = [
        'button_group',
        'checkbox',
        'color_picker',
        'date_picker',
        'date_time_picker',
        'email',
        'file',
        'image',
        'number',
        'oembed',
        'page_link',
        'password',
        'post_object',
        'radio',
        'range',
        'select',
        'text',
        'textarea',
        'time_picker',
        'true_false',
        'url',
        'user',
        'wysiwyg',
    ];

    private Storage $storage;

    private TableScreenResolver $table_screen_resolver;

    private ColumnMatcher $column_matcher;

    public function __construct(
        Storage $storage,
        TableScreenResolver $table_screen_resolver,
        ColumnMatcher $column_matcher
    ) {
        $this->storage = $storage;
        $this->table_screen_resolver = $table_screen_resolver;
        $this->column_matcher = $column_matcher;
    }

    public function register(): void
    {
        if ( ! class_exists('acf', false) && ! class_exists('ACF', false)) {
            return;
        }

        add_filter('acf/field_group/additional_field_settings_tabs', [$this, 'add_tab']);
        add_action('acf/field_group/render_field_settings_tab/admin_columns', [$this, 'render_tab']);
    }

    public function add_tab(array $tabs): array
    {
        $tabs['admin_columns'] = __('Admin Columns', 'codepress-admin-columns');

        return $tabs;
    }

    public function render_tab(array $field): void
    {
        if ($this->is_pro_only_field($field)) {
            $this->render_pro_only_upsell($field);

            return;
        }

        if ( ! $this->is_field_supported($field)) {
            acf_render_field_setting(
                $field,
                [
                    'label'   => '',
                    'type'    => 'message',
                    'name'    => 'ac_unsupported',
                    'message' => '<p style="color:#50575e;">'
                                 . esc_html__('This field type cannot be used as an admin column.', 'codepress-admin-columns')
                                 . '</p>',
                ]
            );

            return;
        }

        $this->render_table_screen_rows($field);
    }

    private function render_pro_only_upsell(array $field): void
    {
        $url = (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings-upsell'))->get_url();

        acf_render_field_setting(
            $field,
            [
                'label'   => '',
                'type'    => 'message',
                'name'    => 'ac_pro_upsell',
                'message' => $this->get_upsell_html(
                    __('This field type is supported in Admin Columns Pro', 'codepress-admin-columns'),
                    __('Add this field as a column with sorting, filtering, and inline editing.', 'codepress-admin-columns'),
                    $url,
                    __('Learn more →', 'codepress-admin-columns')
                ),
            ]
        );
    }

    private function render_table_screen_rows(array $field): void
    {
        $table_screens = $this->resolve_table_screens($field);

        if ( ! $table_screens) {
            return;
        }

        $meta_key = $field['name'] ?? '';
        $has_meta_key = $meta_key !== '' && $meta_key !== 'new_field';
        $nonce = wp_create_nonce('ac-ajax');

        $rows_html = '';

        foreach ($table_screens as $table_screen) {
            $table_id = (string)$table_screen->get_id();
            $label = $table_screen->get_labels()->get_plural();

            if ( ! $has_meta_key) {
                $rows_html .= $this->render_unavailable_row($label);

                continue;
            }

            $match = $this->find_column_in_list_screens($table_screen->get_id(), $meta_key);

            if ($match) {
                $url = EditorUrlFactory::create($table_screen->get_id(), false, $match['list_screen']->get_id())
                    ->with_arg('open_columns', (string)$match['column']->get_id());

                $rows_html .= $this->render_added_row($label, $url);
            } else {
                $rows_html .= $this->render_available_row($table_id, $label);
            }
        }

        $intro = '<p class="ac-acf-intro">'
                 . esc_html__('Add this field as a column in the list tables below. Once added, you can open that specific column in Admin Columns to configure it.', 'codepress-admin-columns')
                 . '</p>';

        if ( ! $has_meta_key) {
            $intro .= '<div class="ac-acf-notice">'
                      . sprintf(
                          esc_html__('Enter a %1$s under the %2$s tab to enable adding this field as a column.', 'codepress-admin-columns'),
                          '<strong>' . esc_html__('Field Name', 'codepress-admin-columns') . '</strong>',
                          '<strong>' . esc_html__('General', 'codepress-admin-columns') . '</strong>'
                      )
                      . '</div>';
        }

        $helper = '<p class="ac-acf-helper">'
                  . esc_html__('Column settings are managed in Admin Columns after creation.', 'codepress-admin-columns')
                  . '</p>';

        acf_render_field_setting(
            $field,
            [
                'label'   => __('Admin Columns', 'codepress-admin-columns'),
                'type'    => 'message',
                'name'    => 'ac_field_settings',
                'message' => $intro . '<div class="ac-acf-list">' . $rows_html . '</div>' . $helper,
            ]
        );

        $this->render_upsell($field, $table_screens);
        $this->render_inline_style();
        $this->render_inline_script($nonce);
    }

    private function render_added_row(string $label, Uri $editor_url): string
    {
        return sprintf(
            '<div class="ac-acf-card ac-acf-card--added" data-state="added">'
            . '<div class="ac-acf-card-main">'
            . '<span class="ac-acf-card-name">%s</span>'
            . '<span class="ac-acf-status"><span class="ac-acf-badge">&#10003;</span><span class="ac-acf-card-status">%s</span></span>'
            . '</div>'
            . '<div class="ac-acf-card-actions">'
            . '<a href="%s" target="_blank" class="ac-acf-link">%s</a>'
            . '</div>'
            . '</div>',
            esc_html($label),
            esc_html__('Column added', 'codepress-admin-columns'),
            esc_url((string)$editor_url),
            esc_html__('Edit in Admin Columns →', 'codepress-admin-columns')
        );
    }

    private function render_available_row(string $table_id, string $label): string
    {
        return sprintf(
            '<div class="ac-acf-card" data-state="available" data-table-id="%s">'
            . '<div class="ac-acf-card-main">'
            . '<span class="ac-acf-card-name">%s</span>'
            . '</div>'
            . '<div class="ac-acf-card-actions">'
            . '<button type="button" class="button ac-acf-add-column">%s</button>'
            . '</div>'
            . '</div>',
            esc_attr($table_id),
            esc_html($label),
            esc_html__('Add column', 'codepress-admin-columns')
        );
    }

    private function render_unavailable_row(string $label): string
    {
        return sprintf(
            '<div class="ac-acf-card ac-acf-card--unavailable">'
            . '<div class="ac-acf-card-main">'
            . '<span class="ac-acf-card-name">%s</span>'
            . '</div>'
            . '<div class="ac-acf-card-actions">'
            . '<button type="button" class="button" disabled>%s</button>'
            . '</div>'
            . '</div>',
            esc_html($label),
            esc_html__('Add column', 'codepress-admin-columns')
        );
    }

    /**
     * @param TableScreen[] $table_screens
     */
    private function render_upsell(array $field, array $table_screens): void
    {
        $labels = [];

        foreach ($table_screens as $table_screen) {
            $labels[] = strtolower($table_screen->get_labels()->get_plural());
        }

        $labels = array_slice($labels, 0, 3);

        if ($labels) {
            $last = array_pop($labels);
            $content_label = $labels
                ? implode(', ', $labels) . ' & ' . $last
                : $last;

            $description = sprintf(
                __(
                    'Make this column editable, sortable, and filterable – and manage your %s faster right from the overview.',
                    'codepress-admin-columns'
                ),
                $content_label
            );
        } else {
            $description = __(
                'Make this column editable, sortable, and filterable – and manage your content faster right from the overview.',
                'codepress-admin-columns'
            );
        }

        $url = (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings'))->get_url();

        acf_render_field_setting(
            $field,
            [
                'label'   => '',
                'type'    => 'message',
                'name'    => 'ac_upsell',
                'message' => $this->get_upsell_html(
                    __('Unlock powerful column features', 'codepress-admin-columns'),
                    $description,
                    $url,
                    __('Get Admin Columns Pro →', 'codepress-admin-columns')
                ),
            ]
        );
    }

    private function get_upsell_html(string $title, string $description, string $url, string $link_text): string
    {
        return sprintf(
            '<div class="ac-acf-upsell">'
            . '<strong>%s</strong><br>'
            . '%s <a href="%s" target="_blank">%s</a>'
            . '</div>',
            esc_html($title),
            esc_html($description),
            esc_url($url),
            esc_html($link_text)
        );
    }

    private function render_inline_style(): void
    {
        static $rendered = false;

        if ($rendered) {
            return;
        }

        $rendered = true;

        echo '<style>
            .ac-acf-intro {
                font-size: 13px;
                color: #50575e;
                line-height: 1.5;
                margin: 0 0 16px;
            }
            .ac-acf-list {
                display: grid;
                gap: 8px;
            }
            .ac-acf-card {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                padding: 12px 16px;
                border: 1px solid #e3e5e8;
                border-radius: 6px;
                background: #fff;
            }
            .ac-acf-card--added {
                background: #fbfcfb;
            }
            .ac-acf-card--unavailable {
                opacity: .6;
            }
            .ac-acf-notice {
                background: #f0f6fc;
                border: 1px solid #c5d9ed;
                border-radius: 4px;
                padding: 10px 14px;
                font-size: 13px;
                color: #1f4f77;
                margin-bottom: 12px;
            }
            .ac-acf-card-main {
                display: flex;
                align-items: center;
                gap: 16px;
                min-width: 0;
            }
            .ac-acf-card-name {
                font-size: 13px;
                font-weight: 600;
                color: #2c3338;
            }
            .ac-acf-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .ac-acf-badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #eaf6ec;
                color: #008a20;
                font-size: 10px;
                font-weight: 700;
                flex-shrink: 0;
                line-height: 1;
            }
            .ac-acf-card-status {
                font-size: 13px;
                color: #646970;
            }
            .ac-acf-card-actions {
                flex: 0 0 auto;
            }
            .ac-acf-link {
                color: #2271b1;
                font-size: 13px;
                font-weight: 500;
                text-decoration: none;
            }
            .ac-acf-link:hover {
                color: #135e96;
                text-decoration: underline;
            }
            .ac-acf-helper {
                margin: 12px 0 0;
                font-size: 12px;
                color: #646970;
            }
            .ac-acf-upsell {
                background: #f0f6fc;
                border: 1px solid #c5d9ed;
                border-radius: 6px;
                padding: 16px 20px;
                font-size: 13px;
                line-height: 1.6;
                color: #1d2327;
            }
            .ac-acf-upsell strong {
                color: #1f4f77;
            }
            .ac-acf-upsell a {
                color: #2271b1;
                font-weight: 500;
                text-decoration: none;
            }
            .ac-acf-upsell a:hover {
                text-decoration: underline;
            }
        </style>';
    }

    private function render_inline_script(string $nonce): void
    {
        static $rendered = false;

        if ($rendered) {
            return;
        }

        $rendered = true;

        ?>
		<script>
			(function( $ ) {
				$( document ).on( 'click', '.ac-acf-add-column', function() {
					var $button = $( this );
					var $card = $button.closest( '.ac-acf-card' );
					var tableId = $card.data( 'table-id' );

					var $fieldObject = $button.closest( '.acf-field-object' );
					var metaKey = $fieldObject.find( '.acf-field[data-name="name"] input' ).val() || $fieldObject.data( 'field-name' ) || '';

					if ( !metaKey ) {
						return;
					}

					var fieldData = {
						name : metaKey,
						type : $fieldObject.data( 'type' ) || '',
						label : $fieldObject.find( '.acf-field[data-name="label"] input' ).val() || '',
						prepend : $fieldObject.find( '.acf-field[data-name="prepend"] input' ).val() || '',
						append : $fieldObject.find( '.acf-field[data-name="append"] input' ).val() || ''
					};

					$button.prop( 'disabled', true ).text( '<?php echo esc_js(__('Adding...', 'codepress-admin-columns')); ?>' );

					$.post( ajaxurl, {
						action : 'ac-acf-add-column',
						_ajax_nonce : '<?php echo esc_js($nonce); ?>',
						table_id : tableId,
						meta_key : metaKey,
						field_data : JSON.stringify( fieldData )
					} ).done( function( response ) {
						if ( response.success ) {
							var editorUrl = response.data && response.data.editor_url ? response.data.editor_url : '#';
							var label = $card.find( '.ac-acf-card-name' ).text();
							var $link = $( '<a>', {
								href : editorUrl,
								target : '_blank',
								'class' : 'ac-acf-link',
								text : '<?php echo esc_js(__('Edit in Admin Columns →', 'codepress-admin-columns')); ?>'
							} );

							$card.addClass( 'ac-acf-card--added' ).attr( 'data-state', 'added' ).empty()
								.append(
									$( '<div>', { 'class' : 'ac-acf-card-main' } )
										.append( $( '<span>', { 'class' : 'ac-acf-card-name', text : label } ) )
										.append(
											$( '<span>', { 'class' : 'ac-acf-status' } )
												.append( $( '<span>', { 'class' : 'ac-acf-badge', html : '&#10003;' } ) )
												.append( $( '<span>', { 'class' : 'ac-acf-card-status', text : '<?php echo esc_js(__('Column added', 'codepress-admin-columns')); ?>' } ) )
										)
								)
								.append(
									$( '<div>', { 'class' : 'ac-acf-card-actions' } ).append( $link )
								);
						} else {
							$button.prop( 'disabled', false ).text( '<?php echo esc_js(__('Add column', 'codepress-admin-columns')); ?>' );
						}
					} ).fail( function() {
						$button.prop( 'disabled', false ).text( '<?php echo esc_js(__('Add column', 'codepress-admin-columns')); ?>' );
					} );
				} );
			})( jQuery );
		</script>
        <?php
    }

    private function is_pro_only_field(array $field): bool
    {
        if ($field['type'] === 'select' && ! empty($field['multiple'])) {
            return true;
        }

        return $this->is_sub_field($field) || in_array($field['type'], self::PRO_ONLY_ACF_TYPES, true);
    }

    private function is_field_supported(array $field): bool
    {
        return in_array($field['type'], self::SUPPORTED_ACF_TYPES, true);
    }

    private function is_sub_field(array $field): bool
    {
        if (isset($field['parent_repeater'])
            || isset($field['parent_group'])
            || isset($field['parent_layout'])
            || isset($field['_clone'])
        ) {
            return true;
        }

        $parent = $field['parent'] ?? 0;

        if ($parent) {
            $parent_field = acf_get_field($parent);

            if ($parent_field && in_array($parent_field['type'], ['group', 'repeater', 'flexible_content'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return TableScreen[]
     */
    private function resolve_table_screens(array $field): array
    {
        static $cache = [];

        $parent = $field['parent'] ?? 0;

        if ( ! isset($cache[$parent])) {
            $group = acf_get_field_group($parent);
            $cache[$parent] = $group ? $this->table_screen_resolver->resolve($group) : [];
        }

        return $cache[$parent];
    }

    /**
     * @return array{list_screen: ListScreen, column: Column}|null
     */
    private function find_column_in_list_screens(TableId $table_id, string $meta_key): ?array
    {
        foreach ($this->storage->find_all_by_table_id($table_id) as $list_screen) {
            $column = $this->column_matcher->find_column($list_screen, $meta_key);

            if ($column) {
                return [
                    'list_screen' => $list_screen,
                    'column'      => $column,
                ];
            }
        }

        return null;
    }

}
