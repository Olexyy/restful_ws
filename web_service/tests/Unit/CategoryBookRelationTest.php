<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Model\ModelInterface;
use Tests\ModelTestCase;

/**
 * Class CategoryBookRelationTest.
 *
 * @package Tests\Unit
 */
class CategoryBookRelationTest extends ModelTestCase {

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
    $this->assertFalse($book->has('categories'));
    $this->assertNotEmpty($book->get('categories'));
    $relatedCategory = current($book->get('categories'));
    $this->assertInstanceOf(ModelInterface::class, $relatedCategory);
    $this->assertEquals($category->getId(), $relatedCategory->getId());
    $book->set('categories', [])->save();
    $book = $this->bookStorage->find($book->getId());
    $this->assertFalse($book->has('categories'));
    $secondCategory = $this->categoryFactory->generate()->save();
    $book->set('categories', [$category,$secondCategory])->save();
    $book = $this->bookStorage->find($book->getId());
    $this->assertFalse($book->has('categories'));
    $this->assertNotEmpty($book->get('categories'));
    $this->assertCount(2, $book->get('categories'));
  }

}
