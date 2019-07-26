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
   * @var Request
   */
  protected $request;

  /**
   * Setter.
   *
   * @param Request $request
   *   Request.
   */
  public function setRequest(Request $request) {

    $this->request = $request;
  }
  
}
