<?php
/**
 * @author    Petros Diveris (https://www.diveris.org)
 * @link      https://github.com/pdiveris
 * @copyright Copyright (c) 2017 Petros Diveris
 * @license   https://raw.githubusercontent.com/jockchou/TinyMVC/master/LICENSE (Apache License)
 */
namespace Bentleysoft\ModelViewController;

class Model
{
  protected $config;
  protected $pdo = null;
  
  function __construct()
  {
    $this->config = require CFG_PATH . ENV . "/database.php";
  }
  
  /**
   * @param string $name
   * @return null|\PDO
   */
  public function loadDB($name = 'default')
  {
    if ($this->pdo != null) {
      return $this->pdo;
    } else {
      try {
        $config = $this->config[$name];
        $this->pdo = new \PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
        
        return $this->pdo;
      } catch (\PDOException $e) {
        throw new FrameworkException("Database connection failed: " . $e->getMessage());
      }
    }
  }
}