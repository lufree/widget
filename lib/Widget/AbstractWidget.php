<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * @see Widget\WidgetInterface
 */
require_once 'WidgetInterface.php';

/**
 * The base class for all widgets
 *
 * @author   Twin Huang <twinhuang@qq.com>
 *
 * @method   mixed      __invoke(mixed $mixed) The invoke method
 *
 * HTTP Request
 * @property Request    $request A widget that handles the HTTP request data
 * @method   mixed      request($name, $default = null) Returns a stringify rquest parameter value
 * @property Cookie     $cookie A widget manager the HTTP cookie
 * @method   mixed      cookie($key, $value = null, $options = array()) Get or set cookie
 * @property Os         $os A widget to detect user OS and browser name and version
 * @method   bool       os() Check if in the specified browser, OS or device
 * @property Post       $post A widget that handles the HTTP request parameters ($_POST)
 * @method   string     post($name, $default = null) Returns a stringify rquest parameter value
 * @property Query      $query A widget that handles the URL query parameters ($_GET)
 * @method   string     query($name, $default = null) Returns a stringify URL query parameter value
 * @property Server     $server A widget that handles the server and execution environment parameters ($_SERVER)
 * @method   string     server($name, $default = null) Returns a stringify server or execution environment parameter value
 * @property Session    $session A widget that session parameters ($_SESSION)
 * @method   mixed      session($name, $default = null) Returns a stringify session parameter value
 * @property Upload     $upload A widget that handles the uploaded files
 * @method   bool       upload(array $options = array() Upload a file
 *
 * HTTP Response
 * @property Response   $response A widget that send the HTTP response
 * @method   Response   response($content = null, $status = null) Send response header and content
 * @property Download   $download A widget send file download response
 * @method   Download   download($file, $options) Send file download response
 * @property Flush      $fulsh A widget that flushes the content to browser immediately
 * @method   Flush      flush($content = null, $status = null) Send response content
 * @property Header     $header The response header widget
 * @method   mixed      header($name, $values = null, $replace = true) Get or set HTTP header
 * @property Json       $json A widget to response json
 * @method   Json       json($message = null, $code = 0, array $append = array(), $jsonp = false) Send JSON(P) response
 * @property Redirect   $redirect A widget that send a redirect response
 * @method   Redirect   redirect($url = null, $status = 302, array $options = array()) Send a redirect response
 *
 * Cache
 * @property Cache      $cache A cache widget proxy
 * @method   mixed      cache($key, $value = null, $expire = 0) Retrieve or store an item by cache
 * @property ArrayCache $arrayCache  A cache widget stored data in PHP array
 * @method   mixed      arrayCache($key, $value = null, $expire = 0) Retrieve or store an item by array cache
 * @property Apc        $apc The PHP APC cache widget
 * @method   mixed      apc($key, $value = null, $expire = 0) Retrieve or store an item
 * @property DbCache    $dbCache A database cache widget
 * @method   mixed      dbCache($key, $value = null, $expire = 0) Retrieve or store an item by database cache
 * @property FileCache  $fileCache A file cache widget
 * @method   mixed      fileCache($key, $value = null, $expire = 0) Retrieve or store an item by file
 * @property Memcache   $memcache A cache widget base on Memcache
 * @method   mixed      memcache($key, $value = null, $expire = 0) Retrieve or store an item by Memcache
 * @property Memcached  $memcached A cache widget base on Memcached
 * @method   mixed      memcached($key, $value = null, $expire = 0) Retrieve or store an item by Memcached
 * @property Couchbase  $couchbase A cache widget base on Couchbase
 * @method   mixed      couchbase($key, $value = null, $expire = 0) Retrieve or store an item by Couchbase
 * @property redis      $redis A cache widget base on Redis
 * @method   mixed      redis($key, $value = null, $expire = 0) Retrieve or store an item by Redis
 * @property Bicache    $bicache A two-level cache widget
 * @method   mixed      bicache($key, $value = null, $expire = 0) Retrieve or store an item by two-level cache
 *
 * View
 * @property View       $view A widget that use to render PHP template
 * @method   string     view($name = null, $vars = array()) Returns view widget or render a PHP template
 * @property Escape     $escape A widget to escape HTML, javascript, CSS, HTML Attribute and URL for secure output
 * @method   string     escape($string, $type = 'html') Escapes a string by specified type for secure output
 * @property Smarty     $smarty A wrapper widget for Smarty object
 * @method   mixed      smarty($name = null, $vars = array()) Returns the internal Smarty object or render a Smarty template
 * @property Twig       $twig A wrapper widget for Twig object
 * @method   mixed      twig($name = null, $vars = array()) Returns \Twig_Environment object or render a Twig template
 *
 * Validation
 * @method   Validate   validate(array $option) Create a new validator and validate by specified options
 * @property Is         $is The validator manager, use to validate input quickly, create validator and rule validator
 *
 * Event
 * @property EventManager $eventManager The event manager to add, remove and trigger events
 * @method   EventManager off($type) Remove event handlers by specified type
 * @method   EventManager on($type, $fn = null, $priority = 0, $data = array()) Attach a handler to an event
 * @method   Event\Event  trigger($event, $params = array(), WidgetInterface $widget = null) Trigger an event
 *
 * Database
 * @property Db                           $db A container widget for Doctrine DBAL connection object
 * @method   \Doctrine\DBAL\Connection    db() Retrieve the Doctrine DBAL connection object
 * @property EntityManager                $entityManager A container widget for Doctrine ORM entity manager object
 * @method   \Doctrine\ORM\EntityManager  entityManager() Returns the Docrine ORM entity manager
 *
 * Util
 * @property Arr        $arr An util widget provides some useful method to manipulation array
 * @property Env        $env A widget to detect the environment and load configuration by environment
 * @method   string     env() Returns the environment name
 * @method   bool       is($rule = null, $input = null, $options = array()) Validate input by given rule
 * @property Logger     $logger A simple logger widget, which is base on the Monolog
 * @method   bool       logger($level, $message) Logs with an arbitrary level
 * @property Monolog    $monolog A wrapper widget for Monolog
 * @method   bool       monolog($level = null, $message = null, array $context = array()) Get monolog logger object or add a log record
 * @property Pinyin     $pinyin An util widget that converts Chinese words to phonetic alphabets
 * @method   string     pinyin($word) Converts Chinese words to phonetic alphabets
 * @property Uuid       $uuid A util widget that generates a RANDOM UUID(universally unique identifier)
 * @method   string     uuid() generates a RANDOM UUID(universally unique identifier)
 * @property Website    $website A pure configuration widget for your website
 * @method   string     website($name) Returns the value of website configuration
 * @property Call       $call A widget handles HTTP request like jQuery Ajax
 * @method   Call       call(array $options) Create a new call object and execute
 * @property Callback   $callback A widget handles WeChat(Weixin) callback message
 * @method   Callback   callback() Start up callback widget and output the matched rule message
 * @property App        $app The application widget
 * @method   App        app(array $options = array()) Startup application
 * @property Router     $router A widget that build a simple REST application
 * @method   Router     router($pathInfo = null, $method = null) Run the application
 * @property T          $t A translator widget
 * @method   string     t($message, array $parameters = array()) Translate the message
 * @property Url        $url A util widget to build URL
 * @method   string     url($uri) Build URL by specified uri and parameters
 */
