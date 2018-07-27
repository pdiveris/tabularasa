<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 27/07/2018
 * Time: 07:45
 */

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
  return false;
}

    // Include the Router class
    // @note: it's recommended to just use the composer autoloader when working with other packages too
    require_once __DIR__ . '/../src/Bentleysoft/Router/Router.php';
    // Create a Router
    $router = new \Bentleysoft\Router\Router();
    // Custom 404 Handler
    $router->set404(function () {
      header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
      echo '404, route not found!';
    });
    // Before Router Middleware
    $router->before('GET', '/.*', function () {
      header('X-Powered-By: Bentleysoft/router');
    });
    // Static route: / (homepage)
    $router->get('/', function () {
      echo '<h1>Bentleysoft/router</h1><p>Try these routes:<p><ul><li>/hello/<em>name</em></li><li>/blog</li><li>/blog/<em>year</em></li><li>/blog/<em>year</em>/<em>month</em></li><li>/blog/<em>year</em>/<em>month</em>/<em>day</em></li><li>/movies</li><li>/movies/<em>id</em></li></ul>';
    });
    // Static route: /hello
    $router->get('/hello', function () {
      echo '<h1>Bentleysoft/router</h1><p>Visit <code>/hello/<em>name</em></code> to get your Hello World mojo on!</p>';
    });
    // Dynamic route: /hello/name
    $router->get('/hello/(\w+)', function ($name) {
      echo 'Hello ' . htmlentities($name);
    });
    // Dynamic route: /ohai/name/in/parts
    $router->get('/ohai/(.*)', function ($url) {
      echo 'Ohai ' . htmlentities($url);
    });
    // Dynamic route with (successive) optional subpatterns: /blog(/year(/month(/day(/slug))))
    $router->get('/blog(/\d{4}(/\d{2}(/\d{2}(/[a-z0-9_-]+)?)?)?)?', function ($year = null, $month = null, $day = null, $slug = null) {
      if (!$year) {
        echo 'Blog overview';
        return;
      }
      if (!$month) {
        echo 'Blog year overview (' . $year . ')';
        return;
      }
      if (!$day) {
        echo 'Blog month overview (' . $year . '-' . $month . ')';
        return;
      }
      if (!$slug) {
        echo 'Blog day overview (' . $year . '-' . $month . '-' . $day . ')';
        return;
      }
      echo 'Blogpost ' . htmlentities($slug) . ' detail (' . $year . '-' . $month . '-' . $day . ')';
    });
    // Subrouting
    $router->mount('/movies', function () use ($router) {
      // will result in '/movies'
      $router->get('/', function () {
        echo 'movies overview';
      });
      // will result in '/movies'
      $router->post('/', function () {
        echo 'add movie';
      });
      // will result in '/movies/id'
      $router->get('/(\d+)', function ($id) {
        echo 'movie id ' . htmlentities($id);
      });
      // will result in '/movies/id'
      $router->put('/(\d+)', function ($id) {
        echo 'Update movie id ' . htmlentities($id);
      });
    });
    // Thunderbirds are go!
    $router->run();
// EOF
