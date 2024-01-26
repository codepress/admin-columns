<?php

namespace AC\Form\Element;

use AC\Form\Element;
use AC\View;

class Toggle extends Element
{

    /**
     * @var bool
     */
    private $checked;

    /**
     * @var string
     */
    private $unchecked_value;

    /**
     * @var array
     */
    private $container_attributes;

    /**
     * @var string
     */
    private $container_class;

    public function __construct($name, $label, $checked = false, $value = null, $unchecked_value = 'false')
    {
        parent::__construct($name);

        $this->set_label($label);
        $this->checked = (bool)$checked;
        $this->unchecked_value = (string)$unchecked_value;
        $this->container_attributes = [];

        if ($value) {
            $this->set_value($value);
        }
    }

    public function set_container_attributes($attributes)
    {
        if (isset($attributes['class'])) {
            $this->container_class = $attributes['class'];
            unset($attributes['class']);
        }

        $this->container_attributes = (array)$attributes;
    }

    protected function get_type()
    {
        return 'checkbox';
    }

    public function render(): string
    {
        $view = new View([
            'id'                   => $this->get_name(),
            'name'                 => $this->get_name(),
            'label'                => $this->get_label(),
            'checked'              => $this->checked,
            'value'                => $this->get_value(),
            'unchecked_value'      => $this->unchecked_value,
            'attributes'           => $this->get_attributes_as_string($this->get_attributes()),
            'container_attributes' => $this->get_attributes_as_string($this->container_attributes),
            'container_class'      => $this->container_class,
        ]);

        return $view->set_template('component/toggle-v2')->render();
    }

}