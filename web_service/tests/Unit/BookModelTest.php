<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Factory\BookFactory;
use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Storage\StorageInterface;
use Tests\ModelCrudTestCase;

/**
 * Class BookModelTest.
 *
 * @package Tests\Unit
 */
class BookModelTest extends ModelCrudTestCase {

  /**
   * Model factory.
   *
   * @return ModelFactoryInterface
   *   Model factory.
   */
  function getModelFactory() {

    return new BookFactory();
  }

  /**
   * Model storage.
   *
   * @return StorageInterface
   */
  function getModelStorage() {

    return Book::getStorage();
  }
}
