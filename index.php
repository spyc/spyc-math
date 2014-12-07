<?php
  require_once("inc/http.php");
  include_once("inc/php/fn.php");
  class Control {
    static function exceptionResponse($statusCode, $message) {
        header("HTTP/1.0 {$statusCode} {$message}");
        print("{$statusCode} {$message}");
        exit();
    }
}

  class Container extends Control{

    public $segment = false;
    private $control = false;

    public function __construct() {
      if ( !isset($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO'] == "/") {
        return ;
      }
      $this->segment = explode("/", $_SERVER['PATH_INFO']);
      array_shift($this->segment);

      $controlName = ucfirst(array_shift($this->segment));

      if (!class_exists($controlName)) {
        $classPath = $controlName . ".php";

        if (file_exists($classPath)) {
          require_once($classPath);
        } else {
          self::exceptionResponse(404, "Not Found");
        }
      }
      try{
        $this->control = new $controlName;
      } catch (Exception $e) {
        
      }
    }

    public function run(){
      if ($this->control === false) {
        include "index.class.php";
        $this->control = new Index();
      }
      $parameter = $_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST" ? $_REQUEST : json_decode(file_get_contents("php://input"), 1);
      $request = new RESTfulRequest($parameter, $_SERVER, $_COOKIE, isset($_SESSION)? $_SESSION : null, $this->segment);
      $response = new RESTfulResponse($request);

      $this->control->service($request, $response);
      $response->send();
    }
  }
  ob_start();
  $request = new Container();
  $request->run();
  ob_end_flush();
?>