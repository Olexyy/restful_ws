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
   * Field name.
   *
   * @var string
   */
  protected $name;

  /**
   * Defines if field is relation to other model.
   *
   * @var bool
   */
  protected $hasManyTrough;

  /**
   * Intermediate table of field.
   *
   * @var string
   */
  protected $table;

  /**
   * This model storage.
   *
   * @var StorageInterface
   */
  protected $modelStorage;

  /**
   * Joined model storage.
   *
   * @var StorageInterface
   */
  protected $joinStorage;

  /**
   * Field type.
   *
   * @var string
   */
  protected $type;

  /**
   * Field settings.
   *
   * @var array
   */
  protected $settings;

  /**
   * Field constructor.
   *
   * @param string $name
   *   Field name.
   * @param string $type
   *   Field type.
   */
  public function __construct($name, $type) {

    $this->name = $name;
    $this->type = $type;
    $this->settings = [];
  }

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
  public static function create($name, $type) {

    return new static($name, $type);
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getTable() {

    return $this->table;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getModelTable() {

    return $this->modelStorage->getTable();;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getJoinTable() {

    return $this->joinStorage->getTable();
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getName() {

    return $this->name;
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
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
   *   Table name.
   * @param StorageInterface $joinStorage
   *   Storage of joined model.
   * @param StorageInterface $modelStorage
   *   Storage of this model.
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
   *   Value.
   */
  public function getField() {

    return $this->getModelTable() . '_id';
  }

  /**
   * Getter.
   *
   * @return string
   *   Value.
   */
  public function getJoinField() {

    return $this->getJoinTable() . '_id';
  }

  /**
   * Getter for Model that is joined.
   *
   * @return string
   *   Classname.
   */
  public function getJoinClass() {

    return $this->joinStorage->getModelClass();
  }

  /**
   * Getter for intermediate table creation.
   *
   * @return string
   *   Statement.
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
