<?php

namespace AC\Admin;

use AC\View;

class Tooltip
{

    private string $id;

    private string $content = '';

    private string $link_label;

    private string $title;

    private string $position = 'right';

    public function __construct(string $id, array $args = [])
    {
        $this->id = $id;
        $this->title = __('Notice', 'codepress-admin-columns');
        $this->link_label = __('Instructions', 'codepress-admin-columns');

        $this->populate($args);
    }

    private function populate($args): void
    {
        foreach ($args as $key => $value) {
            $method = 'set_' . $key;

            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $value);
            }
        }
    }

    public function set_id(string $id): Tooltip
    {
        $this->id = $id;

        return $this;
    }

    public function set_content(string $content): Tooltip
    {
        $this->content = $content;

        return $this;
    }

    public function set_title(string $title): Tooltip
    {
        $this->title = $title;

        return $this;
    }

    public function set_link_label(string $label): Tooltip
    {
        $this->link_label = $label;

        return $this;
    }

    public function set_position(string $position): Tooltip
    {
        $this->position = $position;

        return $this;
    }

    public function get_label(): string
    {
        $view = new View([
            'id'       => $this->id,
            'position' => $this->position,
            'label'    => $this->link_label,
        ]);

        $view->set_template('admin/tooltip-label');

        return $view->render();
    }

    public function get_instructions(): string
    {
        $view = new View([
            'id'       => $this->id,
            'title'    => $this->title,
            'content'  => $this->content,
            'position' => $this->position,
        ]);

        $view->set_template('admin/tooltip-body');

        return $view->render();
    }

}