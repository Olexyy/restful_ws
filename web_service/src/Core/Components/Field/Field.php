<?php

namespace RestfulWS\Core\Components\Field;

use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class Field.
 *
 * @package RestfulWS\Core\Components\Field
 */
class Field implements FieldInterface {

  /**
   * @var string
   */
  protected $name;

  /**
   * @var bool
   */
  protected $hasManyTrough;

  /**
   * @var string
   */
  protected $table;

  /**
   * @var StorageInterface
   */
  protected $modelStorage;

  /**
   * @var StorageInterface
   */
  protected $joinStorage;

  /**
   * @var string
   */
  protected $type;

  /**
   * @var array
   */
  protected $settings;

  /**
   * Field constructor.
   *
   * @param $name
   * @param $type
   */
  public function __construct($name, $type) {

    $this->name = $name;
    $this->type = $type;
    $this->settings = [];
  }

  /**
   * @param $name
   * @param $type
   *
   * @return Field
   */
  public static function create($name, $type) {

    return new static($name, $type);
  }

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getTable() {

    return $this->table;
  }

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getModelTable() {

    return $this->modelStorage->getTable();;
  }

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getJoinTable() {

    return $this->joinStorage->getTable();
  }


  /**
   * Getter.
   *
   * @return mixed
   */
  public function getName() {

    return $this->name;
  }

  /**
   * Getter.
   *
   * @return mixed
   */
  public function getType() {

    return $this->type;
  }

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setNull() {

    $this->settings[]['create'] = 'NULL';

    return $this;
  }

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setNotNull() {

    $this->settings[]['create'] = 'NOT NULL';

    return $this;
  }

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setAutoIncrement() {

    $this->settings['create'][] = 'AUTO_INCREMENT';

    return $this;
  }

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setUnique() {

    $this->settings['create'][] = 'UNIQUE';

    return $this;
  }

  /**
   * Setter.
   *
   * @return $this
   *   Chaining.
   */
  public function setPrimary() {

    $this->settings['constraint'][] = "PRIMARY KEY ({$this->name})";

    return $this;
  }

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
  public function setHasManyThrough($table, StorageInterface $joinStorage, StorageInterface $modelStorage) {

    $this->hasManyTrough = TRUE;
    $this->table = $table;
    $this->joinStorage = $joinStorage;
    $this->modelStorage = $modelStorage;

    return $this;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getCreateStatement() {

    return "{$this->name} {$this->type} {$this->getCreateSettings()}";
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getConstraints() {

    return "{$this->getConstraintSettings()}";
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  protected function getCreateSettings() {

    $settings = '';
    if (isset($this->settings['create'])) {
      $settings .= implode(' ', $this->settings['create']);
    }

    return $settings;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  protected function getConstraintSettings() {

    $settings = '';
    if (isset($this->settings['constraint'])) {
      $settings .= implode(',', $this->settings['constraint']);
    }

    return $settings;
  }

  /**
   * Predicate.
   *
   * @return bool
   *   Result.
   */
  public function hasManyTrough() {

    return (bool) $this->hasManyTrough;
  }

  /**
   * Getter.
   *
   * @return string
   */
  public function getField() {

    return $this->getModelTable() . '_id';
  }

  /**
   * Getter.
   *
   * @return string
   */
  public function getJoinField() {

    return $this->getJoinTable() . '_id';
  }

  /**
   * @return string
   */
  public function getJoinClass() {

    return $this->joinStorage->getModelClass();
  }

  /**
   *
   * @return string
   */
  public function getCreateIntermediateStatement() {

    $field = $this->getField();
    $joinField = $this->getJoinField();

    return <<<SQL
CREATE TABLE {$this->table} (
{$field} bigint(20) unsigned NOT NULL,
{$joinField} bigint(20) unsigned NOT NULL,
PRIMARY KEY ({$field},{$joinField}),
FOREIGN KEY ({$joinField}) REFERENCES {$this->getJoinTable()}(id),
FOREIGN KEY ({$field}) REFERENCES {$this->getModelTable()}(id)
);
SQL;
  }

}
