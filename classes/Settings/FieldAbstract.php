<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_FieldAbstract {

	/**
	 * @var AC_Column $column
	 */
	protected $column;

	/**
	 * @var string Describe the type of settings
	 */
	private $type;

	/**
	 * @var string|int|bool $default_value
	 */
	private $default_value;

	//abstract function display();

	private $group;

	public function add_group( $group ) {
		$this->group = $group;
	}

	public function get_group() {
		return $this->group;
	}

	/**
	 * @param AC_Column $column
	 *
	 * @return $this
	 */
	public function set_column( AC_Column $column ) {
		$this->column = $column;

		return $this;
	}

	/**
	 * @param $default_value
	 */
	public function set_default_value( $default_value ) {
		$this->default_value = $default_value;
	}

	/**
	 * @return bool|int|string
	 */
	public function get_default_value() {
		return $this->default_value;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Get type of the field settings. Used as an ID.
	 */
	public function get_type() {
		return $this->type;
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
		return 'columns[' . $this->column->get_name() . '][' . $field_name . ']';
	}

	/**
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function get_attr_id( $field_name ) {
		return 'cpac-' . $this->column->get_name() . '-' . $field_name;
	}

	public function attr_id( $field_name ) {
		echo esc_attr( $this->get_attr_id( $field_name ) );
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param array $args
	 */
	public function label( $args = array() ) {
		$defaults = array(
			'label'       => '',
			'description' => '',
			'for'         => '',
			'more_link'   => false,
		);

		$data = (object) wp_parse_args( $args, $defaults );

		if ( $data->label ) : ?>
			<td class="label<?php echo esc_attr( $data->description ? ' description' : '' ); ?>">
				<label for="<?php $this->attr_id( $data->for ); ?>">
					<span class="label"><?php echo stripslashes( $data->label ); ?></span>
					<?php if ( $data->more_link ) : ?>
						<a target="_blank" class="more-link" title="<?php echo esc_attr( __( 'View more' ) ); ?>" href="<?php echo esc_url( $data->more_link ); ?>"><span class="dashicons dashicons-external"></span></a>
					<?php endif; ?>
					<?php if ( $data->description ) : ?><p class="description"><?php echo $data->description; ?></p><?php endif; ?>
				</label>
			</td>
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
			'html'           => '', // HTML output
			'fields'         => false,
		);
		$args = wp_parse_args( $args, $defaults );

		$args['value'] = $this->column->get_option( $args['name'] );
		$args['attr_name'] = $this->get_attr_name( $args['name'] );
		$args['attr_id'] = $this->get_attr_id( $args['name'] );

		$field = (object) $args;
		?>
		<tr class="<?php echo esc_attr( $field->type ); ?> column-<?php echo esc_attr( $field->name ); ?><?php echo esc_attr( $field->hidden ? ' hide' : '' ); ?><?php echo esc_attr( $field->section ? ' section' : '' ); ?>"<?php echo $field->toggle_handle ? ' data-handle="' . esc_attr( $this->get_attr_id( $field->toggle_handle ) ) . '"' : ''; ?><?php echo $field->refresh_column ? ' data-refresh="1"' : ''; ?>>

			<?php $this->label( array( 'label' => $field->label, 'description' => $field->description, 'for' => ( $field->for ? $field->for : $field->name ), 'more_link' => $field->more_link ) ); ?>

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
					case 'html' :
						echo $field->html;
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
				<?php $this->label( $args ); ?>
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

	public function row() {
		?>
		<tr>
			<td></td>
			<td>
				<table>


				</table>
			</td>
		</tr>
<?php
	}

	// TODO

	/**
	 * @var AC_Settings_FieldAbstract[]
	 */
	private $fields;

	/**
	 * @param array $fields
	 * @return AC_Settings_FieldAbstract
	 */
	public function add_field( AC_Settings_FieldAbstract $field ) {
		$this->fields[] = $field;

		return $this;
	}

	public function get_args() {
		return array();
	}

	public function display() {
		if ( $this->fields ) {
			foreach ( $this->fields as $field ) {
				$field->display();
			}
		}

		$this->field( $this->get_args() );
	}

	/*private $fields;

	public function add_field( AC_Settings_FieldAbstract $field ) {
		$this->fields[ $field->get_type() ] = $field;

		return $this;
	}*/


}