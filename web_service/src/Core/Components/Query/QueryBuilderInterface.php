<?php

namespace RestfulWS\Core\Components\Query;

use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Interface QueryBuilderInterface.
 *
 * @package RestfulWS\Core\Components\Query
 */
interface QueryBuilderInterface {

  /**
   * @param StorageInterface $storage
   *
   * @return $this
   *    Instance.
   */
  public static function create(StorageInterface $storage);

  /**
   * QueryBuilder constructor.
   *
   * @param StorageInterface $storage
   *   Storage.
   */
  public function __construct(StorageInterface $storage);

  /**
   * Setter.
   *
   * @param int $limit
   *   Value.
   *
   * @return $this
   *   Chaining.
   */
  public function setLimit($limit);

  /**
   * Setter.
   *
   * @param int $offset
   *   Value.
   *
   * @return $this
   *   Chaining.
   */
  public function setOffset($offset);

  /**
   * Builds query and returns statement.
   *
   * Now we support AND operator
   *
   * @return string
   *   Statement.
   */
  public function addWhere($field, $value = NULL, $operator = '=');

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatement();

  /**
   * Setter for operator in where conditions.
   *
   * @param string $operator
   *   Operator.
   *
   * @return $this
   *   Chaining.
   */
  public function setWhereOperator($operator);

}
