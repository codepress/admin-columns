<?php

declare(strict_types=1);

namespace AC\Storage\Encoder;

use AC;
use AC\ListScreen;
use AC\Plugin\Version;
use AC\Setting\Recursive;
use AC\Setting\SettingCollection;

class BaseEncoder implements AC\Storage\Encoder
{

    private $version;

    /**
     * @var ListScreen|null
     */
    private $list_screen;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function set_list_screen(ListScreen $list_screen): self
    {
        $this->list_screen = $list_screen;

        return $this;
    }

    public function encode(): array
    {
        $encoded_data = [
            'version' => (string)$this->version,
        ];

        if ($this->list_screen instanceof ListScreen) {
            $encoded_data['list_screen'] = [
                'title'    => $this->list_screen->get_title(),
                'type'     => (string)$this->list_screen->get_key(),
                'id'       => (string)$this->list_screen->get_id(),
                'updated'  => $this->list_screen->get_updated()->getTimestamp(),
                'columns'  => $this->encode_columns(),
                'settings' => $this->get_preferences(),
            ];
        }

        return $encoded_data;
    }

    protected function get_preferences(): array
    {
        return $this->list_screen->get_preferences();
    }

    private function encode_settings(SettingCollection $settings, array $encoded = []): array
    {
        foreach ($settings as $setting) {
            if ($setting instanceof AC\Setting\Setting) {
                $encoded[$setting->get_input()->get_name()] = $setting->get_input()->get_default();
            }

            if ($setting instanceof Recursive) {
                $encoded = $this->encode_settings($setting->get_children(), $encoded);
            }
        }

        return $encoded;
    }

    private function encode_columns(): array
    {
        $encode = [];

        /**
         * @var AC\Column $column
         */
        foreach ($this->list_screen->get_columns() as $column) {
            $data = $this->encode_settings($column->get_settings());
            $data['type'] = $column->get_type();

            $encode[] = $data;
        }

        return $encode;
    }
}