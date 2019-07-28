<?php

namespace RestfulWS\Core\Components\Query;

use LogicException;
use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;

/**
 * Class ModifyQueryInterface.
 *
 * @package RestfulWS\Core\Components\Query
 */
interface ModifyQueryInterface {

  /**
   * @param StorageInterface $storage
   *
   * @return $this
   *    Instance.
   */
  public static function create(StorageInterface $storage);

  /**
   * ModifyQueryInterface constructor.
   *
   * @param StorageInterface $storage
   *   Storage.
   */
  public function __construct(StorageInterface $storage);

  /**
   * Getter for binding statement params.
   *
   * @param ModelInterface $model
   *   Given model.
   *
   * @return array|string[]
   *   Params.
   *
   * @throws LogicException
   */
  public function getStatement(ModelInterface $model);

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatementParams();

}
