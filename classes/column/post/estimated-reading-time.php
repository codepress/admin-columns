<?php
/**
 * CPAC_Column_Post_Estimated_Reading_Time
 *
 * @since 2.3.3
 */
class CPAC_Column_Post_Estimated_Reading_Time extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.3.3
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-estimated_reading_time';
		$this->properties['label']	 = __( 'Estimated Reading Time', 'cpac' );

		// Options
		$this->options['words_per_minute'] = 200;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.3.3
	 */
	public function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.3
	 */
	public function get_raw_value( $post_id ) {

		return $this->get_estimated_reading_time( get_post_field( 'post_content', $post_id ) );
	}

	/**
	 * @since 2.3.3
	 */
	public function get_estimated_reading_time( $content ) {
		$word_count = $this->str_count_words( $this->strip_trim( $content ) );

		$m = floor( $word_count / $this->options->words_per_minute );
		$s = floor( $word_count % $this->options->words_per_minute / ( $this->options->words_per_minute / 60 ) );

		$time = $m;

		if ( $s < 10 ) {
			$s = '0' . $s;
		}
		if ( '00' != $s ) {
			$time .= ':' . $s;
		}
		if ( $m < 1 ) {
			$time = $s . ' ' . _n( 'second', 'seconds', $s, 'cpac' );
		}
		else {
			$time .= ' ' . _n( 'minute', 'minutes', $m, 'cpac' );
		}

		return $time;

		//return $m . ' ' . __( 'minute', 'cpac' ) . ( $m == 1 ? '' : 's' ) . ', ' . $s . ' ' . __( 'second', 'capc' ) . ( $s == 1 ? '' : 's' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.3.3
	 */
	public function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'editor' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @since 2.3.3
	 */
	public function display_settings() {

		$field_key		= 'words_per_minute';
		$label			= __( 'Words per minute', 'cpac' );
		$description	= __( 'Estimated reading time in words per minute', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->options->words_per_minute; ?>"/>
			</td>
		</tr>
	<?php
	}
}