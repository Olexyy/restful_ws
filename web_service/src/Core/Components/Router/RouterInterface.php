<?php

namespace RestfulWS\Core\Components\Router;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface RouterInterface.
 *
 * @package RestfulWS\Core\Components\Router
 */
interface RouterInterface {

  /**
   * Route match is conventional.
   *
   * @param Request $request
   *   Request.
   *
   * @return RouteMatch
   *   Route match.
   */
  public function match(Request $request);

  /**
   * @param array|Callable $onNotFound
   * @return Router
   */
  public function setOnNotFound($onNotFound);

  /**
   * @param array|Callable $onException
   * @return Router
   */
  public function setOnException($onException);

  /**
   * Not found route match getter.
   *
   * @return RouteMatchInterface
   *   Route match.
   */
  public function notFound();

  /**
   * Not found route match getter.
   *
   * @return RouteMatchInterface
   *   Route match.
   */
  public function exception();

  /**
   * Routes.
   *
   * @return ParameterBag
   */
  public function getRoutes();

  /**
   * Resources.
   *
   * @return ParameterBag
   */
  public function getResources();

}