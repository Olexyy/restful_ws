<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use RestfulWS\App;
use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\KernelInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class KernelTestCase.
 *
 * @package Tests
 */
abstract class KernelTestCase extends TestCase {

  /**
   * App kernel.
   *
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
   * Database getter.
   *
   * @return DatabaseInterface|null
   */
  public function getDatabase() {

    return $this->kernel->getDatabase();
  }

  /**
   * Executes request to kernel.
   *
   * @param string $url
   *   Url or path.
   * @param string $method
   *   Method.
   * @param array $params
   *   Get or Post params.
   * @param null|string|resource $content
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
   *   Kernel response.
   */
  public function call($url, $method = 'GET', $params = [], $content = NULL) {

    $request = Request::create($url, $method, $params, [], [], [], $content);

    return $this->kernel->handle($request);
  }

  /**
   * Executes GET request to kernel.
   *
   * @param string $url
   *   Url or path.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
   *   Kernel response.
   */
  public function get($url) {

    return $this->call($url);
  }

  /**
   * Parses json.
   *
   * @param string $json
   *   Json.
   *
   * @return false|mixed
   *   Result.
   */
  public function parseJson($json) {

    return json_decode($json, TRUE);
  }

  /**
   * Creates json.
   *
   * @param mixed $data
   *   Data.
   *
   * @return false|string
   *   Json string.
   */
  public function toJson($data) {

    return json_encode($data);
  }

}
