<?php

namespace Tests\Unit;

use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Model\ModelInterface;
use Tests\ModelTestCase;

/**
 * Class ResourceRoutesTest.
 *
 * @package Tests\Unit
 */
class ResourceRoutesTest extends ModelTestCase {

  /**
   * Test for simple list collection.
   */
  public function testIndex() {

    $this->bookFactory->generate(3, TRUE);
    $res = $this->get('/api/books');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(3, $this->parseJson($res->getContent()));
  }

  /**
   * Test pagination on index pages.
   */
  public function testPagedIndex() {

    $this->bookFactory->generate(7, TRUE);
    $res = $this->get('/api/books');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(5, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books?page=1');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(5, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books?page=2');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(2, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books?page=3');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertEmpty($this->parseJson($res->getContent()));
    $res = $this->get('/api/books?page=-1');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(5, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books?page=non_numeric');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(5, $this->parseJson($res->getContent()));
  }

  /**
   * Test creation through api.
   */
  public function testCreate() {

    $book = $this->bookFactory->generate(1, FALSE);
    $this->assertEmpty(Book::getStorage()->count());
    $res = $this->post('/api/books', $this->toJson($book->getValues(TRUE)));
    $this->assertEquals(201, $res->getStatusCode());
    $this->assertEquals(1, $this->bookStorage->count());
    $book = $this->bookStorage->find(1);
    $this->assertInstanceOf(ModelInterface::class, $book);
  }

  /**
   * Test model update through api.
   */
  public function testUpdate() {

    $book = $this->bookFactory->generate(1, TRUE);
    $book->set('name', 'Changed name');
    $res = $this->patch('/api/books' . $book->getId(), $this->toJson($book->getValues(TRUE)));
    $this->assertEquals(204, $res->getStatusCode());
    $book = $this->bookStorage->find($book->getId());
    $this->assertEquals('Changed name', $book->get('name'));
  }

  /**
   * Test model update through api.
   */
  public function testDelete() {

    $this->assertEmpty($this->bookStorage->all());
    $book = $this->bookFactory->generate(1, TRUE);
    $this->assertNotEmpty($this->bookStorage->all());
    $res = $this->delete('/api/books' . $book->getId());
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertEmpty($this->bookStorage->all());
    $this->assertEmpty($this->bookStorage->find($book->getId()));
  }

}
