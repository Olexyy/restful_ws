<?php

namespace RestfulWS\Core\Components\Model;

use RestfulWS\Core\Components\Storage\BookStorage;

/**
 * Class Book.
 *
 * @package App\Components\Model
 */
class Book extends Model {

  public static function getStorage() {

    return static::$storage ?: new BookStorage();
  }

}
