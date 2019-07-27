<?php

namespace RestfulWS\Core\Components\Query;

use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Interface QueryBuilderInterface.
 *
 * @package RestfulWS\Core\Components\Query
 */
interface QueryBuilderInterface {

  public static function create(StorageInterface $storage);

  public function __construct(StorageInterface $storage);

  public function setLimit($limit);

  public function setOffset($offset);

  public function addWhere($field, $value = NULL, $operator = '=');

  public function getStatement();

  public function setWhereOperator($operator);

}
