<?php

namespace AC\Table;

use AC\Registerable;

final class TableFormView implements Registerable
{

    public const PARAM_ACTION = 'ac-actions-form';

    private string $meta_type;

    private string $html;

    private int $priority;

    public function __construct(string $meta_type, string $html, ?int $priority = null)
    {
        if (null === $priority) {
            $priority = 10;
        }

        $this->meta_type = $meta_type;
        $this->html = $html;
        $this->priority = $priority;
    }

    public function register(): void
    {
        switch ($this->meta_type) {
            case 'post':
                add_action('restrict_manage_posts', [$this, 'render'], $this->priority);

                break;
            case'user':
                add_action('restrict_manage_users', [$this, 'render'], $this->priority);

                break;
            case 'comment':
                add_action('restrict_manage_comment', [$this, 'render'], $this->priority);

                break;
        }
    }

    public function render(): void
    {
        echo $this->html;
    }

}