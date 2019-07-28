<?php

namespace Tests\Unit;

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

}
