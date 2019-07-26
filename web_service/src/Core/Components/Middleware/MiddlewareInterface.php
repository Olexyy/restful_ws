<?php

namespace RestfulWS\Core\Components\Middleware;

use RestfulWS\Core\KernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface MiddlewareInterface.
 *
 * @package RestfulWS\Core\Components
 */
interface MiddlewareInterface {

  /**
   * MiddlewareInterface constructor.
   * @param KernelInterface $kernel
   * @param MiddlewareInterface|NULL $next
   */
  public function __construct(KernelInterface $kernel, MiddlewareInterface $next = NULL);

  /**
   * Handler.
   *
   * @param Request $request
   *   Request.
   *
   * @return Response|null
   *   Response or call next middleware.
   */
  public function handle(Request $request);

}