abstract class AbstractWidget implements WidgetInterface
{
    /**
     * The default dependence map
     *
     * @var array
     */
    protected $deps = array();

    /**
     * The widget manager object
     *
     * @var Widget
     */
    protected $widget;

    /**
     * Constructor
     *
     * @param  array        $options The property options
     */
    public function __construct(array $options = array())
    {
        $this->setOption($options);

        if (!isset($this->widget)) {
            $this->widget = Widget::create();
        } elseif (!$this->widget instanceof WidgetInterface) {
            throw new \InvalidArgumentException(sprintf('Option "widget" of class "%s" should be an instance of "WidgetInterface"', get_class($this)));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value = null)
    {
        // Set options
        if (is_array($name)) {
            if (is_array($value)) {
                $name += array_intersect_key(get_object_vars($this), array_flip($value));
            }
            foreach ($name as $k => $v) {
                $this->setOption($k, $v);
            }
            return $this;
        }

        // Append option
        if (isset($name[0]) && '+' == $name[0]) {
            $name = substr($name, 1);
            $this->$name = (array)$this->$name + $value;
            return $this;
        }

        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            $this->$name = $value;
            return $this;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name = null)
    {
        // Returns all property options
        if (null === $name) {
            return get_object_vars($this);
        }

        if (method_exists($this, $method = 'get' . $name)) {
            return $this->$method();
        } else {
            return isset($this->$name) ? $this->$name : null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->$name = $this->widget->get($name, array(), $this->deps);
    }
}
