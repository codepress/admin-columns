<?php

namespace AC\Admin\Asset;

use AC;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Controller\DefaultColumns;

class Columns extends Script
{

    /**
     * @var AC\TableScreen[]
     */
    private $table_screens;

    private $list_key;

    private $list_id;

    public function __construct(
        string $handle,
        Location $location,
        array $table_screens,
        string $list_key,
        string $list_id = null
    ) {
        parent::__construct($handle, $location, [
            'jquery',
            'jquery-ui-slider',
            'jquery-ui-sortable',
            'jquery-touch-punch',
        ]);

        $this->table_screens = $table_screens;
        $this->list_key = $list_key;
        $this->list_id = $list_id;
    }

    public function register(): void
    {
        parent::register();

        $params = [
            '_ajax_nonce'                => wp_create_nonce(AC\Ajax\Handler::NONCE_ACTION),
            'list_screen'                => $this->list_key,
            'layout'                     => $this->list_id,
            'original_columns'           => [],
            'uninitialized_list_screens' => [],
            'i18n'                       => [
                'value'  => __('Value', 'codepress-admin-columns'),
                'label'  => __('Label', 'codepress-admin-columns'),
                'clone'  => __('%s column is already present and can not be duplicated.', 'codepress-admin-columns'),
                'error'  => __('Invalid response.', 'codepress-admin-columns'),
                'errors' => [
                    'save_settings'  => __(
                        'There was an error during saving the column settings.',
                        'codepress-admin-columns'
                    ),
                    'loading_column' => __(
                        'The column could not be loaded because of an unknown error',
                        'codepress-admin-columns'
                    ),
                ],
            ],
        ];

        foreach ($this->table_screens as $table_screen) {
            $params['uninitialized_list_screens'][(string)$table_screen->get_key()] = [
                'screen_link' => (string)$table_screen->get_url()->with_arg(DefaultColumns::QUERY_PARAM, '1'),
            ];
        }

        wp_localize_script('ac-admin-page-columns', 'AC', $params);
    }

}