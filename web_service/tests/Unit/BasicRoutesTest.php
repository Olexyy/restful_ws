<?php

namespace Tests\Unit;

use Tests\KernelTestCase;

class BasicRoutesTest extends KernelTestCase {

  /**
   * Test basic health route.
   */
  public function testHealth() {

    $res = $this->get('/');
    $this->assertEquals(200, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertEquals(['health' => 'ok'], $this->parseJson($res->getContent()));
  }

  /**
   * Not found route test.
   */
  public function testNotFound() {

    $res = $this->get('/non-existent');
    $this->assertEquals(404, $res->getStatusCode());
    $this->assertJson($res->getContent());
    $this->assertEquals(['error' => 'Not found'], $this->parseJson($res->getContent()));
  }
}