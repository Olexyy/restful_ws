<?php

namespace Tests\Unit;

use Tests\ModelTestCase;

/**
 * Class SearchTest.
 *
 * @package Tests\Unit
 */
class SearchTest extends ModelTestCase {

  /**
   * Test for simple list collection.
   */
  public function testSearch() {

    $books = $this->bookFactory->generate(3, FALSE);
    $names = ['book1', 'book2', 'book3'];
    $authors = ['author1', 'author2', 'author3'];
    foreach ($books as $i => $book) {
      $book->set('name', $names[$i])
        ->set('author', $authors[$i])
        ->save();
    }
    $res = $this->get('/api/books');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(3, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books', ['filter' => [
      ['name' => 'name', 'value' => 'book1'],
    ]]);
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(1, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books', ['filter' => [
      ['name' => 'name', 'value' => ['book1', 'book2'], 'op' => 'IN'],
    ]]);
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(2, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books', ['filter' => [
      ['name' => 'name', 'value' => 'ook', 'op' => 'LIKE'],
    ]]);
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertCount(3, $this->parseJson($res->getContent()));
    $res = $this->get('/api/books', ['filter' => [
      ['name' => 'name', 'value' => 'non existent', 'op' => 'LIKE'],
    ]]);
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertEmpty($this->parseJson($res->getContent()));
  }

}
