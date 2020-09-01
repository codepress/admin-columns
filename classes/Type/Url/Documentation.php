<?php

namespace AC\Type\Url;

class Documentation {

	const URL = 'https://docs.admincolumns.com/';

	const ARTICLE_INLINE_EDITING = '/article/27-how-to-use-inline-editing';
	const ARTICLE_SORTING = '/article/34-how-to-enable-sorting';
	const ARTICLE_SMART_FILTERING = '/article/61-how-to-use-smart-filtering';
	const ARTICLE_BULK_EDITING = '/article/67-how-to-use-bulk-editing';
	const ARTICLE_EXPORT = '/article/69-how-to-use-export';
	const ARTICLE_QUICK_ADD = '/article/71-how-to-use-quick-add';
	const ARTICLE_COLUMN_SETS = '/article/72-how-to-create-column-sets';
	const ARTICLE_ENABLE_EDITING = '/article/68-enable-editing-for-custom-field-columns';
	const ARTICLE_UPGRADE_V3_TO_V4 = '/article/91-how-to-upgrade-from-v3-to-v4';
	const ARTICLE_SHOW_ALL_SORTING_RESULTS = '/article/86-show-all-results-when-sorting';
	const ARTICLE_ACTIONS_FILTERS = '/article/15-hooks-and-filters';

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param string $url
	 */
	public function __construct( $path = null ) {
		$this->url = self::URL . ltrim( $path, '/' );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}