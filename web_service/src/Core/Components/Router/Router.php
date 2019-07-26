<?

namespace RestfulWS\Core\Components\Router;

use RestfulWS\Core\Components\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Router.
 *
 * @package RestfulWS\Core\Components\Router
 */
class Router implements RouterInterface {

  /**
   * Routes.
   *
   * @var ParameterBag
   */
  protected $routes;

  /**
   *  Resources.
   *
   * @var ParameterBag
   */
  protected $resources;

  /**
   * Not found handler.
   *
   * @var array|Callable
   */
  protected $onNotFound;

  /**
   * Not found handler.
   *
   * @var array|Callable
   */
  protected $onException;

  /**
   * Router constructor.
   *
   * @param ParameterBag $routes
   *   Routes.
   * @param ParameterBag $resources
   *   Resources.
   * @param null|array $onNotFound
   *   Callable.
   * @param null|array $onException
   *   Callable.
   */
  public function __construct(ParameterBag $routes = NULL,
                              ParameterBag $resources = NULL,
                              $onNotFound = NULL,
                              $onException = NULL) {

    $this->routes = $routes ?: new ParameterBag();
    $this->resources = $resources ?: new ParameterBag();
    $this->onNotFound = $onNotFound;
    $this->onException = $onException;
  }

  /**
   * Route match is conventional.
   *
   * @param Request $request
   *   Request.
   *
   * @return RouteMatchInterface
   *   Route match.
   */
  public function match(Request $request) {

    try {
      $pathInfo = $request->getPathInfo();
      foreach ($this->routes as $path => $routeData) {
        $matches = [];
        if (preg_match($path, $pathInfo, $matches)) {
          array_shift($matches);
          $method = strtoupper($request->getMethod());
          if (!$routeData[2] || in_array($method, $routeData[2])) {
            $controller = new $routeData[0]();
            if ($controller instanceof Controller) {
              $controller->setRequest($request);

              return RouteMatch::create()
                ->setController($controller)
                ->setMethod($routeData[1])
                ->setArgs($matches);
            }
          }
        }
      }
      foreach ($this->resources as $path => $controller) {
        $matches = [];
        if (preg_match($path, $pathInfo, $matches)) {
          array_shift($matches);
          $matches = array_filter($matches);
          $method = strtolower($request->getMethod());
          $controller = new $controller();
          if ($controller instanceof Controller) {
            if (method_exists($controller, $method)) {
              $controller->setRequest($request);
              if (!$matches && $method == 'get') {
                $method = 'index';
              }

              return RouteMatch::create()
                ->setController($controller)
                ->setMethod($method)
                ->setArgs($matches);
            }
          }
        }
      }

      return $this->notFound();
    } catch (\Exception $exception) {

      return $this->exception();
    }
  }

  /**
   * @return RouteMatchInterface
   */
  protected function getRouteMatch() {

    return RouteMatch::create();
  }

  /**
   * Not found route match getter.
   *
   * @return RouteMatchInterface
   *   Route match.
   */
  public function notFound() {

    return RouteMatch::create()
      ->setController($this->onNotFound[0])
      ->setMethod($this->onNotFound[1]);
  }

  /**
   * Not found route match getter.
   *
   * @return RouteMatchInterface
   *   Route match.
   */
  public function exception() {

    return RouteMatch::create()
      ->setController($this->onException[0])
      ->setMethod($this->onException[1]);
  }

  /**
   * @param array|Callable $onNotFound
   * @return Router
   */
  public function setOnNotFound($onNotFound)
  {
    $this->onNotFound = $onNotFound;
    return $this;
  }

  /**
   * @param array|Callable $onException
   * @return Router
   */
  public function setOnException($onException)
  {
    $this->onException = $onException;
    return $this;
  }

  /**
   * Routes.
   *
   * @return ParameterBag
   */
  public function getRoutes() {

    return $this->routes;
  }

  /**
   * Resources.
   *
   * @return ParameterBag
   */
  public function getResources() {

    return $this->resources;
  }

}
