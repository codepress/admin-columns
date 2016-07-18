<?php
defined( 'ABSPATH' ) or die();

class AC_ColumnFieldSettings {

	private $name;

	private $storage_key;

	private $options = array();

	public function __construct( $name, $storage_key ) {
		$this->name = $name;
		$this->storage_key = $storage_key;
	}

	public function set_options( $options ) {
		$this->options = $options;
	}

	private function get_option( $name ) {
		return isset( $this->options[ $name ] ) ? $this->options[ $name ] : false;
	}

	/**
	 * @param string $field_name
	 */
	public function attr_name( $field_name ) {
		echo esc_attr( $this->get_attr_name( $field_name ) );
	}

	/**
	 * @param string $field_name
	 *
	 * @return string Attribute name
	 */
	public function get_attr_name( $field_name ) {
		return $this->storage_key . '[' . $this->name . '][' . $field_name . ']';
	}

	/**
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function get_attr_id( $field_name ) {
		return 'cpac-' . $this->storage_key . '-' . $this->name . '-' . $field_name;
	}

	public function attr_id( $field_name ) {
		echo esc_attr( $this->get_attr_id( $field_name ) );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function label_view( $label, $description = '', $for = '', $more_link = false ) {
		if ( $label ) : ?>
			<td class="label<?php echo esc_attr( $description ? ' description' : '' ); ?>">
				<label for="<?php $this->attr_id( $for ); ?>">
					<span class="label"><?php echo stripslashes( $label ); ?></span>
					<?php if ( $more_link ) : ?>
						<a target="_blank" class="more-link" title="<?php echo esc_attr( __( 'View more' ) ); ?>" href="<?php echo esc_url( $more_link ); ?>"><span class="dashicons dashicons-external"></span></a>
					<?php endif; ?>
					<?php if ( $description ) : ?><p class="description"><?php echo $description; ?></p><?php endif; ?>
				</label>
			</td>
			<?php
		endif;
	}

	public function image_field_args( $fields_only = false ) {
		$label = __( 'Image Size', 'codepress-admin-columns' );

		$image_size_w = array(
			'type'          => 'text',
			'name'          => 'image_size_w',
			'label'         => __( "Width", 'codepress-admin-columns' ),
			'description'   => __( "Width in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_w',
			'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' ),
		);

		$image_size_h = array(
			'type'          => 'text',
			'name'          => 'image_size_h',
			'label'         => __( "Height", 'codepress-admin-columns' ),
			'description'   => __( "Height in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_h',
			'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' ),
		);

		if ( $fields_only ) {
			return array(
				array(
					'label'           => $label,
					'type'            => 'select',
					'name'            => 'image_size',
					'grouped_options' => $this->get_grouped_image_sizes(),
				),
				$image_size_w,
				$image_size_h,
			);
		}

		return array(
			'label'  => $label,
			'fields' => array(
				array(
					'type'            => 'select',
					'name'            => 'image_size',
					'grouped_options' => $this->get_grouped_image_sizes(),
				),
				$image_size_w,
				$image_size_h,
			),
		);
	}

	public function image_field() {
		$this->fields( $this->image_field_args() );
	}

	/**
	 * @param array $args
	 */
	public function fields( $args = array() ) {
		$defaults = array(
			'label'       => '',
			'description' => '',
			'fields'      => array(),
		);
		$args = wp_parse_args( $args, $defaults );

		if ( $fields = array_filter( $args['fields'] ) ) : ?>
			<tr class="section">
				<?php $this->label_view( $args['label'], $args['description'] ); ?>
				<td class="input nopadding">
					<table class="widefat">
						<?php foreach ( $fields as $field ) {
							$this->field( $field );
						} ?>
					</table>
				</td>
			</tr>
			<?php
		endif;

	}

