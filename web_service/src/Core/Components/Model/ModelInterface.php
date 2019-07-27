<?php

namespace RestfulWS\Core\Components\Model;

use RestfulWS\Core\Components\Storage\Storage;

/**
 * Interface ModelInterface.
 *
 * @package RestfulWS\Core\Components\Model
 */
interface ModelInterface {

  /**
   * Model constructor.
   *
   * @param null|array $data
   *   Data.
   */
  public function __construct(array $data = NULL);

  /**
   * Getter for values array.
   *
   * @param bool $lazy
   *   Lazy.
   *
   * @return array|string[]
   *   Values.
   */
  public function getValues($lazy = FALSE);

  /**
   * Map values.
   *
   * @param array $values
   *   Values to map on model.
   * @param bool $notify
   *   Flag to notify property changed.
   */
  public function map(array $values = [], $notify = TRUE);

  /**
   * Inline constructor.
   *
   * @param array|NULL $data
   *
   * @return $this
   *   Instance.
   */
  public static function create(array $data = NULL);

  /**
   * Predicate.
   *
   * @param string $name
   *   Name.
   *
   * @return bool
   *   Result.
   */
  public function has($name);

  /**
   * Getter.
   *
   * @param string $name
   *   Name.
   *
   * @return mixed|string|null|object|array|ModelInterface|ModelInterface[]
   *   Value.
   */
  public function get($name);

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
  public function set($name, $value, $notify = TRUE);

  /**
   * Remove handler.
   *
   * @param string $name
   *   Name.
   *
   * @return $this
   *   Chaining.
   */
  public function remove($name);

  /**
   * Getter for id.
   *
   * @return mixed|string|null
   *   Value.
   */
  public function getId();

  /**
   * Is new predicate.
   *
   * @return bool
   *   Result.
   */
  public function isNew();

  /**
   * Getter for storage.
   *
   * @return Storage
   *   Model storage.
   */
  public static function getStorage();

  /**
   * Save handler.
   *
   * @return $this
   *   Chaining.
   */
  public function save();

  /**
   * Delete handler.
   *
   * @return $this
   *   Chaining.
   */
  public function delete();

  /**
   * Get properties for update.
   *
   * @return array|string[]
   *   Properties and values to update.
   */
  public function getChanged();

}
