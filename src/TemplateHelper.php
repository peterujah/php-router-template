<?php 
/**
 * TemplateHelper - Template rendering helper for `Bramus\Router`,
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2022 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;

/**
 * Class TemplateHelper.
 **/

class TemplateHelper {
    /** Holds the project base directory
     * @var string|path|dir $dir __DIR__
    */
    private $dir = "";

    /** Holds the requested url
     * @var string|uri $uri full url
    */
    public $uri = "";

    /** Holds the debug state
     * @var bool $debug true 0r false
    */
    public $debug = false;

    /** Holds the project template file name
     * @var string $file 
    */
    private $file = "";

    /** Holds the project user class
     * @var object|User $user 
    */
    protected $user; 

    /** Holds the project custom functions class
     * @var object|Functions $func 
    */
    protected $func;

    /** Holds the project custom configuration class
     * @var object|Config $config 
    */
    protected $config;

    /** 
    * Initialize class construct
    */
    public function __construct($dir = "", $debug = false){
        $this->dir = $dir;
        $this->debug = $debug;
    }

    /** 
    * Renders the template full path
    * @param string $file the file name
    * @return TemplateHelper|object $this
    */
    public function Render($file): TemplateHelper {
        $this->file = "{$this->dir}/router/{$file}.php";
        return $this;
    }

    /** Register a custom User class to template
    * @param User|object $user the user class object
    * @return TemplateHelper|object $this
    */
    public function addUser($user) {
        if(empty($this->user) && !empty($user)){
         $this->user = $user;
        }
        return $this;
    }

    /** 
    * Gets User class object
    * @return User|object $this->user
    */
    public function user(): User {
        return $this->user;
    }

    /** 
    * Register a custom functions class to template
    * @param Functions|object $func the function class object
    * @return TemplateHelper|object $this
    */
    public function addFunc($func) {
        if(empty($this->func) && !empty($func)){
         $this->func = $func;
        }
        return $this;
    }

    /** 
    * Gets Function class object
    * @return Function|object $this->func
    */
    public function func(): Functions {
        return $this->func;
    }

    /** Register a custom configuration class to template
    * @param Config|object $config the configuration class object
    * @return TemplateHelper|object $this
    */
    public function addConfig($config) {
        if(empty($this->config) && !empty($config)){
         $this->config = $config;
        }
        return $this;
    }

    /** 
    * Gets Config class object
    * @return Config|object $this->config
    */
    public function config(): Config {
    return $this->config;
    }

    /** 
    * Creates and Render template by including the accessible global variable within the template file.
    * @param string|path $base app root directory
    * @param array $options additional parameters to pass in the template file
    */
    public function with($base, $options = []) {
        $root =  ($this->debug ? $base : "/");
        $self = $options??[];
        $user = $this->user;
        $person = $this->user->instance();
        $func = $this->func;
        $config = $this->config;
        $ALLOW_ACCESS = true;
        require_once $this->file;
    }

    /** 
    * Shorthand to Creates and Render template by including the accessible global variable within the template file.
    * @param int $deep the directory location dept
    * @param array $options additional parameters to pass in the template file
    */
    public function withDept($deep, $options = []) {
        $this->with($this->deep($deep), $options);
    }

    /** 
    * Render file from template file path
    * @param string $file the file name
    */
    public static function create($file){
        require_once "{$this->dir}/router/{$file}.php";
    }

    /** 
    * Fixes the broken css,image & links when added additional slash(/) at the router link
    * The function will add the appropriate relative base based on how many invalid link detected.
    * @param int $deep the directory location dept from base directory index.php/fee(1) index.php/foo/bar(2)
    * @return string|path relative path 
    */
    public function deep($deep = 1){
        $this->uri = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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
