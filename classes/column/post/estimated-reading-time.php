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

		$this->properties['type'] = 'column-estimated_reading_time';
		$this->properties['label'] = __( 'Estimated Reading Time', 'codepress-admin-columns' );

		$this->options['words_per_minute'] = 200;
	}

	private function get_words_per_minute(  ) {
		return $this->get_option( 'words_per_minute' );
	}

	/**
	 * Estimate read time in readable format
	 *
	 * @see CPAC_Column::get_value()
	 * @since 2.3.3
	 */
	public function get_value( $post_id ) {
		$seconds = $this->get_raw_value( $post_id );

		return $seconds ? $this->convert_seconds_to_readable_time( $seconds ) : $this->get_empty_char();
	}

	/**
	 * Estimate read time in seconds
	 *
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.3
	 */
	public function get_raw_value( $post_id ) {
		return $this->get_estimated_reading_time_in_seconds( get_post_field( 'post_content', $post_id ) );
	}

	/**
	 * @since 2.3.3
	 */
	public function convert_seconds_to_readable_time( $seconds ) {
		$time = 0;

		if ( $seconds ) {

			$minutes = floor( $seconds / 60 );
			$seconds = floor( $seconds % 60 );

			$time = $minutes;
			if ( $minutes && $seconds < 10 ) {
				$seconds = '0' . $seconds;
			}
			if ( '00' != $seconds ) {
				$time .= ':' . $seconds;
			}
			if ( $minutes < 1 ) {
				$time = $seconds . ' ' . _n( 'second', 'seconds', $seconds, 'codepress-admin-columns' );
			}
			else {
				$time .= ' ' . _n( 'minute', 'minutes', $minutes, 'codepress-admin-columns' );
			}
		}

		return $time;
	}

	/**
	 * @since 2.3.3
	 */
	public function get_estimated_reading_time_in_seconds( $content ) {
		$word_count = $this->str_count_words( $this->strip_trim( $content ) );
		if ( ! $word_count ) {
			return 0;
		}

		return (int) floor( ( $word_count / $this->get_words_per_minute() ) * 60 );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.3.3
	 */
	public function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'editor' );
	}

	/**
	 * @since 2.3.3
	 */
	public function display_settings() {
		$field_key = 'words_per_minute';
		$label = __( 'Words per minute', 'codepress-admin-columns' );
		$description = __( 'Estimated reading time in words per minute', 'codepress-admin-columns' );

		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->get_words_per_minute(); ?>"/>
			</td>
		</tr>
		<?php
	}
}