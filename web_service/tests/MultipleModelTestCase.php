<?php

namespace Tests;

use RestfulWS\Core\Components\Factory\BookFactory;
use RestfulWS\Core\Components\Factory\CategoryFactory;
use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Model\Category;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class MultipleModelTestCase.
 *
 * @package Tests
 */
abstract class MultipleModelTestCase extends KernelTestCase {

  /**
   * Generator.
   *
   * @var ModelFactoryInterface
   */
  protected $bookFactory;

  /**
   * Storage.
   *
   * @var StorageInterface
   */
  protected $bookStorage;

  /**
   * Generator
   *
   * @var ModelFactoryInterface
   */
  protected $categoryFactory;

  /**
   * Storage.
   *
   * @var StorageInterface
   */
  protected $categoryStorage;

  /**
   * {@inheritdoc}
   */
  public function setUp() : void {

    parent::setUp();
    $this->bookFactory = new BookFactory();
    $this->bookStorage = Book::getStorage();
    $this->categoryFactory = new CategoryFactory();
    $this->categoryStorage = Category::getStorage();
    $this->categoryStorage->dropSchema();
    $this->bookStorage->dropSchema();
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown(): void {

    parent::tearDown();
    $this->bookStorage->dropSchema();
    $this->categoryStorage->dropSchema();
  }

}