<?php

namespace RestfulWS\Core\Components\Router;

use RestfulWS\Core\Components\Controller\Controller;

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
   * Getter.
   *
   * @return Controller
   *   Controller.
   */
  public function getController();

  /**
   * Setter.
   *
   * @param Controller $controller
   *   Controller.
   *
   * @return $this
   *   Chaining.
   */
  public function setController($controller);

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getMethod();

  /**
   * Setter.
   *
   * @param mixed $method
   *   Method.
   *
   * @return $this
   *   Chaining.
   */
  public function setMethod($method);

  /**
   * Getter.
   *
   * @return array
   *   Value.
   */
  public function getArgs();

  /**
   * Setter.
   * @param mixed|array $args
   *   Args.
   *
   * @return $this
   *   Chaining.
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