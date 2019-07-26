<?php

namespace RestfulWS\Core;

/**
 * Interface KernelBuilderInterface.
 *
 * @package RestfulWS\Core
 */
interface KernelBuilderInterface {

  /**
   * Factory method.
   *
   * @return KernelInterface
   *   Instance.
   */
  public static function create();

}