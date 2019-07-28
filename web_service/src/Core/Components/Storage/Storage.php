<?php

namespace RestfulWS\Core\Components\Storage;

use \PDO;
use RestfulWS\Core\Components\Database\Database;
use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\Components\Field\Field;
use RestfulWS\Core\Components\Field\FieldInterface;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Query\DeleteQuery;
use RestfulWS\Core\Components\Query\InsertQuery;
use RestfulWS\Core\Components\Query\QueryBuilder;
use RestfulWS\Core\Components\Query\QueryBuilderInterface;
use RestfulWS\Core\Components\Query\UpdateQuery;

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

    if ($id) {
      $result = $this->getQuery()->addWhere('id', $id)->execute();

      return $result? current($result) : NULL;
    }

    return NULL;
  }

  /**
   * Models collection.
   *
   * @return array|ModelInterface[]
   */
  public function all() {

    return $this->getQuery()->execute();
  }

  /**
   * Count query.
   *
   * @return int
   *   Result.
   */
  public function count() {

    return $this->getQuery()->setIsCount(TRUE)->execute();
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

    $query = DeleteQuery::create($this);
    $stmt = $this->database->getConnection()
      ->prepare($query->getStatement($model));
    $params = $query->getStatementParams();
    foreach ($params as $key => $param) {
      $stmt->bindParam($key, $params[$key]);
    }
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

    $query = DeleteQuery::create($this);
    $stmt = $this->database->getConnection()->prepare($query->getStatement());

    return $stmt->execute();
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

    if (!$model->isNew()) {

      return $this->update($model);
    }
    $query = InsertQuery::create($this);
    $stmt = $this->database->getConnection()
      ->prepare($query->getStatement($model));
    $params = $query->getStatementParams();
    foreach ($params as $key => $param) {
      $stmt->bindParam($key, $params[$key]);
    }
    $result = $stmt->execute();
    $model->set('id', $this->database->getConnection()->lastInsertId());
    if ($references = $this->getReferenceForInsert($model)) {
      foreach ($references as $fieldName) {
        $stmt = $this->database->getConnection()->prepare($query->getReferenceStatement($model, $fieldName));
        $params = $query->getStatementParams();
        foreach ($params as $key => $param) {
          $stmt->bindParam($key, $params[$key]);
        }
        $stmt->execute();
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

    $query = UpdateQuery::create($this);
    $result = NULL;
    if ($statement = $query->getStatement($model)) {
      $stmt = $this->database->getConnection()
        ->prepare($query->getStatement($model));
      $params = $query->getStatementParams();
      foreach ($params as $key => $param) {
        $stmt->bindParam($key, $params[$key]);
      }
      $result = $stmt->execute();
    }
    if ($references = $this->getReferencesForUpdate($model)) {
      foreach ($references as $fieldName) {
        $stmt = $this->database->getConnection()->prepare($query->getReferenceStatement($model, $fieldName));
        $params = $query->getStatementParams();
        foreach ($params as $key => $param) {
          $stmt->bindParam($key, $params[$key]);
        }
        $stmt->execute();
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
   * @return array|ModelInterface[]|int
   *   Collection of models.
   */
  public function where(QueryBuilderInterface $query) {

    $stmt = $this->database->getConnection()->prepare($query->getStatement());
    $params = $query->getStatementParams();
    foreach ($params as $key => $value) {
      $stmt->bindParam($key, $params[$key]);
    }
    $stmt->execute();
    if ($query->isCount()) {
      $stmt->setFetchMode(\PDO::FETCH_BOTH);
      $res = $stmt->fetch();

      return (int) $res['count'];
    }
    else {
      $stmt->setFetchMode(PDO::FETCH_CLASS, $this->modelClass);

      return $stmt->fetchAll();
    }
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
   * Getter.
   *
   * @return array|string[]
   *   Fields.
   */
  public function getFieldNames() {

    return array_map(function (FieldInterface $field) {
      return $field->getName();
    },$this->getSchema());
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
   * Getter for model class name.
   *
   * @return string
   *   Value.
   */
  public function getModelClass() {

    return $this->modelClass;
  }

  /**
   * Getter for query.
   *
   * @return QueryBuilderInterface
   *   Query builder.
   */
  public function getQuery() {

    return QueryBuilder::create($this);
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
  protected function getReferenceForInsert(ModelInterface $model, array $exclude = []) {

    $values = [];
    foreach ($this->getSchema() as $field) {
      if ($field->hasManyTrough()) {
        if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
          $values[] = $field->getName();
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
  protected function getReferencesForUpdate(ModelInterface $model, array $exclude = []) {

    $values = [];
    $changes = $model->getChanged();
    foreach ($this->getSchema() as $field) {
      if (array_key_exists($field->getName(), $changes)) {
        if ($field->hasManyTrough()) {
          if ($model->has($field->getName()) && !in_array($field->getName(), $exclude)) {
            $values[] = $field->getName();
          }
        }
      }
    }

    return $values;
  }

}
