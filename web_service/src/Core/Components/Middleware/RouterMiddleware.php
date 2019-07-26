<?php

namespace RestfulWS\Core\Components\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RouterMiddleware.
 *
 * @package RestfulWS\Core\Components
 */
class RouterMiddleware extends Middleware implements MiddlewareInterface {

  /**
   * Handler.
   *
   * @param Request $request
   *   Request.
   *
   * @return Response|null
   *   Response or call next middleware.
   */
  public function handle(Request $request) {

    $routeMatch = $this->kernel
      ->getRouter()
      ->match($request);

    return call_user_func_array($routeMatch->getCallable(), $routeMatch->getArgs());
  }

}
