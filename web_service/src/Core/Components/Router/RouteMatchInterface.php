<?php

namespace RestfulWS\Core\Components\Router;

/**
 * Interface RouteMatchInterface.
 *
 * @package RestfulWS\Core\Components\Router
 */
interface RouteMatchInterface {

  /**
   * Constructor.
   *
   * @return RouteMatchInterface
   *   This.
   */
  public static function create();

  /**
   * @return mixed
   */
  public function getController();

  /**
   * @param mixed $controller
   * @return RouteMatch
   */
  public function setController($controller);

  /**
   * @return mixed
   */
  public function getMethod();

  /**
   * @param mixed $method
   * @return RouteMatch
   */
  public function setMethod($method);

  /**
   * @return mixed
   */
  public function getArgs();

  /**
   * @param mixed $args
   * @return RouteMatch
   */
  public function setArgs($args);

  /**
   * Callable array.
   *
   * @return array
   *   Array.
   */
  public function getCallable();

}