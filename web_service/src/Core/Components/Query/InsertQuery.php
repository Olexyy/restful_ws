<?php

namespace RestfulWS\Core\Components\Query;

use LogicException;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class InsertQuery.
 *
 * @package RestfulWS\Core\Components\Query
 */
class InsertQuery implements ModifyQueryInterface {

  /**
   * @var string
   */
  protected $statement;

  /**
   * @var array
   */
  protected $statementParams;

  /**
   * @var StorageInterface
   */
  protected $storage;

  /**
   * Inline constructor.
   *
   * @param StorageInterface $storage
   *
   * @return $this
   *   Instance.
   */
  public static function create(StorageInterface $storage) {

    return new static($storage);
  }

  /**
   * DeleteQuery constructor.
   *
   * @param StorageInterface $storage
   *   Storage.
   */
  public function __construct(StorageInterface $storage) {

    $this->storage = $storage;
    $this->statement = '';
    $this->statementParams = [];
  }

  /**
   * Getter for binding statement params.
   *
   * @param ModelInterface $model
   *   Given model.
   *
   * @return string
   *   Statement.
   *
   * @throws LogicException
   */
  public function getStatement(ModelInterface $model = NULL) {

    $this->statementParams = [];
    if (get_class($model) != $this->storage->getModelClass()) {
      throw new \LogicException(
        'Model does not relevant with instantiated storage.'
      );
    }
    $valuesArray = $this->getValues($model);
    $statement = <<<SQL
INSERT INTO {$this->storage->getTable()} ({$this->implodeForInsert($this->extractFieldNames($valuesArray))}) 
VALUES ({$this->implodeForInsert($this->extractFieldNames($valuesArray, TRUE))});
SQL;
    foreach ($valuesArray as $key => $value) {
      $this->statementParams[":{$key}"] = $value;
    }
    $this->statement = "$statement;";

    return $this->statement;
  }

  /**
   * Getter for references statement.
   *
   * @param ModelInterface $model
   *   Model.
   * @param string $fieldName
   *   Field name.
   *
   * @return string
   */
  public function getReferenceStatement(ModelInterface $model, $fieldName) {

    $this->statementParams = [];
    $statement = '';
    if (($field = $this->storage->getField($fieldName)) && $field->hasManyTrough()) {
      $values = $model->get($fieldName);
      foreach ($values as $i => $value) {
        $value = $value instanceof ModelInterface ? $value->getId() : $value;
        $statement .= <<<SQL
INSERT INTO {$field->getTable()} ({$field->getField()},{$field->getJoinField()}) 
VALUES (:{$field->getField()}_{$i},:{$field->getJoinField()}_{$i});
SQL;
        $this->statementParams[":{$field->getField()}_{$i}"] = $model->getId();
        $this->statementParams[":{$field->getJoinField()}_{$i}"] = $value;
      }
    }
    $this->statement = $statement;

    return $this->statement;
  }

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatementParams() {

    return $this->statementParams;
  }

  /**
   * Getter model values.
   *
   * @param ModelInterface $model
   *   Model.
   * @param array $exclude
   *   Fields to exclude.
   *
   * @return array
   *   Model values.
   */
  protected function getValues(ModelInterface $model, array $exclude = []) {

    $values = [];
    foreach ($this->storage->getSchema() as $field) {
      if (!$field->hasManyTrough()) {
        if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
          $values[$field->getName()] = $model->get($field->getName());
        }
      }
    }

    return $values;
  }

  /**
   * Internal helper.
   *
   * @param array $values
   *   Values.
   *
   * @return string
   *   Statement.
   */
  protected function implodeForInsert(array $values) {

    return implode(', ', $values);
  }

  /**
   * Internal helper.
   *
   * @param array $valuesArray
   *   Values from model.
   * @param bool $prefixed
   *   Flag define whether to prefix names.
   *
   * @return array
   *   Field names.
   */
  protected function extractFieldNames(array $valuesArray, $prefixed = FALSE) {

    $fieldNames = array_keys($valuesArray);
    if ($prefixed) {
      $fieldNames = array_map(function ($value) {
        return ':' . $value;
      }, $fieldNames);
    }

    return $fieldNames;
  }


}