<?php

namespace RestfulWS\Core\Components\Controller;

use RestfulWS\Core\Components\Model\Category;
use RestfulWS\Core\Components\Query\QueryAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CategoryController.
 *
 * @package RestfulWS\Core\Components\Controller
 */
class CategoryController extends ResourceController {

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function index() {

    $adapter = QueryAdapter::create(Category::getStorage()->getQuery(), $this->pageSize);

    return new JsonResponse($adapter->getQuery($this->request)->execute());
  }

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  public function get($id = NULL) {

    if ($book = Category::getStorage()->find($id)) {

      return new JsonResponse($book);
    }

    return NULL;
  }

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function post() {

    $data = json_decode($this->request->getContent(), TRUE);
    $book = Category::create($data);
    $book->save();

    return new JsonResponse($book, 201);
  }

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  public function patch($id = NULL) {

    if ($book = Category::getStorage()->find($id)) {
      $data = json_decode($this->request->getContent(), TRUE);
      $book->map($data);
      $book->save();

      return new JsonResponse($book, 204);
    }

    return NULL;
  }

  /**
   * Controller callback.
   *
   * @param null|int|string $id
   *   Resource id.
   *
   * @return JsonResponse
   *   Response.
   */
  public function delete($id = NULL) {

    if ($book = Category::getStorage()->find($id)) {
      $book->delete();

      return new JsonResponse(['result' => TRUE], 200);
    }

    return NULL;
  }
}