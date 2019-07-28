<?php

namespace RestfulWS\Core\Components\Storage;

use RestfulWS\Core\Components\Database\DatabaseInterface;
use RestfulWS\Core\Components\Field\FieldInterface;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Query\QueryBuilderInterface;

/**
 * Interface StorageInterface.
 *
 * @package RestfulWS\Core\Components\Storage
 */
interface StorageInterface {

  /**
   * Storage constructor.
   *
   * @param DatabaseInterface|NULL $database
   */
  public function __construct(DatabaseInterface $database = NULL);

  /**
   * Find model.
   *
   * @param int|string $id
   *   Id to search.
   *
   * @return ModelInterface|null
   *   Model if any.
   */
  public function find($id);

  /**
   * Getter for references.
   *
   * @param ModelInterface $model
   *   Model.
   * @param $fieldName
   *   Field name.
   */
  public function getFieldValue(ModelInterface $model, $fieldName);

  /**
   * Models collection.
   *
   * @return array|ModelInterface[]
   */
  public function all();

  /**
   * Getter.
   *
   * @return array|string[]
   *   Fields.
   */
  public function getFieldNames();

  /**
   * Delete handler.
   *
   * @param ModelInterface $model
   *   Model.
   * @return bool
   *   Result.
   */
  public function delete(ModelInterface $model);

  /**
   * Delete handler.
   *
   * @return bool
   *   Result.
   */
  public function deleteAll();

  /**
   * Count query.
   *
   * @return int
   *   Result.
   */
  public function count();

  /**
   * Save handler.
   *
   * @param ModelInterface $model
   *   Model.
   *
   * @return bool
   *   Result.
   */
  public function save(ModelInterface $model);

  /**
   * Update handler.
   *
   * @param ModelInterface $model
   *   Model.
   *
   * @return bool
   *   Result.
   */
  public function update(ModelInterface $model);

  /**
   * Query handler.
   *
   * @param QueryBuilderInterface $query
   *   Search query.
   *
   * @return array|ModelInterface[]|int
   *   Collection of models.
   */
  public function where(QueryBuilderInterface $query);

  /**
   * Execute result.
   *
   * @return bool
   *   Result.
   */
  public function dropSchema();

  /**
   * Ensures schema.
   *
   * @return bool|int
   *   Result.
   */
  public function ensureSchema();

  /**
   * Schema getter.
   *
   * @return FieldInterface[]
   *   Fields collection.
   */
  public function getSchema();

  /**
   * Getter for field.
   *
   * @param string $name
   *   Name.
   *
   * @return FieldInterface|null
   */
  public function getField($name);

  /**
   * Table getter.
   *
   * @return string
   *   Value.
   */
  public function getTable();

  /**
   * Getter for model class name.
   *
   * @return string
   *   Value.
   */
  public function getModelClass();

  /**
   * Getter for query.
   *
   * @return QueryBuilderInterface
   *   Query builder.
   */
  public function getQuery();

}
