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

    if ($this->exception) {
      $error = $this->exception->getMessage() . $this->exception->getTraceAsString();

      return new JsonResponse([
        'notice' => 'Details are shown only in "dev" environment.',
        'error' => $error
      ], 500);
    }

    return new JsonResponse([
      'error' => 'Internal server error.'
    ], 500);
  }

}
