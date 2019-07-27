<?php

namespace RestfulWS\Core\Components\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Controller.
 *
 * @package Context
 */
abstract class Controller {

  /**
   * Current request.
   *
   * @var Request
   */
  protected $request;

  /**
   * Exception.
   *
   * @var \Exception
   */
  protected $exception;

  /**
   * Setter.
   *
   * @param Request $request
   *   Request.
   */
  public function setRequest(Request $request) {

    $this->request = $request;
  }

  /**
   * Setter.
   *
   * @param \Exception $exception
   *   Exception.
   */
  public function setException(\Exception $exception) {

    $this->exception = $exception;
  }
  
}
