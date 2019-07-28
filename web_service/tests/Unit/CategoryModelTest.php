<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Factory\CategoryFactory;
use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\Category;
use RestfulWS\Core\Components\Storage\StorageInterface;
use Tests\ModelCrudTestCase;

/**
 * Class CategoryModelTest.
 *
 * @package Tests\Unit
 */
class CategoryModelTest extends ModelCrudTestCase {

  /**
   * Model factory.
   *
   * @return ModelFactoryInterface
   *   Model factory.
   */
  function getModelFactory() {

    return new CategoryFactory();
  }

  /**
   * Model storage.
   *
   * @return StorageInterface
   */
  function getModelStorage() {

    return Category::getStorage();
  }

}
