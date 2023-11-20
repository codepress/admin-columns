<?php

namespace AC\Settings;

use AC;
use AC\Form\Element;
use AC\Setting\ConditionCollection;
use AC\View;

abstract class Column implements AC\Setting\Setting
{
    use AC\Setting\SettingTrait;

    protected $column;

    public function __construct( AC\Column $column, ConditionCollection $conditions = null ) {
        //$this->column = $column;
        $this->conditions = $conditions;
    }

//    /**
//     * A (short) reference to this setting
//     * @var string
//     */
//    protected $name;
//
//    /**
//     * The options this field manages (optionally with default values)
//     * @var array
//     */
//    protected $options = [];
//
//    /**
//     * @var AC\Column
//     */
//    protected $column;
//
//    /**
//     * Options that are set by the user and should not be overwritten with defaults
//     * @var array
//     */
//    private $user_set = [];
//
//    /**
//     * @param AC\Column $column
//     */
//    public function __construct(AC\Column $column)
//    {
//        $this->column = $column;
//
//        $this->set_options();
//        $this->set_name();
//    }
//
//    /**
//     * @return array
//     * @see AC\Settings_Column::$options
//     */
//    protected abstract function define_options();
//
//    /**
//     * Create a string representation of this setting
//     * @return View|false
//     */
//    public abstract function create_view();
//
//    /**
//     * Get settings that depend on this setting
//     * @return Column[]
//     */
//    public function get_dependent_settings()
//    {
//        return [];
//    }
//
//    private function set_options()
//    {
//        foreach ($this->define_options() as $option => $value) {
//            if (is_numeric($option)) {
//                $option = $value;
//                $value = null;
//            }
//
//            $this->set_option($option, $value);
//            $this->set_default($value, $option);
//        }
//    }
//
//    /**
//     * Set an option and set value afterwards
//     *
//     * @param string $option
//     * @param mixed  $value
//     */
//    private function set_option($option, $value = null)
//    {
//        $this->options[$option] = $value;
//    }
//
//    public function has_option($option)
//    {
//        return array_key_exists($option, $this->options);
//    }
//
//    /**
//     * Get a managed option
//     * @return false|string
//     */
//    protected function get_default_option()
//    {
//        reset($this->options);
//
//        return key($this->options);
//    }
//
//    /**
//     * Return the value of all options
//     * @return array
//     */
//    public function get_values()
//    {
//        $values = [];
//
//        foreach (array_keys($this->options) as $option) {
//            $values[$option] = $this->get_value($option);
//        }
//
//        return $values;
//    }
//
//    /**
//     * Get value of this setting, optionally specified with a key
//     * Will return the value of the default option
//     *
//     * @param string|null $option
//     *
//     * @return string|array|int|bool
//     */
//    public function get_value($option = null)
//    {
//        if (null === $option) {
//            $option = $this->get_default_option();
//        }
//
//        if ( ! $this->has_option($option)) {
//            return null;
//        }
//
//        $method = 'get_' . $option;
//
//        if ( ! method_exists($this, $method)) {
//            return null;
//        }
//
//        return $this->$method();
//    }
//
//    /**
//     * Set the values of this setting
//     *
//     * @param array $values
//     */
//    public function set_values(array $values)
//    {
//        foreach ($values as $option => $value) {
//            $this->set_value($value, $option);
//        }
//    }
//
//    /**
//     * Invoke the setter of the setting
//     *
//     * @param string|array|int|bool $value
//     * @param string                $option
//     *
//     * @return bool
//     */
//    private function invoke_option_setter($option, $value)
//    {
//        $method = 'set_' . $option;
//
//        if ( ! method_exists($this, $method)) {
//            return false;
//        }
//
//        return $this->$method($value);
//    }
//
//    /**
//     * Set value of an option
//     *
//     * @param string|array|int|bool $value
//     * @param string|null           $option
//     *
//     * @return bool
//     */
//    public function set_value($value, $option = null)
//    {
//        if (null === $option) {
//            $option = $this->get_default_option();
//        }
//
//        if ( ! $this->has_option($option)) {
//            return false;
//        }
//
//        $result = $this->invoke_option_setter($option, $value);
//
//        if ($result) {
//            $this->user_set[] = $option;
//        }
//
//        return $result;
//    }
//
//    /**
//     * Set a default value unless option is loaded from settings
//     *
//     * @param string|array|int|bool $value
//     * @param string|null           $option
//     *
//     * @return bool
//     */
//    public function set_default($value, $option = null)
//    {
//        if (null === $option) {
//            $option = $this->get_default_option();
//        }
//
//        if ( ! $this->has_option($option)) {
//            return false;
//        }
//
//        $this->set_option($option, $value);
//
//        // check if value is user set
//        if ( ! in_array($option, $this->user_set)) {
//            $this->invoke_option_setter($option, $value);
//        }
//
//        return true;
//    }
//
//    /**
//     * Get the default value of an option if set
//     *
//     * @param string|null $option
//     *
//     * @return mixed
//     */
//    public function get_default($option = null)
//    {
//        if (null === $option) {
//            $option = $this->get_default_option();
//        }
//
//        return $this->has_option($option) ? $this->options[$option] : null;
//    }
//
//    /**
//     * @return string
//     */
//    public function get_name()
//    {
//        return $this->name;
//    }
//
//    /**
//     * Default to self::get_default_option()
//     */
//    protected function set_name()
//    {
//        $this->name = $this->get_default_option();
//    }
//
//    /**
//     * Add an element to this setting
//     *
//     * @param string      $type
//     * @param string|null $name
//     *
//     * @return Element\Select|Element\Input|Element\Radio
//     */
//    protected function create_element($type, $name = null)
//    {
//        if (null === $name) {
//            $name = $this->get_default_option();
//        }
//
//        switch ($type) {
//            case 'checkbox' :
//                $element = new Element\Checkbox($name);
//
//                break;
//            case 'radio' :
//                $element = new Element\Radio($name);
//
//                break;
//            case 'select' :
//                $element = new AC\Settings\Form\Element\Select($name);
//
//                break;
//            case 'multi-select' :
//                $element = new Element\MultiSelect($name);
//
//                break;
//            default:
//                $element = new Element\Input($name);
//                $element->set_type($type);
//        }
//
//        $element->set_name($name);
//        $element->set_id(sprintf('ac-%s-%s', $this->column->get_name(), $name));
//        $element->add_class('ac-setting-input_' . $name);
//
//        // try to set current value
//        $value = $this->get_value($name);
//
//        if (null !== $value) {
//            $element->set_value($value);
//        }
//
//        return $element;
//    }
//
//    /**
//     * Render the output of self::create_header()
//     * @return false|string
//     */
//    public function render_header()
//    {
//        if ( ! ($this instanceof Header)) {
//            return false;
//        }
//
//        /* @var Header $this */
//        $view = $this->create_header_view();
//
//        if ( ! ($view instanceof View)) {
//            return false;
//        }
//
//        if (null == $view->get_template()) {
//            $view->set_template('settings/header');
//        }
//
//        if (null == $view->setting) {
//            $view->set('setting', $this->get_name());
//        }
//
//        return $view->render();
//    }
//
//    /**
//     * Render the output of self::create_view()
//     * @return false|string
//     */
//    public function render()
//    {
//        $view = $this->create_view();
//
//        if ( ! ($view instanceof View)) {
//            return false;
//        }
//
//        $template = 'settings/section';
//
//        // set default template
//        if (null === $view->get_template()) {
//            $view->set_template($template);
//        }
//
//        // set default name
//        if (null === $view->get('name')) {
//            $view->set('name', $this->name);
//        }
//
//        // set default for
//        if (null === $view->get('for')) {
//            $setting = $view->get('setting');
//
//            if ($setting instanceof AC\Form\Element) {
//                $view->set('for', $setting->get_id());
//            }
//        }
//
//        // set default template for nested sections
//        foreach ((array)$view->sections as $section) {
//            if ($section instanceof View && null === $section->get_template()) {
//                $section->set_template($template);
//            }
//        }
//
//        return $view->render();
//    }
//
//    public function __toString()
//    {
//        $rendered = $this->render();
//
//        if ( ! is_string($rendered)) {
//            return '';
//        }
//
//        return $rendered;
//    }
//
//    public function get_column()
//    {
//        return $this->column;
//    }
//
//    // TODO should be abstract and can be used as alternative of creating views
//    public function get_config(): ?array
//    {
//        return null;
//    }

}