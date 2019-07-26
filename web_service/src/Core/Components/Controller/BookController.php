<?php

namespace RestfulWS\Core\Components\Controller;

use RestfulWS\Core\Components\Model\Book;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BookController.
 *
 * @package RestfulWS\Core\Components\Controller
 */
class BookController extends ResourceController {

  public function index() {

    return new JsonResponse(Book::getStorage()->all());
  }

  public function get($id = NULL) {

    if ($book = Book::getStorage()->find($id)) {

      return new JsonResponse($book);
    }

    return NULL;
  }

  public function post() {

    $data = json_decode($this->request->getContent(), TRUE);
    $book = Book::create($data);
    $book->save();

    return new JsonResponse($book, 201);
  }

  public function patch($id = NULL) {

    if ($book = Book::getStorage()->find($id)) {
      $data = json_decode($this->request->getContent(), TRUE);
      $book->map($data);
      $book->save();

      return new JsonResponse($book, 201);
    }

    return NULL;
  }

  public function delete($id = NULL) {

    if ($book = Book::getStorage()->find($id)) {
      $book->delete();

      return new JsonResponse(['result' => TRUE], 201);
    }

    return NULL;
  }

}
