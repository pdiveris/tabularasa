<?php
/**
 * @author    Petros Diveris (https://www.diveris.org)
 * @link      https://github.com/pdiveris
 * @copyright Copyright (c) 2017 Petros Diveris
 * @license   https://raw.githubusercontent.com/jockchou/TinyMVC/master/LICENSE (Apache License)
 */
namespace Bentleysoft\ModelViewController;

/**
 * Class Application
 * @package Bentleysoft\ModelViewController
 */
class Application
{
  private $pathInfo = null;
  
  /**
   * @param string $uri
   */
  function __construct($uri)
  {
    $this->pathInfo = trim(preg_replace("/\/+/", '/', $uri), '/');
  }
  
  /**
   *
   */
  public function run()
  {
    if (!empty($this->pathInfo)) {
      $pathArr = explode('/', $this->pathInfo);
      $action = strtolower((isset($pathArr[1]) ? $pathArr[1] : 'index'));
      
      $controller = ucfirst(strtolower($pathArr[0]));
      $controllerClass = NS_CTRL . $controller . C_SUFFIX;
      $controllerMethod = $action . M_SUFFIX;
      
      if (class_exists($controllerClass) && method_exists($controllerClass, $controllerMethod)) {
        try {
          $controllerObject = new $controllerClass();
          if ($controllerObject->$controllerMethod() !== false) {
            $controllerObject->render(strtolower($controller) . APP_DS . $action);
          }
        } catch (FrameworkException $e) {
          Controller::show500($e);
        }
      } else {
        Controller::show404();
      }
    } else {
      Controller::welcome();
    }
  }
}