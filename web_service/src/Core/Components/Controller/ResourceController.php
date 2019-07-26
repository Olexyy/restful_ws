<?php

namespace RestfulWS\Core\Components\Controller;

use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class ResourceController.
 *
 * @package RestfulWS\Core\Components\Controller
 */
abstract class ResourceController extends Controller {

  abstract public function index();

  abstract public function get($id = NULL);

  abstract public function post();

  abstract public function patch($id = NULL);

  abstract public function delete($id = NULL);

}
