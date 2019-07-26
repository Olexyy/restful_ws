<?php

namespace RestfulWS;

use RestfulWS\Core\Components\Controller\BasicController;
use RestfulWS\Core\Components\Controller\BookController;
use RestfulWS\Core\Components\Controller\CategoryController;
use RestfulWS\Core\Components\Database\Database;
use RestfulWS\Core\Components\Middleware\RouterMiddleware;
use RestfulWS\Core\Components\Router\Router;
use RestfulWS\Core\Kernel;
use RestfulWS\Core\KernelBuilderInterface;
use RestfulWS\Core\KernelInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class App.
 *
 * @package RestfulWS
 */
class App implements KernelBuilderInterface {

  /**
   * Factory method.
   *
   * @return KernelInterface
   *   Instance.
   */
  public static function create() {

    return Kernel::up()
      ->addService('config', function () {
        return new ParameterBag($_ENV);
      })
      ->addService('router', function (KernelInterface $kernel) {
        return new Router();
      })
      ->addService('database', function (KernelInterface $kernel) {
        return Database::instance(
          $kernel->getConfig('DB_ROOT_USERNAME'),
          $kernel->getConfig('DB_ROOT_PASSWORD'),
          $kernel->getConfig('DB_HOST') . ':' . $kernel->getConfig('DB_PORT'),
          $kernel->getConfig('DB_NAME')
        );
      })
      ->addMiddleware(RouterMiddleware::class)
      ->addRoute('~^/$~', BasicController::class, 'health', ['GET'])
      ->addResource('~^/api/books/?(\d*)$~', BookController::class)
      ->addResource('~^/api/categories/?(\d*)$~', CategoryController::class)
      ->onException(BasicController::class, 'exception')
      ->onNotFound(BasicController::class, 'notFound');
  }

}
