slim-dynamic-routing
===

Slim 3 Dynamic routing setup with a Factory object
-------

I'm actually using Slim 3 for prototyping my "for fun" applications, often I prefer using a dynamic router and avoid generating an entry for each time I add a new action. I had a piece of code which I used to setup the Slim 3 framework router for instancing a controller depending on called route. I was passing the container DI to this controller and getting in each method all needed dependencies, but in the end I will be always depending on Container interface. Now I had rewritten the code to improve its reusability and testing, I have implemented a channel to feed dependencies through the parent controller for avoiding such a container dependance.

The base idea is a Factory object which can instanciate and setup an slim application, this Slim application will be ready to instance controller classes with an especific implementation, this allows router to inject dependencies on controllers.

Installation
----
You can install the component in the following ways:

* Use the official Github repository (https://github.com/mbarquin/slim-dynamic-routing)
* Use composer : composer require mbarquin/slim-dynamic-routing


Usage
----
After requiring autoload, with an static call we can ask the Factory object to make a Slim App setup and retrieve a ready-to-use Slim app instance.


```php
    require __DIR__ . '/../vendor/autoload.php';
    $settings = require __DIR__ . '/../src/settings.php';

    $app = \mbarquin\SlimDR\Factory::slim()
            ->withGroup('api')
            ->withVersionGroup('v1')
            ->withContainer($settings)
            ->withNamespace('\\MyApp\\Controller')
            ->getApp();

    $app->run();
```

Static method *slim()* returns the Factory object, and each next call will return the same object to continue with the setup process. Methods like withGroup, withVersionGroup, withContainer and withNamespace all return the Factory object itself, allowing concatenate functions on it. Function getApp finally returns a new \Slim\App object.

* **slim(\Slim\App $app=null)**
It creates and retunr a new Factory instance, if a \Slim\App is provided as param it keeps a reference of it (in testing process) in order to setup this instance, not a new one when getApp() is called.

* **withGroup($mainGroup)**
It sets up the main group name to be used as prefix on these calls, http://www.myserver.com/api/controller, this function returns the Factory object itself. If not setted, routes will use version group or nothing as routes prefix

* **withVersionGroup($versionGroup)**
It sets up the secondary group name to be used as subprefix on these calls, http://www.myserver.com/api/v1/controller, this function returns the Factory object itself. If not setted, routes will use main group as prefix http://www.myserver.com/api/controller, or nothing http://www.myserver.com/controller .

* **withContainer($settings)**
Mandatory. It's used to ask Factory object to inject a config array or container on \Slim\App constructor.

* **withNamespace('\\\\MyApp\\\\Controller')**
Mandatory. It's used to autload controllers using its namespace. These controllers must extend \mbarquin\SlimDR\ParentController or implement \mbarquin\SlimDR\ControllerInterface as explained below.

* **getApp()**
Instances and returns a new \Slim\App Instance with all previous params implemented. (In testing process) If in initial static method slim($app) a Slim\App object was provided as param, this function will setup this instance and return it.

Now you only have to extend the SlimDR ParentController on your controllers or implement **ControllerInterface** by yourself on your parent controller. This implementation is intended to avoid *Service Location anti-pattern*, our controllers must provide a way of communicating which are their dependencies. In this way our controllers will not depend on containers, only on the services we really need to use.

Slim will search and call a function named like the method used in call, if it is a method GET call, function get will be the one called on controller. If the call is via PUT method, slim will try to raise put function in controller.

```php
    /**
     * Test Controller file
     * SlimDR example controller
     *
     * PHP version 5.6
     *
     *
     * @category   SlimDR
     * @package    Test
     * @subpackage Controller
     * @author     Moises Barquin Salgado <moises.barquin@gmail.com>
     * @copyright  Moises Barquin Salgado 2016
     * @version    GIT: $Id$
     */

    namespace MyApp\Controller;

    use mbarquin\SlimDR\ParentController;

    /**
     * Class test, must implements Controller interface
     * It's extended from mbarquin\SlimDR\Parentcontroller
     */
    class test extends ParentController
    {
        /**
         * Array with actions dependencies, in the form [ method => [dependencies]]
         * @var type
         */
        protected $dependencies = array(
            self::GET => array ('db', 'logger')
        );

        /**
         * GET method as controller action,
         * Params which are not request, reponse and args must be declared on
         * dependencies array.
         *
         * @param Psr\Http\Message\ServerRequestInterface $request  Request object
         * @param Psr\Http\Message\ResponseInterface      $response Reponse object
         * @param array                                   $args     Request params
         * @param \stdClass                               $db       Database object
         * @param \Monolog\Logger                         $logger   Logger object
         */
        public function get($request, $response, $args, \stdClass $db, \Monolog\Logger $logger)
        {
            print_r($args);
        }
    }
```

Controller protected property $dependencies must be an array, the indexes must be the request method name, and its array contents must be the keys on the dependencies container to access the necessary object. Parent controller has all these methods declared (GET, POST, DELETE, etc...) as constants, so we can use them to define this array

```php
    $dependencies = array(
        self::POST => array('dep1', 'db', 'dep3'),
        self::GET  => array('dep1'),
        self::PUT  => array('dep3')
        ...
    );
```

These dependencies must be already injected to DI container, the key in the dependencies method array must be the key to localize the object in the container. Out of our controller, slim container must be setted to accomplish these dependencies.

```php
    $container       = $app->getContainer();

    $container['db'] = function ($c) {
        $db = new stdClass();

        return $db;
    };
```