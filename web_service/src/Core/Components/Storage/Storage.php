<?php

namespace RestfulWS\Core\Components\Storage;

use \PDO;
use RestfulWS\Core\Components\Database\Database;
use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\Components\Field\Field;
use RestfulWS\Core\Components\Field\FieldInterface;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Query\QueryBuilderInterface;

/**
 * Class Storage.
 *
 * @package RestfulWS\Core\Components\Storage
 */
abstract class Storage implements StorageInterface {

  /**
   * @var DatabaseInterface
   */
  protected $database;

  /**
   * @var string
   */
  protected $table;

  /**
   * @var string
   */
  protected $modelClass;

  /**
   * Storage constructor.
   *
   * @param DatabaseInterface|NULL $database
   */
  public function __construct(DatabaseInterface $database = NULL) {

    $this->database = $database ?: Database::instance();
    $this->database->getConnection()->setAttribute(
      PDO::ATTR_ERRMODE,
      PDO::ERRMODE_EXCEPTION
    );
  }

  /**
   * Find model.
   *
   * @param int|string $id
   *   Id to search.
   *
   * @return ModelInterface|null
   *   Model if any.
   */
  public function find($id) {

    $stmt = $this->database->getConnection()->prepare(
      "SELECT * FROM {$this->table} WHERE id = :id"
    );
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, $this->modelClass);

