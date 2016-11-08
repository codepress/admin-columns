<?php

class AC_Settings_View_Field
	implements AC_Settings_ViewInterface {

	/**
	 * @var AC_Settings_FieldAbstract;
	 */
	protected $field;

	public function __construct( AC_Settings_FieldAbstract $field ) {
		$this->field = $field;
	}

	public function render() {
		$args = array();

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

		$args['value'] = $this->get_option( $args['name'] );
		$args['attr_name'] = $this->get_attr_name( $args['name'] );
		$args['attr_id'] = $this->get_attr_id( $args['name'] );

		$field = (object) $args;
		// start

		$classes = array(
			$this->field->get_type(),
			'column-' . $this->field->get_name(),
		);

		// todo: hide cond. on classes
		$classes[] = 'hide';

		// todo: section cond. on classes
		$classes[] = 'section';

		// todo: map to attribute
		echo( $field->toggle_trigger ? ' data-trigger="' . esc_attr( $this->get_attr_id( $field->toggle_trigger ) ) . '"' : '' );

		$colspan = 2;
		$label = new AC_Settings_View_Label( false );

		if ( $this->field->get_label() ) {
			$colspan = 1;

			//$label = new AC_Settings_View_Label( $this->field->get_label(), $this->field->get_first_element() );

		}

		// todo map to attribute
		empty( $field->label ) ? ' colspan="2"' : '';

		// todo: map to attribute
		echo $field->toggle_handle ? ' data-handle="' . esc_attr( $this->get_attr_id( $field->toggle_handle ) ) . '"' : '';

		// todo: map to attribute
		echo $field->refresh_column ? ' data-refresh="1"' : '';

		$template = '
			<tr class="%s" data-handle="%s" data-refresh="%d">
				%s
				<td class="input" data-trigger="%s" colspan="%d">
					%s
				</td>
			</tr>';

		sprintf(
			$template,
			esc_attr( implode( ' ', $classes ) ),
			'//data-handle',
			'//data-refresh',
			$label->render(),
			'//data-trigger',
			$colspan,
			'//field render'
		);

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
		<?
	}

}