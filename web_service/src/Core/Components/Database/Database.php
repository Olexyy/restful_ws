<?php

namespace RestfulWS\Core\Components\Database;

/**
 * Class Database.
 *
 * @package RestfulWS\Core\Components\Database
 */
class Database implements DatabaseInterface {

  /**
   * Connection.
   *
   * @var null|\PDO
   */
  protected $connection;

  /**
   * Singleton.
   *
   * @var $this
   */
  protected static $instance;

  /**
   * Username.
   *
   * @var string
   */
  protected $user;

  /**
   * Password.
   *
   * @var string
   */
  protected $pass;

  /**
   * Database name.
   *
   * @var string
   */
  protected $database;

  /**
   * Host name.
   *
   * @var string
   */
  protected $host;

  /**
   * Database constructor, provides single connection.
   *
   * @param string $user
   *   User name.
   * @param string $pass
   *   User pass.
   * @param $host
   *   Db host.
   * @param $database
   *   Db name.
   */
  protected function __construct($user, $pass, $host, $database) {

    $this->user = $user;
    $this->pass = $pass;
    $this->host = $host;
    $this->database = $database;
  }

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
  public static function instance($user = NULL, $pass = NULL, $host = NULL, $database = NULL) {

    if (!static::$instance) {

      static::$instance = new static($user, $pass, $host, $database);
    }
    return static::$instance;
  }

  /**
   * Connection getter.
   *
   * @return null|\PDO
   *   Connection.
   */
  public function getConnection() {

    if (!$this->connection) {
      $this->connection = new \PDO(
        "mysql:host={$this->host};dbname={$this->database}",
        $this->user,
        $this->pass
      );
    }

    return $this->connection;
  }

  /**
   * Predicate to define table existence.
   *
   * @param string $table
   *   Table name.
   *
   * @return bool
   *   Result.
   */
  public function tableExists($table) {

    $query = <<<SQL
SELECT COUNT(*) AS count 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = '{$this->database}' AND TABLE_NAME = '{$table}'
SQL;
    $stmt = $this->getConnection()->query($query);
    $stmt->setFetchMode(\PDO::FETCH_BOTH);
    $res = $stmt->fetch();

    return (bool) $res['count'];
  }

  /**
   * Closes connection.
   */
  public function closeConnection() {

    $this->connection = NULL;
  }

}
