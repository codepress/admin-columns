<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeConfigurator\FieldComponentDirectorFactory;
use AC\Setting\Config;

/**
 * The Field Type setting with every field type enabled. Kept as a class of its own because the
 * TYPE_* constants are referenced throughout the plugin and the addons; the setting itself is
 * assembled by the configurators. Use FieldComponentDirectorFactory directly to offer a subset.
 */
class FieldType implements ComponentFactory
{
    public const TYPE_DEFAULT = '';
    public const TYPE_HTML = 'html';
    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'checkmark';
    public const TYPE_COLOR = 'color';
    public const TYPE_COUNT = 'count';
    public const TYPE_DATE = 'date';
    public const TYPE_IMAGE = 'image';
    public const TYPE_MEDIA = 'library_id';
    public const TYPE_NON_EMPTY = 'has_content';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPE_POST = 'title_by_id';
    public const TYPE_TEXT = 'excerpt';
    public const TYPE_URL = 'link';
    public const TYPE_USER = 'user_by_id';

    public const TYPE_SELECT = 'select';

    private FieldComponentDirectorFactory $director;

    public function __construct(FieldComponentDirectorFactory $director)
    {
        $this->director = $director;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        return $this->director->with_all()->create($config, $conditions);
    }

}
