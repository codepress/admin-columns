<?php

namespace AC\Message;

use AC\Message;
use AC\View;

class Plugin extends Message
{

    protected $plugin_basename;

    protected $icon;

    public function __construct(string $message, string $plugin_basename, string $type = null)
    {
        if (null === $type) {
            $type = self::WARNING;
        }

        parent::__construct($message, $type);

        $this->plugin_basename = $plugin_basename;
        $this->icon = $this->get_icon_by_current_type();
    }

    public function register(): void
    {
        add_action('after_plugin_row_' . $this->plugin_basename, [$this, 'display'], 11);
    }

    public function render(): string
    {
        switch ($this->type) {
            case self::SUCCESS :
                $class = 'updated-message notice-success';

                break;
            case self::INFO :
                $class = self::WARNING;

                break;
            default:
                $class = $this->type;
        }

        $is_plugin_active = is_multisite() && is_network_admin()
            ? is_plugin_active_for_network($this->plugin_basename)
            : is_plugin_active($this->plugin_basename);

        $status = $is_plugin_active
            ? 'active'
            : 'inactive';

        $data = [
            'plugin_basename' => $this->plugin_basename,
            'icon'            => $this->icon,
            'class'           => $class,
            'message'         => $this->message,
            'type'            => $this->type,
            'status'          => $status,
        ];

        $view = new View($data);
        $view->set_template('message/plugin');

        return $view->render();
    }

    /**
     * @return string
     */
    protected function get_icon_by_current_type()
    {
        $mapping = [
            self::SUCCESS => '\f147', // yes
            self::WARNING => '\f348', // info
            self::ERROR   => '\f534', // warning
            self::INFO    => '\f14c', // info outline
        ];

        if ( ! isset($mapping[$this->type])) {
            return false;
        }

        return $mapping[$this->type];
    }

    public function set_icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

}