<?php

namespace AC\Helper;

use DOMDocument;
use DOMElement;

class Html
{

	public function get_attribute_as_string(string $key, $value = null): string
	{
		return ac_helper()->string->is_not_empty($value)
				? sprintf('%s="%s"', $key, esc_attr(trim($value)))
				: $key;
	}

	public function get_style_attributes_as_string(array $attributes): string
	{
		$style = '';

		foreach ($attributes as $key => $value) {
			$style .= $key . ':' . $value . '; ';
		}

		return $style;
	}

	public function link(string $url, ?string $label = null, array $attributes = []): string
	{
		if ( ! $url) {
			return $label;
		}

		if (null === $label) {
			$label = urldecode($url);
		}

		if ( ! $label) {
			return '';
		}

		if ( ! $this->contains_html($label)) {
			$label = esc_html($label);
		}

		if (array_key_exists('tooltip', $attributes)) {
			$attributes['data-ac-tip'] = $attributes['tooltip'];

			unset($attributes['tooltip']);
		}

		$allowed = wp_allowed_protocols();
		$allowed[] = 'skype';
		$allowed[] = 'call';

		return sprintf(
				'<a href="%s" %s>%s</a>',
				esc_url($url, $allowed),
				$this->get_attributes($attributes),
				$label
		);
	}

	public function divider(): string
	{
		return '<span class="ac-divider"></span>';
	}

	public function get_tooltip_attr(string $content): string
	{
		if ( ! $content) {
			return '';
		}

		return 'data-ac-tip="' . esc_attr($content) . '"';
	}

	public function tooltip(string $label, string $tooltip, array $attributes = []): string
	{
		if (ac_helper()->string->is_not_empty($label) && $tooltip) {
			$label = sprintf(
					'<span %s %s>%s</span>',
					$this->get_tooltip_attr($tooltip),
					$this->get_attributes($attributes),
					$label
			);
		}

		return $label;
	}

	public function codearea(string $string, int $max_chars = 1000): string
	{
		if ( ! $string) {
			return false;
		}

		$contents = substr(
				$string,
				0,
				$max_chars
		);

		return '<textarea style="color: #808080; width: 100%; min-height: 60px;" readonly>' . $contents . '</textarea>';
	}

	private function get_attributes(array $attributes): string
	{
		$_attributes = [];

		foreach ($attributes as $attribute => $value) {
			if (in_array($attribute, ['title', 'id', 'class', 'style', 'target', 'rel', 'download']
				) || 'data-' === substr($attribute, 0, 5)) {
				$_attributes[] = $this->get_attribute_as_string($attribute, $value);
			}
		}

		return ' ' . implode(' ', $_attributes);
	}

	/**
	 * Returns an array with internal / external  links
	 */
	public function get_internal_external_links(string $string, array $internal_domains = []): ?array
	{
		if ( ! class_exists('DOMDocument')) {
			return null;
		}

		// Just do a very simple check to check for possible links
		if (false === strpos($string, '<a')) {
			return null;
		}

		if ( ! $internal_domains) {
			$internal_domains = [home_url()];
		}

		$internal_links = [];
		$external_links = [];

		$dom = new DOMDocument();

		libxml_use_internal_errors(true);
		$dom->loadHTML($string);
		libxml_clear_errors();

		$links = $dom->getElementsByTagName('a');

		foreach ($links as $link) {
			/**
			 * @var DOMElement $link
			 */
			$href = $link->getAttribute('href');

			if (0 === strpos($href, '#')) {
				continue;
			}

			$internal = false;

			foreach ($internal_domains as $domain) {
				if (false !== strpos($href, $domain)) {
					$internal = true;
				}
			}

			if ($internal) {
				$internal_links[] = $href;
			} else {
				$external_links[] = $href;
			}
		}

		if (empty($internal_links) && empty($external_links)) {
			return null;
		}

		return [
				$internal_links,
				$external_links,
		];
	}

	private function contains_html(string $string): bool
	{
		return $string && $string !== strip_tags($string);
	}

	public function implode(array $array, bool $divider = true): string
	{
		// Remove empty values
		$array = $this->remove_empty($array);

		if (true === $divider) {
			$divider = $this->divider();
		}

		return implode($divider, $array);
	}

	public function remove_empty(array $array): array
	{
		return array_filter($array, [ac_helper()->string, 'is_not_empty']);
	}

	/**
	 * Remove attribute from an html tag
	 */
	public function strip_attributes(string $html, array $attributes): string
	{
		if ($this->contains_html($html)) {
			foreach ($attributes as $attribute) {
				$html = (string)preg_replace('/(<[^>]+) ' . $attribute . '=".*?"/i', '$1', $html);
			}
		}

		return $html;
	}

	/**
	 * Small HTML block with grey background and rounded corners
	 */
	public function small_block(array $items): string
	{
		$blocks = [];

		foreach ($items as $item) {
			if ($item && is_scalar($item)) {
				$blocks[] = sprintf('<span class="ac-small-block">%s</span>', $item);
			}
		}

		return implode($blocks);
	}

	public function more(array $array, int $limit = 10, string $glue = ', '): string
	{
		if ($limit <= 0) {
			return implode($glue, $array);
		}

		$first_set = array_slice($array, 0, $limit);
		$last_set = array_slice($array, $limit);

		ob_start();

		if ($first_set) {
			$first = sprintf('<span class="ac-show-more__part -first">%s</span>', implode($glue, $first_set));
			$more = $last_set ? sprintf(
					'<span class="ac-show-more__part -more">%s%s</span>',
					$glue,
					implode($glue, $last_set)
			) : '';
			$content = sprintf('<span class="ac-show-more__content">%s%s</span>', $first, $more);
			$toggler = $last_set ? sprintf(
					'<span class="ac-show-more__divider">|</span><a class="ac-show-more__toggle" data-show-more-toggle data-more="%1$s" data-less="%2$s">%1$s</a>',
					sprintf(__('%s more', 'codepress-admin-columns'), count($last_set)),
					strtolower(__('Hide', 'codepress-admin-columns'))
			) : '';

			echo sprintf('<span class="ac-show-more">%s</span>', $content . $toggler);
		}

		return ob_get_clean();
	}

	/**
	 * Return round HTML span
	 */
	public function rounded(string $string): string
	{
		return sprintf('<span class="ac-rounded">%s</span>', $string);
	}

	/**
	 * Returns star rating based on X start from $max count. Does support decimals.
	 */
	public function stars(int $count, int $max = 0): string
	{
		$stars = [
				'filled' => floor($count),
				'half'   => floor(round(($count * 2)) - (floor($count) * 2)) ? 1 : 0,
				'empty'  => 0,
		];

		$max = absint($max);

		if ($max > 0) {
			$star_count = $stars['filled'] + $stars['half'];

			$stars['empty'] = $max - $star_count;

			if ($star_count > $max) {
				$stars['filled'] = $max;
				$stars['half'] = 0;
			}
		}

		$icons = [];

		foreach ($stars as $type => $_count) {
			for ($i = 1; $i <= $_count; $i++) {
				$icons[] = ac_helper()->icon->dashicon(['icon' => 'star-' . $type, 'class' => 'ac-value-star']);
			}
		}

		ob_start();
		?>
		<span class="ac-value-stars">
			<?php
			echo implode(' ', $icons); ?>
		</span>
		<?php
		return ob_get_clean();
	}

	public function images(string $html, ?int $removed = null): string
	{
		if ( ! $html) {
			return '';
		}

		if ($removed) {
			$html .= ac_helper()->html->rounded('+' . $removed);
		}

		return '<div class="ac-image-container">' . $html . '</div>';
	}

}