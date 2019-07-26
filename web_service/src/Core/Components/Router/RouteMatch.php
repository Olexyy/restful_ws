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
   * Getter.
   *
   * @return Controller
   *   Controller.
   */
  public function getController() {

    return $this->controller;
  }

  /**
   * Setter.
   *
   * @param Controller $controller
   *   Controller.
   *
   * @return $this
   *   Chaining.
   */
  public function setController($controller) {

    $this->controller = $controller;
    return $this;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getMethod() {

    return $this->method;
  }

  /**
   * Setter.
   *
   * @param mixed $method
   *   Method.
   *
   * @return $this
   *   Chaining.
   */
  public function setMethod($method) {
    $this->method = $method;
    return $this;
  }

  /**
   * Getter.
   *
   * @return array
   *   Value.
   */
  public function getArgs() {
    return $this->args;
  }

  /**
   * Setter.
   * @param mixed|array $args
   *   Args.
   *
   * @return $this
   *   Chaining.
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
