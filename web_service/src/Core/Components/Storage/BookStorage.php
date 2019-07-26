<?php

namespace RestfulWS\Core\Components\Storage;

use RestfulWS\Core\Components\Field\Field;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Model\Category;

/**
 * Class BookStorage.
 *
 * @package RestfulWS\Core\Components\Storage
 */
class BookStorage extends Storage {

  protected $table = 'books';

  protected $modelClass = Book::class;

  /**
   * @return \RestfulWS\Core\Components\Field\FieldInterface[]
   */
  public function getSchema() {

    $fields = parent::getSchema();
    $fields[] = Field::create(
      'name', Field::NVARCHAR
    )->setNull();
    $fields[] = Field::create(
      'author', Field::NVARCHAR
    )->setNull();
    $fields[] = Field::create(
      'publisher', Field::NVARCHAR
    )->setNull();
    $fields[] = Field::create(
      'year', Field::INT
    )->setNull();
    $fields[] = Field::create(
      'words', Field::INT
    )->setNull();
    $fields[] = Field::create(
      'price', Field::DECIMAL
    )->setNull();
    $fields[] = Field::create(
      'categories', Field::COMPUTED
    )->setHasManyThrough('books_categories', Category::getStorage(), $this);

    return $fields;
  }

}
