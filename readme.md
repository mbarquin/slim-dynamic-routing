slim-dynamic-routing
===

Slim 3 Dynamic routing setup with a Factory object
-------

I'm actually using Slim 3 for prototyping my "for fun" applications, often I prefer using a dynamic router and avoid generating an entry for each time I add a new action. I had a piece of code which I used to setup the Slim 3 framework router for instancing a controller depending on called route. I was passing the container DI to this controller and getting in each method all needed dependencies, but in the end I will be always depending on Container interface. Now I had rewritten the code to improve its reusability and testing, I have implemented a channel to feed dependencies through the parent controller constructor and avoiding such a container dependance.

The base idea is a Factory object which can instanciate and setup an slim application, this Slim application will be ready to instance controller classes with an especific implementation, this allows router to inject dependencies on controllers.

Installation
----
You can install the component in the following ways:

* Use the official Github repository (https://github.com/mbarquin/slim-dynamic-router)
* Use composer : composer require mbarquin/slim-dynamic-router --dev


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

Static method *slim()* returns the Factory object, and each next call will return the same object to continue with the setup proccess. Methods like withGroup, withVersionGroup, withContainer and withNamespace all return the Factory object itself, allowing concatenate functions on it. Function getApp finally returns a new \Slim\App object.

* **withGroup($mainGroup)**
It sets up the main group name to be used as prefix on these calls, http://www.myserver.com/api/controller, this function returns the Factory object itself. If not setted, routes will use version group or nothing as routes prefix

* **withVersionGroup($versionGroup)**
It sets up the secondary group name to be used as subprefix on these calls, http://www.myserver.com/api/v1/controller, this function returns the Factory object itself. If not setted, routes will use main group as prefix http://www.myserver.com/api/controller, or nothing http://www.myserver.com/controller .

* **withContainer($settings)**
Mandatory. It's used to ask Factory object to inject a config array or container on \Slim\App constructor.

* **withNamespace($settings)**
Mandatory. It's used to autload controllers using its namespace, Controller classes namespace must be provided. This controllers must extend \mbarquin\SlimDR\ParentController or implement ControllerInterface as explained below.

* **getApp()**
Instances and returns a new \Slim\App Instance with all previous config implemented.