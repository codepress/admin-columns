<?php

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Setting\Base;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Option;
use AC\Settings;
use ACP\Expression\Specification;
use ACP\Expression\StringComparisonSpecification;

class Image extends Settings\Column implements AC\Setting\Recursive
{

    use SettingTrait;
    use AC\Setting\RecursiveTrait;

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'image_size';
        $this->label = __('Image Size', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            $this->get_grouped_image_sizes()
        );

        parent::__construct($column, $specification);
    }

    public function is_parent(): bool
    {
        return true;
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            new Base\Setting(
                'image_size_w',
                __('Width', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal('cpac-custom')
            ),
            new Base\Setting(
                'image_size_h',
                __('Height', 'codepress-admin-columns'),
                '',
                Input\Number::create_single_step(0, null, 60),
                StringComparisonSpecification::equal('cpac-custom')
            ),
        ]);
    }


    //implements Settings\FormatValue {

    //	/**
    //	 * @var string
    //	 */
    //	private $image_size;
    //
    //	/**
    //	 * @var integer
    //	 */
    //	private $image_size_w;
    //
    //	/**
    //	 * @var integer
    //	 */
    //	private $image_size_h;
    //
    //	protected function set_name() {
    //		return $this->name = 'image';
    //	}
    //
    //	protected function define_options() {
    //		return [
    //			'image_size'   => 'cpac-custom',
    //			'image_size_w' => 60,
    //			'image_size_h' => 60,
    //		];
    //	}
    //
    //	public function create_view() {
    //		$width = new View( [
    //			'setting' => $this->create_element( 'number', 'image_size_w' ),
    //			'label'   => __( 'Width', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Width in pixels', 'codepress-admin-columns' ),
    //		] );
    //
    //		$height = new View( [
    //			'setting' => $this->create_element( 'number', 'image_size_h' ),
    //			'label'   => __( 'Height', 'codepress-admin-columns' ),
    //			'tooltip' => __( 'Height in pixels', 'codepress-admin-columns' ),
    //		] );
    //
    //		$size = $this->create_element( 'select', 'image_size' )
    //		             ->set_options( $this->get_grouped_image_sizes() );
    //
    //		$view = new View( [
    //			'label'    => __( 'Image Size', 'codepress-admin-columns' ),
    //			'setting'  => $size,
    //			'sections' => [ $width, $height ],
    //		] );
    //
    //		return $view;
    //	}
    //
    //	/**
    //	 * @return array
    //	 * @since 1.0
    //	 */

    private function get_dimension_for_size(string $size)
    {
        global $_wp_additional_image_sizes;

        $w = $_wp_additional_image_sizes[$size]['width'] ?? get_option(
            "{$size}_size_w"
        );
        $h = $_wp_additional_image_sizes[$size]['height'] ?? get_option(
            "{$size}_size_h"
        );

        if ($w && $h) {
            return $w && $h ? " ($w x $h)" : '';
        }
    }

    private function append_dimensions(OptionCollection $options): OptionCollection
    {
        $labeled_options = new OptionCollection();

        foreach ($options as $option) {
            $size = $this->get_dimension_for_size($option->get_value());

            $labeled_options->add(
                new Option(
                    $option->get_label() . $size,
                    $option->get_value(),
                    $option->get_group()
                )
            );
        }

        return $labeled_options;
    }

    private function get_grouped_image_sizes(): OptionCollection
    {
        $default_group = __('Default', 'codepress-admin-columns');
        $options = new OptionCollection([
            new Option(__('Thumbnail', 'codepress-admin-columns'), 'thumbnail', $default_group),
            new Option(__('Medium', 'codepress-admin-columns'), 'medium', $default_group),
            new Option(__('Large', 'codepress-admin-columns'), 'large', $default_group),
            new Option(__('Full Size', 'codepress-admin-columns'), 'full', $default_group),
        ]);

        $custom_group = __('Others', 'codepress-admin-columns');
        foreach (get_intermediate_image_sizes() as $size) {
            if ('medium_large' === $size || isset($sizes['default']['options'][$size])) {
                continue;
            }

            $options->add(
                new Option(
                    ucwords(str_replace('-', ' ', $size)),
                    $size,
                    $custom_group
                )
            );
        }

        $options->add(
            new Option(
                __('Custom Size', 'codepress-admin-columns'),
                'cpac-custom',
                __('Custom', 'codepress-admin-columns')
            )
        );

        return $this->append_dimensions($options);
    }
    //
    //	/**
    //	 * @return string
    //	 */
    //	public function get_image_size() {
    //		return $this->image_size;
    //	}
    //
    //	/**
    //	 * @param string $image_size
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_image_size( $image_size ) {
    //		$this->image_size = $image_size;
    //
    //		return true;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_image_size_w() {
    //		return $this->image_size_w;
    //	}
    //
    //	/**
    //	 * @param int $image_size_w
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_image_size_w( $image_size_w ) {
    //		if ( ! is_numeric( $image_size_w ) ) {
    //			return false;
    //		}
    //
    //		$this->image_size_w = $image_size_w;
    //
    //		return true;
    //	}
    //
    //	/**
    //	 * @return int
    //	 */
    //	public function get_image_size_h() {
    //		return $this->image_size_h;
    //	}
    //
    //	/**
    //	 * @param int $image_size_h
    //	 *
    //	 * @return bool
    //	 */
    //	public function set_image_size_h( $image_size_h ) {
    //		if ( ! is_numeric( $image_size_h ) ) {
    //			return false;
    //		}
    //
    //		$this->image_size_h = $image_size_h;
    //
    //		return true;
    //	}
    //
    //	protected function get_size_args() {
    //		$size = $this->get_image_size();
    //
    //		if ( 'cpac-custom' === $size ) {
    //			$size = [ $this->get_image_size_w(), $this->get_image_size_h() ];
    //		}
    //
    //		// fallback size
    //		if ( empty( $size ) ) {
    //			$size = [ 60, 60 ];
    //		}
    //
    //		return $size;
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		return ac_helper()->image->get_image( $value, $this->get_size_args() );
    //	}

}