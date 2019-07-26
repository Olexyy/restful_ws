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
   * Route match process.
   *
   * @param Request $request
   *   Request.
   *
   * @return RouteMatch
   *   Route match.
   */
  public function match(Request $request);

  /**
   * Setter.
   *
   * @param array|Callable $onNotFound
   *   Value.
   *
   * @return $this
   *   Chaining.
   */
  public function setOnNotFound($onNotFound);

  /**
   * Setter.
   *
   * @param array|Callable $onException
   *   Value.
   *
   * @return $this
   *   Chaining.
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
   *   Collection.
   */
  public function getRoutes();

  /**
   * Resources.
   *
   * @return ParameterBag
   *   Collection.
   */
  public function getResources();

}