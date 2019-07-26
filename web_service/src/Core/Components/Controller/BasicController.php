<?php

namespace RestfulWS\Core\Components\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BasicController.
 *
 * @package Context
 */
class BasicController extends Controller {

  public function health() {

    $a = 1;
    return new JsonResponse([
      'health' => 'ok'
    ]);
  }

  public function notFound() {

    return new JsonResponse([
      'error' => 'Not found'
    ], 404);
  }

  public function exception() {

    return new JsonResponse([
      'error' => 'Internal server error.'
    ], 500);
  }

}
