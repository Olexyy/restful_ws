<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Factory\BookFactory;
use RestfulWS\Core\Components\Factory\CategoryFactory;
use RestfulWS\Core\Components\Factory\ModelFactoryInterface;
use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Model\Category;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;
use Tests\KernelTestCase;

/**
 * Class CategoryBookRelationTest.
 *
 * @package Tests\Unit
 */
class CategoryBookRelationTest extends KernelTestCase {

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
   * Test intermediate table creation.
   */
  public function testIntermediateTableCreation() {

    $this->categoryStorage->ensureSchema();
    $this->bookStorage->ensureSchema();
    $this->assertTrue($this->getDatabase()->tableExists('books_categories'));
    $category = $this->categoryFactory->generate()->save();
    $this->assertFalse($category->isNew());
    $book = $this->bookFactory->generate();
    $book->save();
    $this->assertFalse($category->isNew());
    $book->set('categories', [$category])->save();
    $book = $this->bookStorage->find($book->getId());
    $this->assertTrue($book->has('categories'));
    $relatedCategory = current($book->get('categories'));
    $this->assertInstanceOf(ModelInterface::class, $relatedCategory);
    $this->assertEquals($category->getId(), $relatedCategory->getId());
    $book->set('categories', [])->save();
    $book = $this->bookStorage->find($book->getId());
    $this->assertFalse($book->has('categories'));
    $secondCategory = $this->categoryFactory->generate()->save();
    $book->set('categories', [$category,$secondCategory])->save();
    $book = $this->bookStorage->find($book->getId());
    $this->assertTrue($book->has('categories'));
    $this->assertCount(2, $book->get('categories'));
  }

}
