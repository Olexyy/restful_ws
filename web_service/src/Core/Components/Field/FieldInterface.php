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
   * @param $name
   * @param $type
   */
  public function __construct($name, $type);

  /**
   * @param $name
   * @param $type
   *
   * @return Field
   */
  public static function create($name, $type);

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getTable();

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getModelTable();

  /**
   * Getter.
   *
   * @return mixed
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
   * @return mixed
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
   * @param StorageInterface $joinStorage
   * @param StorageInterface $modelStorage
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
   *
   * @return string
   */
  public function getCreateIntermediateStatement();

  /**
   * Getter.
   *
   * @return string
   */
  public function getField();

  /**
   * Getter.
   *
   * @return string
   */
  public function getJoinField();

  /**
   * @return string
   */
  public function getJoinClass();

}
