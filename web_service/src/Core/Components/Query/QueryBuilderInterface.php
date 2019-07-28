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
   * Add where condition.
   *
   * @param string $field
   *   Field name.
   * @param mixed $value
   *   Value.
   * @param string $operator
   *   Operator.
   *
   * @return $this
   *   Chaining.
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

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatementParams();

  /**
   * Executes query.
   *
   * @return array|ModelInterface[]|int
   *   Execution result.
   */
  public function execute();

  /**
   * Predicate.
   *
   * @return bool
   *   Value.
   */
  public function isCount();

  /**
   * Is count query.
   *
   * @param bool $isCount
   *   Flag.
   *
   * @return $this
   *   Chaining.
   */
  public function setIsCount($isCount);

}
