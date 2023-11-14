<?php

namespace AC\Table;

use AC\Registerable;

// TODO
final class TableFormView implements Registerable
{

    public const PARAM_ACTION = 'ac-actions-form';

    private $type;

    private $html;

    private $priority;

    public function __construct(string $type, string $html, int $priority = null)
    {
        if (null === $priority) {
            $priority = 10;
        }

        $this->type = $type;
        $this->html = $html;
        $this->priority = $priority;
    }

    public function register(): void
    {
        switch ($this->type) {
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

    public function render()
    {
        echo $this->html;
    }

}