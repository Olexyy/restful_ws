<?php

namespace RestfulWS\Core;

use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\Components\Router\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface KernelInterface.
 *
 * @package RestfulWS\Core
 */
interface KernelInterface {

  /**
   * Getter for service.
   *
   * @param string $name
   *   Service name.
   *
   * @return mixed|null|object
   *   Service if any.
   */
  public function getService($name);

  /**
   * Handle incoming request through registered middleware.
   *
   * @param Request $request
   *   Current request.
   *
   * @return JsonResponse|Response
   *   Response.
   */
  public function handle(Request $request);

  /**
   * Config.
   *
   * @param string $name
   *   Variable name.
   * @param null $default
   *   Default value.
   *
   * @return mixed|null
   *   Variable value.
   */
  public function getConfig($name, $default = NULL);

  /**
   * Getter for database.
   *
   * @return null|DatabaseInterface
   *   Database.
   */
  public function getDatabase();

  /**
   * Getter for router.
   *
   * @return null|RouterInterface
   *   Router.
   */
  public function getRouter();

  /**
   * Tear down kernel.
   */
  public function down();

  /**
   * Boot kernel.
   *
   * @return $this
   *   Instance.
   */
  public static function up();

  /**
   * Route setter.
   *
   * @param string $path
   *   Path.
   * @param $controller
   *   Class definition.
   * @param $method
   *   Method.
   * @param array $methods
   *   Http methods.
   *
   * @return $this
   *   Chaining.
   */
  public function addRoute($path, $controller, $method, $methods = []);

  /**
   * Resource setter.
   *
   * @param string $path
   *   Path.
   * @param $controller
   *   Class definition.
   * @return $this
   *   Chaining.
   */
  public function addResource($path, $controller);

  /**
   * Service setter.
   *
   * @param string $name
   *   Name.
   * @param \Closure $closure
   *   Should return service.
   *
   * @return $this
   *   Chaining.
   */
  public function addService($name, \Closure $closure);

  /**
   * Middleware setter.
   *
   * @param string $middleware
   *   Class definition.
   *
   * @return $this
   *   Chaining.
   */
  public function addMiddleware($middleware);

  /**
   * Setter.
   *
   * @param string $controller
   *   Controller.
   * @param string $method
   *   Method.
   *
   * @return $this
   *   Chaining.
   */
  public function onException($controller, $method);

  /**
   * Setter.
   *
   * @param string $controller
   *   Controller.
   * @param $method
   *   Method.
   *
   * @return $this
   *   Chaining.
   */
  public function onNotFound($controller, $method);

}
