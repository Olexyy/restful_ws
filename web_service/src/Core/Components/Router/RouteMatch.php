<?php

namespace RestfulWS\Core\Components\Router;

use RestfulWS\Core\Components\Controller\Controller;

/**
 * Class RouteMatch.
 *
 * @package RestfulWS\Core\Components\Router
 */
class RouteMatch implements RouteMatchInterface {

  /**
   * Method.
   *
   * @var string
   */
  protected $method;

  /**
   * Args.
   *
   * @var array|string[]
   */
  protected $args = [];

  /**
   * Controller.
   *
   * @var Controller
   */
  protected $controller;

  /**
   * Constructor.
   *
   * @return RouteMatchInterface
   *   This.
   */
  public static function create() {

    return new static();
  }

  /**
   * @return mixed
   */
  public function getController()
  {
    return $this->controller;
  }

  /**
   * @param mixed $controller
   * @return RouteMatch
   */
  public function setController($controller)
  {
    $this->controller = $controller;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * @param mixed $method
   * @return RouteMatch
   */
  public function setMethod($method)
  {
    $this->method = $method;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getArgs()
  {
    return $this->args;
  }

  /**
   * @param mixed $args
   * @return RouteMatch
   */
  public function setArgs($args)
  {
    $this->args = $args;
    return $this;
  }

  /**
   * Callable array.
   *
   * @return array
   *   Array.
   */
  public function getCallable() {

    return [$this->getController(), $this->getMethod()];
  }

}