    return $stmt->fetch();
  }

  /**
   * Getter for field value.
   *
   * Sets default empty value for it if not set yet.
   *
   * @param ModelInterface $model
   *   Model.
   * @param $fieldName
   *   Field name.
   */
  public function getFieldValue(ModelInterface $model, $fieldName) {

    if ($field = $this->getField($fieldName)) {
      if ($field->hasManyTrough()) {
        $model->set($field->getName(), [], FALSE);
        if ($id = $model->getId()) {
          $query = "SELECT * FROM {$field->getJoinTable()}
              JOIN {$field->getTable()}
              ON {$field->getTable()}.{$field->getJoinField()} = {$field->getJoinTable()}.id
              WHERE {$field->getTable()}.{$field->getField()} = :id";
          $stmt = $this->database->getConnection()->prepare($query);
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $stmt->setFetchMode(PDO::FETCH_CLASS, $field->getJoinClass());
          $models = $stmt->fetchAll();
          if ($models) {
            $model->set($field->getName(), $models, FALSE);
          }
        }
      }
      else {
        if (!$model->has($field->getName())) {
          $model->set($field->getName(), NULL, FALSE);
        }
      }
    }
  }

  /**
   * Models collection.
   *
   * @return array|ModelInterface[]
   */
  public function all() {

    $stmt = $this->database->getConnection()->prepare("SELECT * FROM {$this->table}");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, $this->modelClass);

    return $stmt->fetchAll();
  }

  /**
   * Getter.
   *
   * @return array|string[]
   *   Fields.
   */
  public function getFields() {

    return array_map(function (FieldInterface $field) {
      return $field->getName();
    },$this->getSchema());
  }

  /**
   * Delete handler.
   *
   * @param ModelInterface $model
   *   Model.
   * @return bool
   *   Result.
   */
  public function delete(ModelInterface $model) {

    if (!$model->getId()) {
      throw new \LogicException(
        'Model with no id cannot be deleted.'
      );
    }
    $id = $model->getId();
    $stmt = $this->database->getConnection()->prepare(
      <<<SQL
DELETE FROM {$this->table} WHERE id = :id;
SQL
    );
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();
    $model->remove('id');

    return $result;
  }

  /**
   * Delete handler.
   *
   * @return bool
   *   Result.
   */
  public function deleteAll() {

    $stmt = $this->database->getConnection()->prepare(
      <<<SQL
DELETE FROM {$this->table};
SQL
    );

    return $stmt->execute();
  }

  /**
   * Count query.
   *
   * @return int
   *   Result.
   */
  public function count() {

    $query = <<<SQL
SELECT COUNT(*) as count 
FROM {$this->table}
SQL;
    $stmt = $this->database->getConnection()->query($query);
    $stmt->setFetchMode(\PDO::FETCH_BOTH);
    $res = $stmt->fetch();

    return (int) $res['count'];
  }

  /**
   * Save handler.
   *
   * @param ModelInterface $model
   *   Model.
   *
   * @return bool
   *   Result.
   */
  public function save(ModelInterface $model) {

    if (get_class($model) != $this->modelClass) {
      throw new \LogicException(
        'Model does not relevant with instantiated storage.'
      );
    }
    if (!$model->isNew()) {

      return $this->update($model);
    }
    $valuesArray = $this->getValues($model);
    $query = <<<SQL
INSERT INTO {$this->table} ({$this->implodeForInsert($this->extractFieldNames($valuesArray))}) 
VALUES ({$this->implodeForInsert($this->extractFieldNames($valuesArray, TRUE))});
SQL;
    $stmt = $this->database->getConnection()->prepare($query);
    foreach ($valuesArray as $key => $value) {
      $stmt->bindParam(":{$key}", $valuesArray[$key]);
    }
    $result = $stmt->execute();
    $model->set('id', $this->database->getConnection()->lastInsertId());
    if ($references = $this->getReferenceValues($model)) {
      foreach ($references as $key => $values) {
        if (($field = $this->getField($key)) && $field->hasManyTrough()) {
          foreach ($values as $value) {
            $value = $value instanceof ModelInterface ? $value->getId() : $value;
            $stmt = $this->database->getConnection()->prepare(
              <<<SQL
INSERT INTO {$field->getTable()} ({$field->getField()},{$field->getJoinField()}) 
VALUES ({$model->getId()},{$value});
SQL
            );
            $stmt->bindParam(':id', $id);
            $stmt->execute();
          }
        }
      }
    }

    return $result;
  }

  /**
   * Update handler.
   *
   * @param ModelInterface $model
   *   Model.
   *
   * @return bool
   *   Result.
   */
  public function update(ModelInterface $model) {

    if (get_class($model) != $this->modelClass) {
      throw new \LogicException(
        'Model does not relevant with storage instantiated.'
      );
    }
    if ($model->isNew()) {
      throw new \LogicException(
        'Cannot update model without id.'
      );
    }
    $id = (int) $model->getId();
    $result = NULL;
    if ($valuesArray = $this->getChanged($model, ['id'])) {
      $stmt = $this->database->getConnection()->prepare(
        <<<SQL
UPDATE {$this->table} 
SET {$this->implodeForUpdate($valuesArray)} 
WHERE id = :id
SQL
      );
      foreach ($valuesArray as $key => $value) {
        $stmt->bindParam(":{$key}", $valuesArray[$key]);
      }
      $stmt->bindParam(':id', $id);
      $result = $stmt->execute();
    }
    if ($references = $this->getReferencesChanged($model)) {
      foreach ($references as $key => $values) {
        if (($field = $this->getField($key)) && $field->hasManyTrough()) {
          $stmt = $this->database->getConnection()->prepare(
<<<SQL
DELETE FROM {$field->getTable()}
WHERE {$field->getField()} = :id;
SQL
          );
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          foreach ($values as $index => $value) {
            $values[$index] = $value instanceof ModelInterface ? $value->getId() : $value;
            $query = <<<SQL
INSERT INTO {$field->getTable()} ({$field->getField()},{$field->getJoinField()}) 
VALUES (:id,:ref);
SQL;
            $stmt = $this->database->getConnection()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':ref', $values[$index]);
            $stmt->execute();
          }
        }
      }
    }

    return $result;
  }

  /**
   * Query handler.
   *
   * @param QueryBuilderInterface $query
   *   Search query.
   *
   * @return array|ModelInterface[]
   *   Collection of models.
   */
  public function where(QueryBuilderInterface $query) {

    $stmt = $this->database->getConnection()->prepare($query->getStatement());
    $params = $query->getStatementParams();
    foreach ($params as $key => $value) {
      $stmt->bindParam($key, $params[$key]);
    }
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_CLASS, $this->modelClass);

    return $stmt->fetchAll();
  }

  protected function extractFieldNames(array $valuesArray, $prefixed = FALSE) {

    $fieldNames = array_keys($valuesArray);
    if ($prefixed) {
      $fieldNames = array_map(function ($value) {
        return ':' . $value;
      }, $fieldNames);
    }

    return $fieldNames;
  }

  protected function extractFieldValues(array $valuesArray) {

    return array_values($valuesArray);
  }

  protected function implodeForInsert(array $values) {

    return implode(', ', $values);
  }

  protected function implodeForUpdate(array $valuesArray) {

    $fieldNames = array_keys($valuesArray);
    $fieldNames = array_map(function ($value) {
      return $value.' = :' . $value;
    }, $fieldNames);

    return implode(',', $fieldNames);
  }

  /**
   * Execute result.
   *
   * @return bool
   *   Result.
   */
  public function dropSchema() {

    $this->database->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach($this->getSchema() as $field) {
      if ($field->hasManyTrough()) {
        $query = "DROP TABLE IF EXISTS {$field->getTable()}";
        $this->database->getConnection()->exec($query);
      }
    }
    $query = "DROP TABLE IF EXISTS {$this->table}";
    $stmt = $this->database->getConnection()->prepare($query);
    $result =  $stmt->execute();
    $this->database->getConnection()->exec('SET FOREIGN_KEY_CHECKS = 1');

    return $result;
  }

  /**
   * Ensures schema.
   *
   * @return bool|int
   *   Result.
   */
  public function ensureSchema() {

    if (!$this->database->tableExists($this->table)) {
      $query = "CREATE TABLE {$this->table} (";
      foreach($this->getSchema() as $field) {
        if (!$field->hasManyTrough()) {
          $query .= "{$field->getCreateStatement()},";
        }
      }
      foreach($this->getSchema() as $field) {
        if (!$field->hasManyTrough() && $constraints = $field->getConstraints()) {
          $query .= "{$constraints},";
        }
      }
      $query = substr_replace($query, ")", -1);
      $result = $this->database->getConnection()->exec($query);
      foreach($this->getSchema() as $field) {
        if ($field->hasManyTrough()) {
          if ($this->database->tableExists($field->getJoinTable())) {
            $this->database->getConnection()->exec($field->getCreateIntermediateStatement());
          }
        }
      }

      return $result;
    }

    return FALSE;
  }

  /**
   * Schema getter.
   *
   * @return FieldInterface[]
   *   Fields collection.
   */
  public function getSchema() {

    $fields = [];
    $fields[] = Field::create(
      'id', Field::SERIAL
    )->setPrimary();

    return $fields;
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
    foreach ($this->getSchema() as $field) {
      if (!$field->hasManyTrough()) {
        if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
          $values[$field->getName()] = $model->get($field->getName());
        }
      }
    }

    return $values;
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
  protected function getChanged(ModelInterface $model, array $exclude = []) {

    $values = [];
    $changes = $model->getChanged();
    foreach ($this->getSchema() as $field) {
      if (array_key_exists($field->getName(), $changes)) {
        if (!$field->hasManyTrough()) {
          if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
            $values[$field->getName()] = $model->get($field->getName());
          }
        }
      }
    }

    return $values;
  }

  /**
   * Getter model field reference.
   *
   * @param ModelInterface $model
   *   Model.
   * @param array $exclude
   *   Fields to exclude.
   *
   * @return array
   *   Model values.
   */
  protected function getReferenceValues(ModelInterface $model, array $exclude = []) {

    $values = [];
    foreach ($this->getSchema() as $field) {
      if ($field->hasManyTrough()) {
        if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
          $values[$field->getName()] = $model->get($field->getName());
        }
      }
    }

    return $values;
  }

  /**
   * Getter model field reference.
   *
   * @param ModelInterface $model
   *   Model.
   * @param array $exclude
   *   Fields to exclude.
   *
   * @return array
   *   Model values.
   */
  protected function getReferencesChanged(ModelInterface $model, array $exclude = []) {

    $values = [];
    $changes = $model->getChanged();
    foreach ($this->getSchema() as $field) {
      if (array_key_exists($field->getName(), $changes)) {
        if ($field->hasManyTrough()) {
          if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
            $values[$field->getName()] = $model->get($field->getName());
          }
        }
      }
    }

    return $values;
  }

  /**
   * Getter for field.
   *
   * @param string $name
   *   Name.
   *
   * @return FieldInterface|null
   */
  public function getField($name) {

    foreach ($this->getSchema() as $field) {
      if ($field->getName() == $name) {

        return $field;
      }
    }

    return NULL;
  }

  /**
   * Table getter.
   *
   * @return string
   *   Value.
   */
  public function getTable() {

    return $this->table;
  }

  /**
   * @return string
   */
  public function getModelClass() {

    return $this->modelClass;
  }

}
