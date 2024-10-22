<?php

namespace AC\Sanitize;

class Kses
{

    public function sanitize(string $string): string
    {
        return wp_kses($string, $this->get_allowed_html(), $this->get_allowed_protocols());
    }

    private function get_allowed_html(): array
    {
        // TODO test javascript injection prevention
        $html = wp_kses_allowed_html('post');

        $html['iframe'] = [
            'src'             => true,
            'height'          => true,
            'width'           => true,
            'frameborder'     => true,
            'allowfullscreen' => true,
        ];

        return $html;
    }

    protected function get_allowed_protocols(): array
    {
        return array_merge(
            wp_allowed_protocols(),
            ['data']
        );
    }

}