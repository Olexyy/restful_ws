<?php

namespace RestfulWS\Core\Components\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BasicController.
 *
 * @package Context
 */
class BasicController extends Controller {

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function health() {

    return new JsonResponse([
      'health' => 'ok'
    ]);
  }

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function notFound() {

    return new JsonResponse([
      'error' => 'Not found'
    ], 404);
  }

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function exception() {

    return new JsonResponse([
      'error' => 'Internal server error.'
    ], 500);
  }

}
