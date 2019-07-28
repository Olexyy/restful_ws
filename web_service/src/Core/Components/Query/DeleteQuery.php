<?php

namespace RestfulWS\Core\Components\Query;

use RestfulWS\Core\Components\Model\ModelInterface;
use RestfulWS\Core\Components\Storage\StorageInterface;
use Symfony\Component\Mime\Exception\LogicException;

/**
 * Class DeleteQuery.
 *
 * @package RestfulWS\Core\Components\Query
 */
class DeleteQuery implements ModifyQueryInterface {

  /**
   * @var string
   */
  protected $statement;

  /**
   * @var array
   */
  protected $statementParams;

  /**
   * @var StorageInterface
   */
  protected $storage;

  /**
   * Inline constructor.
   *
   * @param StorageInterface $storage
   *
   * @return $this
   *   Instance.
   */
  public static function create(StorageInterface $storage) {

    return new static($storage);
  }

  /**
   * DeleteQuery constructor.
   *
   * @param StorageInterface $storage
   *   Storage.
   */
  public function __construct(StorageInterface $storage) {

    $this->storage = $storage;
    $this->statement = '';
    $this->statementParams = [];
  }

  /**
   * Getter for binding statement params.
   *
   * @param ModelInterface $model
   *   Given model.
   *
   * @return string
   *   Statement.
   *
   * @throws LogicException
   */
  public function getStatement(ModelInterface $model = NULL) {

    $this->statementParams = [];
    $statement = "DELETE FROM {$this->storage->getTable()}";
    if ($model) {
      if (!$model->getId()) {
        throw new \LogicException(
          'Model with no id cannot be deleted.'
        );
      }
      $statement .= " WHERE id = :id";
      $this->statementParams[':id'] = $model->getId();
    }
    $this->statement = "$statement;";

    return $this->statement;
  }

  /**
   * Getter for binding statement params.
   *
   * @return array|string[]
   *   Params.
   */
  public function getStatementParams() {

    return $this->statementParams;
  }
}