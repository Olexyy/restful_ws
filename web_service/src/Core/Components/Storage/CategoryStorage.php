<?php

namespace RestfulWS\Core\Components\Storage;

use RestfulWS\Core\Components\Field\Field;
use RestfulWS\Core\Components\Model\Category;

/**
 * Class CategoryStorage.
 *
 * @package RestfulWS\Core\Components\Storage
 */
class CategoryStorage extends Storage {

  protected $table = 'categories';

  protected $modelClass = Category::class;

  /**
   * @return \RestfulWS\Core\Components\Field\FieldInterface[]
   */
  public function getSchema() {

    $fields = parent::getSchema();
    $fields[] = Field::create(
      'name',
      Field::NVARCHAR
    )->setNull();

    return $fields;
  }
}
