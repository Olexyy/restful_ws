<?php

namespace RestfulWS\Core\Components\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResourceController.
 *
 * @package RestfulWS\Core\Components\Controller
 */
abstract class ResourceController extends Controller {

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  abstract public function index();

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  abstract public function get($id = NULL);

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  abstract public function post();

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  abstract public function patch($id = NULL);

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  abstract public function delete($id = NULL);

}
