<?php

namespace RestfulWS\Core\Components\Model;

use RestfulWS\Core\Components\Storage\Storage;

/**
 * Class Model.
 *
 * @package RestfulWS\Core\Components\Model
 */
abstract class Model implements ModelInterface, \JsonSerializable {

  /**
   * Values.
   *
   * @var array|string[]
   */
  protected $values;

  /**
   * @var array|string[]
   */
  protected $changed;

  /**
   * Model storage.
   *
   * @var Storage
   */
  protected static $storage;

  /**
   * Model constructor.
   *
   * @param null|array $data
   *   Data.
   */
  public function __construct(array $data = NULL) {

    $this->changed = [];
    is_array($data) ? $this->map($data, FALSE) : $this->map([]);
    if (!$this->values) {
      $this->mapSelf();
    }
  }

  /**
   * Get properties for update.
   *
   * @return array|string[]
   *   Properties and values to update.
   */
  public function getChanged() {

    $changed = $this->changed;
    return array_filter($this->values, function($value, $key) use ($changed) {
      return in_array($key, $changed);
    }, ARRAY_FILTER_USE_BOTH);
  }

  /**
   * Map values.
   *
   * @param bool $notify
   *   Flag to notify property changed.
   */
  protected function mapSelf($notify = FALSE) {

    foreach (static::getStorage()->getFields() as $field) {
      if (isset($this->{$field})) {
        $this->set($field, $this->{$field}, $notify);
        unset($this->{$field});
      }
    }
  }

  /**
   * Map values.
   *
   * @param array $values
   *   Values to map on model.
   * @param bool $notify
   *   Flag to notify property changed.
   */
  public function map(array $values = [], $notify = TRUE) {

    foreach (static::getStorage()->getFields() as $field) {
      if (isset($values[$field])) {
        $this->set($field, $values[$field], $notify);
      }
    }
  }

  /**
   * Getter for values array.
   *
   * @return array|string[]
   *   Values.
   */
  public function getValues() {

    return $this->values;
  }

  /**
   * Inline constructor.
   *
   * @param array|NULL $data
   *
   * @return $this
   *   Instance.
   */
  public static function create(array $data = NULL) {

    return new static($data);
  }

  /**
   * Predicate.
   *
   * @param string $name
   *   Name.
   *
   * @return bool
   *   Result.
   */
  public function has($name) {

    return isset($this->values[$name]);
  }

  /**
   * Getter.
   *
   * @param string $name
   *   Name.
   *
   * @return mixed|string|null|object|array
   *   Value.
   */
  public function get($name) {

    if ($this->has($name)) {

      return $this->values[$name];
    }

    return NULL;
  }

  /**
   * Setter.
   *
   * @param string $name
   *   Name.
   * @param mixed $value
   *   Value.
   * @param bool $notify
   *   Flag to notify property changed.
   *
   * @return $this
   *   Chaining.
   */
  public function set($name, $value, $notify = TRUE) {

    $this->values[$name] = $value;
    if ($notify) {
      $this->changed[] = $name;
    }

    return $this;
  }

  /**
   * Remove handler.
   *
   * @param string $name
   *   Name.
   *
   * @return $this
   *   Chaining.
   */
  public function remove($name) {

    unset($this->values[$name]);

    return $this;
  }

  /**
   * Getter for id.
   *
   * @return mixed|string|null
   *   Value.
   */
  public function getId() {

    return $this->get('id');
  }

  /**
   * Is new predicate.
   *
   * @return bool
   *   Result.
   */
  public function isNew() {

    return !$this->getId();
  }

  /**
   * Getter for storage.
   *
   * @return Storage
   *   Model storage.
   */
  public abstract static function getStorage();

  /**
   * Save handler.
   *
   * @return $this
   *   Chaining.
   */
  public function save() {

    static::getStorage()->save($this);
    $this->changed = [];

    return $this;
  }

  /**
   * Delete handler.
   *
   * @return $this
   *   Chaining.
   */
  public function delete() {

    static::getStorage()->delete($this);

    return $this;
  }

  /**
   * Casts to json.
   *
   * @return false|string
   *   Json string.
   */
  public function toJson() {

    return json_encode($this);
  }

  /**
   * {@inheritdoc}
   */
  public function jsonSerialize () {

    return $this->values;
  }

}
