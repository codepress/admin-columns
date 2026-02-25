<?php

namespace AC;

use AC;
use InvalidArgumentException;

/**
 * Implements __call to work around any keyword restrictions for PHP versions > 7
 * @deprecated 7.0.11 Use Helper\{ClassName}::create() instead. Will be removed in 7.1.
 * @property Helper\Arrays   $array
 * @property Helper\Date     $date
 * @property Helper\Image    $image
 * @property Helper\Post     $post
 * @property Helper\Menu     $menu
 * @property Helper\Strings  $string
 * @property Helper\Taxonomy $taxonomy
 * @property Helper\User     $user
 * @property Helper\Icon     $icon
 * @property Helper\Html     $html
 * @property Helper\Media    $media
 * @property Helper\Network  $network
 * @property Helper\File     $file
 */
final class Helper
{

    public static function create(): self
    {
        return new self();
    }

    public function __get(string $helper)
    {
        switch ($helper) {
            case 'string' :
                return new AC\Helper\Strings();

            case 'array' :
                return new AC\Helper\Arrays();

            default :
                $class = 'AC\Helper\\' . ucfirst($helper);

                if (class_exists($class)) {
                    return new $class();
                }
        }

        throw new InvalidArgumentException('Invalid helper.');
    }

}