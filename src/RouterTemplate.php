<?php 
/**
 * TemplateHelper - Template rendering helper for `Bramus\Router`,
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2022 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;

/**
 * Class RouterTemplate.
 **/

class RouterTemplate {
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

    /** Holds the router active page name
     * @var string $activePage 
    */
    private $activePage = "";

    /** Holds the project template file directory path
     * @var string $file 
    */
    private $templateDir = "router";

    /** Holds the array classes
     * @var object|Config $config 
    */

    protected $classMapper = array();

    /** Holds template assets folder
     * @var object|Config $config 
    */
    public $assetsFolder = "assets/";

    /** Holds template project root
     * @var object|Config $config 
    */
    private $projectRoot = "";

    /**
     * Object registered with this server
     */
    protected $object;


    /** 
    * Initialize class construct
    */
    public function __construct($dir = "", $debug = false){
        $this->dir = $dir;
        $this->debug = $debug;
        $this->setTemplatePath("router");
    }

    /** 
    * Set the template directory path
    * @param string $path the file path directory
    * @return RouterTemplate|object $this
    */
    public function setTemplatePath($path){
        $this->templateDir = trim( $path, "/" );
        return $this;
    }
    /** 
    * Renders the template full path
    * @param string $file the file name
    * @return RouterTemplate|object $this
    */
    public function Render($file): RouterTemplate {
        $this->file = "{$this->dir}/router/{$file}.php";
        $this->activePage = $file;
        return $this;
    }

    /** Register a custom User class to template
    * @param User|object $user the user class object
    * @return RouterTemplate|object $this
    */
    public function addUser($user) {
        if(empty($this->newClass("user")) && !empty($user)){
          $this->addClass("user", $user);
        }
        return $this;
    }
    /** 
    * Register a custom functions class to template
    * @param Functions|object $func the function class object
    * @return RouterTemplate|object $this
    */
    public function addFunc($func) {
        if(empty($this->newClass("func")) && !empty($func)){
            $this->addClass("func", $func);
        }
        return $this;
    }

    /** Register a custom configuration class to template
    * @param Config|object $config the configuration class object
    * @return RouterTemplate|object $this
    */
    public function addConfig($config) {
        if(empty($this->newClass("config")) && !empty($config)){
            $this->addClass("config", $config);
        }
        return $this;
    }

    /** Register a custom configuration class to template
    * @param SettingManager|object $config the configuration class object
    * @return RouterTemplate|object $this
    */
    public function addSettings($settings) {
        if(empty($this->newClass("settings")) && !empty($settings)){
            $this->addClass("settings", $settings);
        }
        return $this;
    }


    /** 
     * Register a class instance to template
    * @param String $name the class name/identifier
    * @param Class|Object $class class instance
    * @return RouterTemplate|object $this
    */
    function addClass($name, $class){
        /*if(empty($name) or empty($class)){
            trigger_error("Invalid class mapper exception");
        }*/
        if (empty($name) OR !is_string($name)) {
            throw new \Exception(sprintf(
                'Invalid class name (%s)',
                gettype($name)
            ));
        }

        if (empty($class) OR !is_object($class)) {
            throw new \Exception(sprintf(
                'Invalid class argument (%s)',
                gettype($class)
            ));
        }
        $this->classMapper[$name] = $class;
        return $this;
    }
    
    /** 
     * Initialize class instance by name
    * @param String $name the class name/identifier
    * @return Object $classInstance
    */
    public function newClass($name) {
        return $this->classMapper[$name]??null;
    }

    /** 
     * Set router base root
    * @param String $root base directory
    * @return RouterTemplate|object $this
    */
    public function setRoot($root) {
        $this->projectRoot = $root;
        return $this;
    }

     /**
     * Attach an object to a server
     *
     * Accepts an instantiated object to use when handling requests.
     *
     * @param  object $object
     * @return self
     * @throws Exception
     */
    public function setObject($object){
        if (empty($object) OR !is_object($object)) {
            throw new \Exception(sprintf(
                'Invalid object argument (%s)',
                gettype($object)
            ));
        }

        if (isset($this->object)) {
            throw new \Exception(
                'An object has already been registered with this soap server instance'
            );
        }

        $this->object = $object;
        return $this;
    }

    /** 
     * Get object instance
    * @return Object
    */
    public function getObject(){
        return ($this->object??(object)[]);
    }

    /** 
    * Creates and Render template by including the accessible global variable within the template file.
    * @param string|path $base app root directory
    * @param array $options additional parameters to pass in the template file
    */
    public function with($base_dir, $options = []) {
        $root =  ($this->debug ? $base_dir : "/");
        $base =  ($root . $this->projectRoot);
        $self = $options??[];
        if(empty($self["active"])){
            $self["active"] = $this->activePage;
        }
        $user = $this->newClass("user");
        $func = $this->newClass("func");
        $config = $this->newClass("config");
        $settings = $this->newClass("settings");
        if (!defined('ALLOW_ACCESS')){
            define("ALLOW_ACCESS", true);
        }
        if (!defined('ASSETS')){
            define("ASSETS", "{$root}{$this->assetsFolder}");
        }
        if (!defined('BASE_ASSETS')){
            define("BASE_ASSETS", "{$base}{$this->assetsFolder}");
        }
        require_once $this->file;
    }

    /** 
    * Shorthand to build and Render template by including the accessible global variable within the template file.
    * @param int $deep the directory location dept
    * @param array $options additional parameters to pass in the template file
    */
    public function view($deep, $options = []) {
        $this->with($this->deep($deep), $options);
    }

    /** 
    * Render file from template file path
    * @param string $file the file name
    */
    public static function create($file){
        require_once "{$this->dir}/{$this->templateDir}/{$file}.php";
    }

    /** 
    * Fixes the broken css,image & links when added additional slash(/) at the router link
    * The function will add the appropriate relative base based on how many invalid link detected.
    * @param int $deep the directory location dept from base directory index.php/fee(1) index.php/foo/bar(2)
    * @return string|path relative path 
    */
    public function deep($deep = 0){
        $this->uri = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if(substr($this->uri, -1) == "/"){
            $slash = explode("/", $this->uri);
            if($deep == 1 && $slash[0] == "localhost" && 3 == count($slash)){
                return "./";
            }
            $total = array_count_values($slash);
            return str_repeat("../", $total['']??0);
        }else if($deep >= 2){
            return str_repeat("../", $deep);
        }
        return ($deep > 0 ? "../" : "./");
    }
}
