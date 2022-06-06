<?php 
/**
 * TemplateHelper - Template rendering helper for `Bramus\Router`,
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2022 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;
//use ;
//use Peterujah\NanoBlock\Config;

/**
 * Class TemplateHelper.
 **/

class TemplateHelper {
    private $dir = "";
    public $uri = "";
    public $debug = false;
    private $file = "";
    protected $user; 
    protected $func;
    protected $config;
    public function __construct($dir = "", $debug = false){
        $this->dir = $dir;
        $this->debug = $debug;
        $emptyClass = new stdClass();
        if(class_exists('\Peterujah\NanoBlock\User')){
          $this->addUser(new \Peterujah\NanoBlock\User(\Peterujah\NanoBlock\User::LIVE));
        }else{
          $this->addUser($emptyClass);
        }
        $this->addFunc($emptyClass);
        $this->addConfig($emptyClass);
    }

    public function Build($file): TemplateHelper {
        $this->uri = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->file =  $this->dir . "/router/{$file}.php";
        return $this;
    }

    public function addUser($user) {
      if(empty($this->user) && !empty($user)){
         $this->user = $user;
      }
      return $this;
    }

    public function user(): User {
        return $this->user;
    }

    public function addFunc($func) {
      if(empty($this->func) && !empty($func)){
         $this->func = $func;
      }
      return $this;
    }

    public function func(): Functions {
        return $this->func;
    }

    public function addConfig($config) {
      if(empty($this->config) && !empty($config)){
         $this->config = $config;
      }
      return $this;
    }

    public function config(): Config {
        return $this->config;
    }

    public function root($base = $this->uri, $options = []) {
        $root =  ($this->debug ? $base : "/");
        $self = $options??[];
        $user = $this->user;
        $person = $this->user->instance();
        $func = $this->func;
        $config = $this->config;
        $ALLOW_ACCESS = true;
        require_once $this->file;
    }

    public static function create($file){
        require_once $this->dir . "/router/{$file}.php";
    }

    public function fixSlash($deep = 1){
        if(substr($this->uri, -1) == "/"){
            $slash = explode("/", $this->uri);
            if($deep == 1 && $slash[0] == "localhost" && 3 == count($slash)){
                return "./";
            }
            $total = array_count_values($slash);
            return str_repeat("../", $total['']??0);
        }
        return ($deep == 2 ? "." : "") . "./";
    }
}
