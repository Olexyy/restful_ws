<?php

namespace RestfulWS\Core\Components\Factory;

use RestfulWS\Core\Components\Model\Model;

/**
 * Interface ModelFactoryInterface.
 *
 * @package RestfulWS\Core\Components\Factory
 */
interface ModelFactoryInterface {

  /**
   * Generator.
   *
   * @param int $count
   *   Count of items to generate.
   * @param bool $save
   *   Save flag.
   *
   * @return array|Model[]|Model
   *   Generated models.
   */
  public function generate($count = 1, $save = FALSE);

}
