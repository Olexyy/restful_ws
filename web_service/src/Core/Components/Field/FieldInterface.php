<?php

namespace RestfulWS\Core\Components\Field;

use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Interface FieldInterface.
 *
 * @package RestfulWS\Core\Components\Field
 */
interface FieldInterface {

  const INT = 'INT';

  const TEXT = 'TEXT';

  const NVARCHAR = 'NVARCHAR(255)';

  const SERIAL = 'SERIAL';

  const DECIMAL = 'DECIMAL(10,2)';

  const COMPUTED = 'computed';

  /**
   * Field constructor.
   *
   * @param string $name
   *   Field name.
   * @param string $type
   *   Field type.
   */
  public function __construct($name, $type);

  /**
   * Field inline constructor.
   *
   * @param string $name
   *   Field name.
   * @param string $type
   *   Field type.
   *
   * @return $this
   *   Instance.
   */
  public static function create($name, $type);

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getTable();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getModelTable();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getJoinTable();

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getName();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getType();

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setNull();

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setNotNull();

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setAutoIncrement();

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setUnique();

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setPrimary();

  /**
   * Setter.
   *
   * @param string $table
   *   Table name.
   * @param StorageInterface $joinStorage
   *   Storage of joined model.
   * @param StorageInterface $modelStorage
   *   Storage of this model.
   *
   * @return $this
   *   Chaining.
   */
  public function setHasManyThrough($table, StorageInterface $joinStorage, StorageInterface $modelStorage);

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getCreateStatement();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getConstraints();

  /**
   * Predicate.
   *
   * @return bool
   *   Result.
   */
  public function hasManyTrough();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getCreateIntermediateStatement();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getField();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getJoinField();

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getJoinClass();

}
