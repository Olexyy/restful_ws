<?php

namespace RestfulWS\Core\Components\Controller;

use RestfulWS\Core\Components\Model\Book;
use RestfulWS\Core\Components\Query\QueryAdapter;
use RestfulWS\Core\Components\Query\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BookController.
 *
 * @package RestfulWS\Core\Components\Controller
 */
class BookController extends ResourceController {

  /**
   * Controller callback.
   *
   * @return JsonResponse
   *   Response.
   */
  public function index() {

    $adapter = QueryAdapter::create(QueryBuilder::create(Book::getStorage()), 5);
    $query = $adapter->getQuery($this->request);

    return new JsonResponse(Book::getStorage()->where($query));
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

    if ($book = Book::getStorage()->find($id)) {

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
    $book = Book::create($data);
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

    if ($book = Book::getStorage()->find($id)) {
      $data = json_decode($this->request->getContent(), TRUE);
      $book->map($data);
      $book->save();

      return new JsonResponse($book, 201);
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

    if ($book = Book::getStorage()->find($id)) {
      $book->delete();

      return new JsonResponse(['result' => TRUE], 201);
    }

    return NULL;
  }

}
