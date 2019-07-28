<?php

namespace RestfulWS\Core\Components\Query;

use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class QueryBuilder.
 *
 * @package RestfulWS\Core\Components\Query
 */
class QueryBuilder implements QueryBuilderInterface {

  /**
   * @var StorageInterface
   */
  protected $storage;

  /**
   * @var int
   */
  protected $limit;

  /**
   * @var int
   */
  protected $offset;

  /**
   * @var array
   */
  protected $where;

  /**
   * @var string
   */
  protected $whereOperator;

  /**
   * @var string
   */
  protected $statement;

  /**
   * @var array
   */
  protected $statementParams;

  /**
   * @param StorageInterface $storage
   *
   * @return $this
       Instance.
   */
  public static function create(StorageInterface $storage) {

    return new static($storage);
  }

  /**
   * QueryBuilder constructor.
   *
   * @param StorageInterface $storage
   *   Storage.
   */
  public function __construct(StorageInterface $storage) {

    $this->storage = $storage;
    $this->where = [];
    $this->limit = 0;
    $this->offset = 0;
    $this->whereOperator = 'AND';
    $this->statement = '';
    $this->statementParams = [];
  }

  /**
   * Setter.
   *
   * @param int $limit
   *   Value.
   *
   * @return $this
   *   Chaining.
   */
  public function setLimit($limit) {

    $this->limit = $limit;

    return $this;
  }

  /**
   * Setter.
   *
   * @param int $offset
   *   Value.
   *
   * @return $this
   *   Chaining.
   */
  public function setOffset($offset) {

    $this->offset = $offset;

    return $this;
  }

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
  public function addWhere($field, $value = NULL, $operator = '=') {

    $this->where[] = [$field, $value, $operator];

    return $this;
  }

  /**
   * Builds query and returns statement.
   *
   * Now we support AND operator
   *
   * @return string
   *   Statement.
   */
  public function getStatement() {

    $this->statementParams = [];
    $table = $this->storage->getTable();
    $statement = "SELECT * FROM {$table}";
    $whereStatements = [];
    $joinStatements = [];
    foreach ($this->where as $where) {
      list($fieldName, $operator, $values) = $this->processWhere($where);
      $this->statementParams = array_merge($this->statementParams, $values);
      $placeholders = array_keys($values);
      $value = (count($placeholders) == 1) ? current($placeholders) : '(' . implode(', ', $placeholders) . ')';
      if ($field = $this->storage->getField($fieldName)) {
        if ($field->hasManyTrough()) {
          $joinStatements[] = "JOIN {$field->getTable()} ON {$field->getTable()}.{$field->getField()} = {$table}.id";
          $whereStatements[] = "{$field->getTable()}.{$field->getJoinField()} {$operator} {$value}";
        }
        else {
          $whereStatements[] = "{$table}.{$fieldName} {$operator} {$value}";
        }
      }
    }
    if ($joinStatements) {
      foreach ($joinStatements as $joinStatement) {
        $statement .= " {$joinStatement}";
      }
    }
    if ($whereStatements) {
      $whereStatement = implode(" {$this->whereOperator} ", $whereStatements);
      $statement .= " WHERE {$whereStatement}";
    }
    if (!empty($this->limit)) {
      $statement .= " LIMIT {$this->limit} OFFSET {$this->offset}";
    }

    return "$statement;";
  }

  /**
   * Process where clause.
   *
   * @param array $data
   *   Data to process.
   *
   * @return array
   *   Values.
   */
  protected function processWhere(array $data) {

    $fieldName = $data[0];
    $value = (array) $data[1];
    $values = [];
    foreach ($value as $i => $item) {
      $placeholder = ":{$fieldName}_{$i}";
      $up = $i;
      while (array_key_exists($placeholder, $this->statementParams)) {
        $up ++;
        $placeholder = ":{$fieldName}_{$up}";
      }
      $values[$placeholder] = $item;
    }
    $operator = $data[2];
    if ($operator == "LIKE") {
      foreach ($values as &$item) {
        $item = "%{$item}%";
      }
    }

    return [$fieldName, $operator, $values];
  }

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatementParams() {

    if (!$this->statement) {
      $this->getStatement();
    }

    return $this->statementParams;
  }

  /**
   * Setter for operator in where conditions.
   *
   * @param string $operator
   *   Operator.
   *
   * @return $this
   *   Chaining.
   */
  public function setWhereOperator($operator) {

    $this->whereOperator = $operator;

    return $this;
  }

  /**
   * Executes query.
   *
   * @return array|ModelInterface[]
   *   Execution result.
   */
  public function execute() {

    return $this->storage->where($this);
  }

}
