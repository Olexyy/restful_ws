<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RestfulWS\App;
use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\KernelInterface;

/**
 * Class KernelTestCase.
 *
 * @package Tests
 */
abstract class KernelTestCase extends TestCase {

  /**
   * @var KernelInterface
   */
  protected $kernel;

  /**
   * {@inheritdoc}
   */
  public function setUp() : void {

    parent::setUp();
    $this->kernel = App::create();
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown(): void {

    parent::tearDown();
    $this->kernel->down();
  }

  /**
   * @return DatabaseInterface|null
   */
  public function getDatabase() {

    return $this->kernel->getDatabase();
  }

}