	/**
	 * @since NEWVERSION
	 */
	public function field( $args = array() ) {
		$defaults = array(
			'type'           => 'text',
			'name'           => '',
			'label'          => '', // empty label will apply colspan 2
			'description'    => '',
			'toggle_trigger' => '', // triggers a toggle event on toggle_handle
			'toggle_handle'  => '', // can be used to toggle this element
			'refresh_column' => false, // when value is selected the column element will be refreshed with ajax
			'hidden'         => false,
			'for'            => false,
			'section'        => false,
			'help'           => '', // help message below input field
			'more_link'      => '', // link to more, e.g. admin page for a field
		);
		$args = wp_parse_args( $args, $defaults );

		$args['current'] = $this->get_option( $args['name'] );
		$args['attr_name'] = $this->get_attr_name( $args['name'] );
		$args['attr_id'] = $this->get_attr_id( $args['name'] );

		$field = (object) $args;
		?>
		<tr class="<?php echo esc_attr( $field->type ); ?> column-<?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $field->hidden ? ' hide' : '' ); ?><?php echo esc_attr( $field->section ? ' section' : '' ); ?>"<?php echo $field->toggle_handle ? ' data-handle="' . esc_attr( $this->get_attr_id( $field->toggle_handle ) ) . '"' : ''; ?><?php echo $field->refresh_column ? ' data-refresh="1"' : ''; ?>>
			<?php $this->label_view( $field->label, $field->description, ( $field->for ? $field->for : $field->name ), $field->more_link ); ?>
			<td class="input"<?php echo( $field->toggle_trigger ? ' data-trigger="' . esc_attr( $this->get_attr_id( $field->toggle_trigger ) ) . '"' : '' ); ?><?php echo empty( $field->label ) ? ' colspan="2"' : ''; ?>>
				<?php
				switch ( $field->type ) {
					case 'select' :
						ac_helper()->formfield->select( $args );
						break;
					case 'radio' :
						ac_helper()->formfield->radio( $args );
						break;
					case 'text' :
						ac_helper()->formfield->text( $args );
						break;
					case 'message' :
						ac_helper()->formfield->message( $args );
						break;
					case 'number' :
						ac_helper()->formfield->number( $args );
						break;
					case 'width' :
						$this->width_field();
						break;
				}

				if ( $field->help ) : ?>
					<p class="help-msg">
						<?php echo $field->help; ?>
					</p>
				<?php endif; ?>

			</td>
		</tr>
		<?php
	}

	/**
	 * @since 1.0
	 * @return array Image Sizes.
	 */
	private function get_grouped_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array(
			'default' => array(
				'title'   => __( 'Default', 'codepress-admin-columns' ),
				'options' => array(
					'thumbnail' => __( "Thumbnail", 'codepress-admin-columns' ),
					'medium'    => __( "Medium", 'codepress-admin-columns' ),
					'large'     => __( "Large", 'codepress-admin-columns' ),
				),
			),
		);

		$all_sizes = get_intermediate_image_sizes();

		if ( ! empty( $all_sizes ) ) {
			foreach ( $all_sizes as $size ) {
				if ( 'medium_large' == $size || isset( $sizes['default']['options'][ $size ] ) ) {
					continue;
				}

				if ( ! isset( $sizes['defined'] ) ) {
					$sizes['defined']['title'] = __( "Others", 'codepress-admin-columns' );
				}

				$sizes['defined']['options'][ $size ] = ucwords( str_replace( '-', ' ', $size ) );
			}
		}

		// add sizes
		foreach ( $sizes as $key => $group ) {
			foreach ( array_keys( $group['options'] ) as $_size ) {

				$w = isset( $_wp_additional_image_sizes[ $_size ]['width'] ) ? $_wp_additional_image_sizes[ $_size ]['width'] : get_option( "{$_size}_size_w" );
				$h = isset( $_wp_additional_image_sizes[ $_size ]['height'] ) ? $_wp_additional_image_sizes[ $_size ]['height'] : get_option( "{$_size}_size_h" );
				if ( $w && $h ) {
					$sizes[ $key ]['options'][ $_size ] .= " ({$w} x {$h})";
				}
			}
		}

		// last
		$sizes['default']['options']['full'] = __( "Full Size", 'codepress-admin-columns' );

		$sizes['custom'] = array(
			'title'   => __( 'Custom', 'codepress-admin-columns' ),
			'options' => array( 'cpac-custom' => __( 'Custom Size', 'codepress-admin-columns' ) . '..' ),
		);

		return $sizes;
	}

	/**
	 * @since NEWVERSION
	 * @return int Width
	 */
	public function get_width() {
		$width = absint( $this->get_option( 'width' ) );

		return $width > 0 ? $width : false;
	}

	/**
	 * @since NEWVERSION
	 * @return string px or %
	 */
	public function get_width_unit() {
		return 'px' === $this->get_option( 'width_unit' ) ? 'px' : '%';
	}

	/**
	 * @since NEWVERSION
	 */
	private function width_field() {
		?>
		<div class="description" title="<?php echo esc_attr( __( 'default', 'codepress-admin-columns' ) ); ?>">
			<input class="width" type="text" placeholder="<?php echo esc_attr( __( 'auto', 'codepress-admin-columns' ) ); ?>" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo esc_attr( $this->get_width() ); ?>"/>
			<span class="unit"><?php echo esc_html( $this->get_width_unit() ); ?></span>
		</div>
		<div class="width-slider"></div>

		<div class="unit-select">

			<?php
			ac_helper()->formfield->radio( array(
				'attr_id'   => $this->get_attr_id( 'width_unit' ),
				'attr_name' => $this->get_attr_name( 'width_unit' ),
				'options'   => array(
					'px' => 'px',
					'%'  => '%',
				),
				'class'     => 'unit',
				'default'   => '%',
			) );
			?>
		</div>
		<?php
	}

}