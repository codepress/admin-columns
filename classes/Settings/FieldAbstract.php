<?php

abstract class AC_Settings_FieldAbstract {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $more_link;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var AC_Settings_FieldAbstract[]
	 */
	private $fields;

	/**
	 * @var AC_Settings_Form_ElementAbstract;
	 */
	private $form_element;

	public function __construct() {
		$this->fields = array();
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_more_link() {
		return $this->get_more_link;
	}

	/**
	 * @param string $more_link
	 *
	 * @return $this
	 */
	public function set_more_link( $more_link ) {
		$this->more_link = $more_link;

		return $this;
	}

	protected function display_wrapper() {
		$class = sprintf( 'widefat %s column-%s', $this->type, $this->name );

		if ( $this->get_hide() ) {
			$class .= ' hide';
		}

		$data_trigger = $this->toggle_trigger ? $this->format_attr( 'id', $this->toggle_trigger ) : '';
		$data_handle = $this->toggle_handle ? $this->format_attr( 'id', $this->toggle_handle ) : '';

		?>

		<table class="<?php echo esc_attr( $class ); ?>" data-trigger="<?php echo esc_attr( $data_trigger ); ?>" data-handle="<?php echo esc_attr( $data_handle ); ?>" data-refresh="<?php echo esc_attr( $this->refresh_column ); ?>">
			<tr>
				<?php

				$colspan = 2;

				// hide the label on the first field
				if ( $this->get_label() ) {
					$colspan = 1;

					$this->display_label();
				}

				?>

				<td class="input" colspan="<?php echo esc_attr( $colspan ); ?>">
					<?php $this->display_field(); ?>

					<?php if ( $this->get_help() ) : ?>
						<p class="help-msg">
							<?php echo $this->get_help(); ?>
						</p>
					<?php endif; ?>
				</td>
			</tr>
		</table>

		<?php
	}

	public function display_label() {
		$class = 'label';

		if ( $this->get_description() ) {
			$class .= ' description';
		}

		// todo: check the for conditional
		if ( ! $this->for ) {
			$this->for = $this->get_name();
		}

		?>

		<td class="<?php echo esc_attr( $class ); ?>">
			<label for="<?php esc_attr( $this->format_attr( 'id', $this->for ) ); ?>">
				<span class="label"><?php echo $this->get_label(); ?></span>
				<?php if ( $this->get_more_link() ) : ?>
					<a target="_blank" class="more-link" title="<?php esc_attr_e( 'View more', 'codepress-admin-columns' ); ?>" href="<?php echo esc_url( $this->get_more_link() ); ?>">
						<span class="dashicons dashicons-external"></span>
					</a>
				<?php endif; ?>
				<?php if ( $this->get_description() ) : ?>
					<p class="description"><?php echo $this->get_description(); ?></p>
				<?php endif; ?>
			</label>
		</td>

		<?php
	}

}