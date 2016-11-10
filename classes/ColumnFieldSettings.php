<?php
defined( 'ABSPATH' ) or die();

class AC_ColumnFieldSettings {

	private $column;

	public function __construct( AC_Column $column ) {
		$this->column = $column;
	}

	private function get_option( $name ) {
		return $this->column->settings()->get_option( $name );
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

	public function image_size_width_args() {
		return array(
			'type'          => 'text',
			'name'          => 'image_size_w',
			'label'         => __( "Width", 'codepress-admin-columns' ),
			'description'   => __( "Width in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_w',
			'hidden'        => true,
			'default_value' => 80,
		);
	}

	public function image_size_height_args() {
		return array(
			'type'          => 'text',
			'name'          => 'image_size_h',
			'label'         => __( "Height", 'codepress-admin-columns' ),
			'description'   => __( "Height in pixels", 'codepress-admin-columns' ),
			'toggle_handle' => 'image_size_h',
			'hidden'        => true,
			'default_value' => 80,
		);
	}

	/**
	 * @return array
	 */
	public function image_args_fields_only( $default_value = false ) {
		return array(
			array(
				'label'           => __( 'Image Size', 'codepress-admin-columns' ),
				'type'            => 'select',
				'name'            => 'image_size',
				'grouped_options' => $this->get_grouped_image_sizes(),
				'default_value'   => $default_value,
			),
			$this->image_size_width_args(),
			$this->image_size_height_args(),
		);
	}

	/**
	 * @param bool $fields_only Returns fields without the main label
	 *
	 * @return array
	 */
	public function image_args( $default_value = false ) {
		return array(
			'label'  => __( 'Image Size', 'codepress-admin-columns' ),
			'fields' => array(
				array(
					'type'            => 'select',
					'name'            => 'image_size',
					'grouped_options' => $this->get_grouped_image_sizes(),
					'default_value'   => $default_value,
				),
				$this->image_size_width_args(),
				$this->image_size_height_args(),
			),
		);
	}

	public function image() {
		$this->fields( $this->image_args() );
	}

	public function before_args() {
		return array(
			'type'        => 'text',
			'name'        => 'before',
			'label'       => __( "Before", 'codepress-admin-columns' ),
			'description' => __( 'This text will appear before the column value.', 'codepress-admin-columns' ),
		);
	}

	public function after_args() {
		return array(
			'type'        => 'text',
			'name'        => 'after',
			'label'       => __( "After", 'codepress-admin-columns' ),
			'description' => __( 'This text will appear after the column value.', 'codepress-admin-columns' ),
		);
	}

	public function before_after() {
		$this->fields( array(
			'label'  => __( 'Display Options', 'codepress-admin-columns' ),
			'fields' => array(
				$this->before_args(),
				$this->after_args(),
			),
		) );
	}

	public function date_args() {
		return array(
			'type'        => 'text',
			'name'        => 'date_format',
			'label'       => __( 'Date Format', 'codepress-admin-columns' ),
			'placeholder' => __( 'Example:', 'codepress-admin-columns' ) . ' d M Y H:i',
			'description' => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
			'help'        => sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>",
		);
	}

	public function date() {
		$this->field( $this->date_args() );
	}

	public function word_limit_args( $default_value = 30 ) {
		return array(
			'type'          => 'number',
			'name'          => 'excerpt_length',
			'label'         => __( 'Word Limit', 'codepress-admin-columns' ),
			'description'   => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'default_value' => $default_value,
		);
	}

	public function word_limit( $default_value = 30 ) {
		$this->field( $this->word_limit_args( $default_value ) );
	}

	public function character_limit_args() {
		return array(
			'type'        => 'number',
			'name'        => 'character_limit',
			'label'       => __( 'Character Limit', 'codepress-admin-columns' ),
			'description' => __( 'Maximum number of characters', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
		);
	}

	public function character_limit() {
		$this->field( $this->character_limit_args() );
	}

	public function user_args() {
		$nametypes = array(
			'display_name'    => __( 'Display Name', 'codepress-admin-columns' ),
			'first_name'      => __( 'First Name', 'codepress-admin-columns' ),
			'last_name'       => __( 'Last Name', 'codepress-admin-columns' ),
			'nickname'        => __( 'Nickname', 'codepress-admin-columns' ),
			'user_login'      => __( 'User Login', 'codepress-admin-columns' ),
			'user_email'      => __( 'User Email', 'codepress-admin-columns' ),
			'ID'              => __( 'User ID', 'codepress-admin-columns' ),
			'first_last_name' => __( 'First and Last Name', 'codepress-admin-columns' ),
		);

		natcasesort( $nametypes ); // sorts also when translated

		return array(
			'type'        => 'select',
			'name'        => 'display_author_as',
			'label'       => __( 'Display format', 'codepress-admin-columns' ),
			'options'     => $nametypes,
			'description' => __( 'This is the format of the author name.', 'codepress-admin-columns' ),
		);
	}

	public function user() {
		$this->field( $this->user_args() );
	}

	public function url_args() {
		return array(
			'type'        => 'text',
			'name'        => 'link_label',
			'label'       => __( 'Link label', 'codepress-admin-columns' ),
			'description' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
		);
	}

	public function user_link_to_args() {
		return array(
			'type'        => 'select',
			'name'        => 'user_link_to',
			'label'       => __( 'Link To', 'codepress-admin-columns' ),
			'options'     => array(
				''                => __( 'None' ),
				'edit_user'       => __( 'Edit User Profile' ),
				'view_user_posts' => __( 'View User Posts' ),
				'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' ),
				'email_user'      => __( 'User Email' ),
			),
			'description' => __( 'Page the author name should link to.', 'codepress-admin-columns' ),
		);
	}

	public function user_link_to() {
		$this->field( $this->user_link_to_args() );
	}

	public function url() {
		$this->field( $this->url_args() );
	}

	public function post_args( $default_value = 'title' ) {
		return array(
			'type'          => 'select',
			'name'          => 'post_property_display',
			'label'         => __( 'Property To Display', 'codepress-admin-columns' ),
			'options'       => array(
				'title'  => __( 'Title' ), // default
				'id'     => __( 'ID' ),
				'author' => __( 'Author' ),
			),
			'description'   => __( 'Post property to display for related post(s).', 'codepress-admin-columns' ),
			'default_value' => $default_value,
		);
	}

	public function post( $default_value = 'title' ) {
		$this->field( $this->post_args( $default_value ) );
	}

	public function post_link_to( $default_value = 'edit_post' ) {
		$this->field( array(
			'type'          => 'select',
			'name'          => 'post_link_to',
			'label'         => __( 'Link To', 'codepress-admin-columns' ),
			'options'       => array(
				''            => __( 'None' ),
				'edit_post'   => __( 'Edit Post' ),
				'view_post'   => __( 'View Post' ),
				'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
				'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
			),
			'description'   => __( 'Page the posts should link to.', 'codepress-admin-columns' ),
			'default_value' => $default_value,
		) );
	}

	public function words_per_minute_args() {
		return array(
			'type'          => 'text',
			'name'          => 'words_per_minute',
			'label'         => __( 'Words per minute', 'codepress-admin-columns' ),
			'description'   => __( 'Estimated reading time in words per minute', 'codepress-admin-columns' ),
			'placeholder'   => __( 'Enter words per minute. For example: 200' ),
			'default_value' => 200,
		);
	}

	public function words_per_minute() {
		$this->field( $this->words_per_minute_args() );
	}

	/**
	 * @since 2.4.7
	 */
	function placeholder( $args = array() ) {
		$defaults = array(
			'label' => '',
			'url'   => '',
			'type'  => '',
		);

		$data = (object) wp_parse_args( $args, $defaults );

		if ( ! $data->label ) {
			return;
		}
		?>
		<div class="is-disabled">
			<p>
				<strong><?php printf( __( "The %s column is only available in Admin Columns Pro - Business or Developer.", 'codepress-admin-columns' ), $data->label ); ?></strong>
			</p>

			<p>
				<?php printf( __( "If you have a business or developer licence please download & install your %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $data->label, admin_url( 'options-general.php?page=codepress-admin-columns&tab=addons' ) ); ?>
			</p>

			<p>
				<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $data->label, $data->label ); ?>
			</p>
			<a href="<?php echo add_query_arg( array(
				'utm_source'   => 'plugin-installation',
				'utm_medium'   => $data->type,
				'utm_campaign' => 'plugin-installation',
			), $data->url ); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>
		</div>
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

		$args['value'] = $this->get_option( $args['name'] );
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
	 */
	private function width_field() {
		?>
		<div class="description" title="<?php echo esc_attr( __( 'default', 'codepress-admin-columns' ) ); ?>">
			<input class="width" type="text" placeholder="<?php echo esc_attr( __( 'auto', 'codepress-admin-columns' ) ); ?>" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo esc_attr( $this->column->get_width() ); ?>"/>
			<span class="unit"><?php echo esc_html( $this->column->get_width_unit() ); ?></span>
		</div>
		<div class="width-slider"></div>

		<div class="unit-select">
			<?php
			ac_helper()->formfield->radio( array(
				'attr_id'       => $this->get_attr_id( 'width_unit' ),
				'attr_name'     => $this->get_attr_name( 'width_unit' ),
				'options'       => array(
					'px' => 'px',
					'%'  => '%',
				),
				'class'         => 'unit',
				'default_value' => $this->column->get_width_unit(),
			) );
			?>
		</div>
		<?php
	}

}