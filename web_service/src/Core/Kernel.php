<?php

namespace RestfulWS\Core;

use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\Components\Router\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Kernel.
 *
 * @package Core\Kernel\Kernel
 */
class Kernel implements KernelInterface {

  /**
   * Middleware.
   *
   * @var ParameterBag
   */
  protected $middleware;

  /**
   * Services.
   *
   * @var ParameterBag
   */
  protected $services;

  /**
   * Boot kernel.
   *
   * @return $this
   *   Instance.
   */
  public static function up() {

    return new static();
  }

  /**
   * Kernel constructor.
   */
  public function __construct() {

    $this->boot();
  }

  /**
   * Boot up kernel.
   */
  protected function boot() {

    $this->middleware = [];
    $this->services = new ParameterBag();
  }

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
  public function addRoute($path, $controller, $method, $methods = []) {

    $this->getRouter()->getRoutes()->set($path, [$controller, $method, $methods = []]);

    return $this;
  }

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
  public function addResource($path, $controller) {

    $this->getRouter()->getResources()->set($path, $controller);

    return $this;
  }

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
  public function addService($name, \Closure $closure) {

    $this->services->set($name, $closure($this));

    return $this;
  }

  /**
   * Middleware setter.
   *
   * @param string $middleware
   *   Class definition.
   *
   * @return $this
   *   Chaining.
   */
  public function addMiddleware($middleware) {

    $this->middleware[] = $middleware;

    return $this;
  }

  /**
   * Handle incoming request through registered middleware.
   *
   * Uses middleware pattern.
   *
   * @param Request $request
   *   Current request.
   *
   * @return JsonResponse|Response
   *   Response.
   */
  public function handle(Request $request) {

    try {
      $instances = [];
      $previous = NULL;
      foreach ($this->middleware as $key => $middleware) {
        $instance = new $middleware($this, $previous);
        $instances[] = $instance;
        $previous = $instance;
      }
      $response = current($instances)->handle($request);
      if (!$response instanceof Response) {
        throw new \LogicException('Controller did not return response.');
      }

      return $response;
    } catch (\Exception $exception) {

      return $this->handleException($exception);
    }
  }

  /**
   * Tear down kernel.
   */
  public function down() {

    $this->getDatabase()->closeConnection();
  }

  /**
   * Getter for service.
   *
   * @param string $name
   *   Service name.
   *
   * @return mixed|null|object
   *   Service if any.
   */
  public function getService($name) {

    if ($this->services->has($name)) {

      return $this->services->get($name);
    }

    return NULL;
  }

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
  public function getConfig($name, $default = NULL) {

    if ($this->getService('config')->has($name)) {

      return $this->getService('config')->get($name);
    }

    return $default;
  }

  /**
   * Getter for database.
   *
   * @return null|DatabaseInterface
   *   Database.
   */
  public function getDatabase() {

    return $this->getService('database');
  }

  /**
   * Getter for router.
   *
   * @return null|RouterInterface
   *   Router.
   */
  public function getRouter() {

    return $this->getService('router');
  }

  /**
   * Handles unexpected situation during runtime.
   *
   * @param \Exception $exception
   *   Exception.
   *
   * @return JsonResponse
   *   Response.
   */
  protected function handleException(\Exception $exception) {

    $controller = $this->getRouter()
      ->exception()
      ->getController();
    $controller = new $controller();
    if ($this->getConfig('STAGE')  == 'dev') {
      $controller->setException($exception);
    }

    return call_user_func([$controller,
      $this->getRouter()
      ->exception()
      ->getMethod()
    ]);
  }

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
  public function onException($controller, $method) {

    $this->getRouter()->setOnException([$controller, $method]);

    return $this;
  }

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
  public function onNotFound($controller, $method) {

    $this->getRouter()->setOnNotFound([$controller, $method]);

    return $this;
  }

}
