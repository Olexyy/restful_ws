<?php

namespace RestfulWS\Core\Components\Model;

use RestfulWS\Core\Components\Storage\CategoryStorage;

/**
 * Class Category.
 *
 * @package RestfulWS\Core\Components\Model
 */
class Category extends Model {

  /**
   * Getter for storage.
   *
   * @return CategoryStorage|\RestfulWS\Core\Components\Storage\Storage
   *   Storage.
   */
  public static function getStorage() {

    return static::$storage ?: new CategoryStorage();
  }
}
