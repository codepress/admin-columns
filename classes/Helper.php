<?php

namespace AC;

use AC;

/**
 * Class AC\Helper
 * Implements __call to work around any keyword restrictions for PHP versions > 7
 * @property Helper\Arrays   array
 * @property Helper\Date     date
 * @property Helper\Image    image
 * @property Helper\Post     post
 * @property Helper\Menu     menu
 * @property Helper\Strings  string
 * @property Helper\Taxonomy taxonomy
 * @property Helper\User     user
 * @property Helper\Icon     icon
 * @property Helper\Html     html
 * @property Helper\Media    media
 * @property Helper\Network  network
 * @property Helper\File     file
 */
final class Helper
{

    public function __get($helper)
    {
        switch ($helper) {
            // Hotfix
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

        return false;
    }

}