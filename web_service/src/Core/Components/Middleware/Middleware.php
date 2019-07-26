<?php

namespace RestfulWS\Core\Components\Middleware;

use RestfulWS\Core\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Middleware.
 *
 * @package RestfulWS\Core\Components
 */
class Middleware {

  /**
   * Kernel.
   *
   * @var KernelInterface
   */
  protected $kernel;

  /**
   * Next middleware.
   *
   * @var MiddlewareInterface|null
   */
  protected $next;

  /**
   * Middleware constructor.
   *
   * @param KernelInterface $kernel
   *   Kernel.
   * @param MiddlewareInterface|NULL $next
   *   Next handler.
   */
  public function __construct(KernelInterface $kernel, MiddlewareInterface $next = NULL) {

    $this->kernel = $kernel;
    $this->next = $next;
  }

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

    if ($this->next instanceof Middleware) {

      return $this->next->handle($request);
    }

    return NULL;
  }

}
