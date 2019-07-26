<?php

namespace RestfulWS\Core\Components\Database;

/**
 * Interface DatabaseInterface.
 *
 * @package RestfulWS\Core\Components\Database
 */
interface DatabaseInterface {

  /**
   * Singleton.
   *
   * @param string $user
   *   User name.
   * @param string $pass
   *   Password.
   * @param string $host
   *   Hostname.
   * @param string $database
   *   Database.
   *
   * @return $this
   *   Instance.
   */
  public static function instance($user = NULL, $pass = NULL, $host = NULL, $database = NULL);

  /**
   * Connection getter.
   *
   * @return null|\PDO
   */
  public function getConnection();

  /**
   * Predicate to define table existence.
   *
   * @param string $table
   *   Table name.
   *
   * @return bool
   *   Result.
   */
  public function tableExists($table);

  /**
   * Closes connection.
   */
  public function closeConnection();

}
