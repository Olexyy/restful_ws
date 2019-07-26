<?php

namespace RestfulWS\Core\Components\Model;

use RestfulWS\Core\Components\Storage\CategoryStorage;

/**
 * Class Category.
 *
 * @package RestfulWS\Core\Components\Model
 */
class Category extends Model {

  public static function getStorage() {

    return static::$storage ?: new CategoryStorage();
  }
}
