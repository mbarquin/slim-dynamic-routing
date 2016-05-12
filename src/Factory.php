<?php
/**
 * Factory file class, it configures dynamic router options 
 * and returns a new Slim instance
 *
 */
namespace mbarquin\SlimDR;


/**
 * Factory class, it configures dynamic router options 
 * and returns a new Slim instance
 */
class Factory
{
    /**
     * Slim application object reference
     * 
     * @var \Slim\App
     */
    private $slimApp      = null;

    /**
     * Grouped routes, first level name
     *
     * @var strinvg
     */
    private $group        = null;

    /**
     * Grouped routes, second level name
     * 
     * @var string
     */
    private $versionGroup = null;

    /**
     * Container object reference or config array
     * to be injected into slim constructor
     * 
     * @var mixed
     */
    private $container    = array();

    /**
     * Namespace to be used on controllers autoloading
     *
     * @var string
     */
    private $namespace    = null;

    /**
     * Main factory constructor, protected to avoid direct instances
     *
     * @param \Slim\App $app Reference to a previously instanced Slim app
     *
     * @return void
     * @access protected
     */
    protected function __construct(\Slim\App $app = null)
    {
        if (is_a($app, '\\Slim\\App') === true) {
            $this->slimApp = $app;
        }
    }

    /**
     * Gets a copy of Factory object to perform parametrizations
     *
     * @param \Slim\App $app Reference to a previously instanced Slim app
     *
     * @return \MyApp\slimDR\Factory
     * @access public
     */
    static public function slim(\Slim\App $app = null) {
        $oFact = new Factory($app);

        return $oFact;
    }

    /**
     * Sets route main grouping option
     *
     * @param string $group Route group name
     *
     * @return \MyApp\slimDR\Factory
     * @access public
     */
    public function withGroup($group)
    {
        if (empty($group) === false) {
            $this->group = $group;
        }

        return $this;
    }

    /**
     * Sets a secondary group, intended to group api versions
     *
     * @param string $group Route group name
     * @return \MyApp\slimDR\Factory
     */
    public function withVersionGroup($group)
    {
        if (empty($group) === false) {
            $this->versionGroup = $group;
        }

        return $this;
    }

    /**
     * If no application has been instanced,
     * it sets config array or container to be used on slim app constructor
     *
     * @param mixed $container
     * 
     * @return \MyApp\slimDR\Factory
     * @access public
     * @throws InvalidArgumentException
     */
    public function withContainer($container = array())
    {
        if ($this->slimApp !== null) {
            throw new InvalidArgumentException(
                    'Container must be already injected to Slim'
            );
        }

        if($container !== null) {
            $this->container = $container;
        }

        return $this;
    }

    /**
     * Sets controllers namespace
     *
     * @param string $namespace Namespace to autoload controllers
     * 
     * @return \MyApp\slimDR\Factory
     */
    public function withNamespace($namespace)
    {
        if (empty($namespace) === false) {
            $this->namespace = $namespace;
        }

        return $this;
    }

    /**
     * If necessary, performs slim app creation and configures options in order
     * to use dinamic routing
     *
     * @return \Slim\App
     * @access public
     */
    public function getApp()
    {
        if($this->slimApp === null) {
            $this->slimApp = new \Slim\App($this->container);
        }

        // Sets main and secondary routing groups.
        if($this->group !== null) {
            $this->setGroups($this->slimApp, $this->group, $this->versionGroup);

        } elseif ($this->versionGroup !== null) {
            $this->setGroups($this->slimApp, $this->versionGroup);

        } else {
            // Sets dynamic routing for bare routes.
            \mbarquin\slimDR\Factory::setMap($this->slimApp);
        }

        return $this->slimApp;
    }

    /**
     * Sets routing groups to be used on dynamic routing
     *
     * @param \Slim\App $app      Reference to slim app
     * @param string    $group    Main group name
     * @param string    $subGroup Secondary group name
     *
     * @return void
     * @access protected
     */
    private function setGroups(\Slim\App $app, $group, $subGroup) {
        $ns = $this->namespace;
        $app->group('/'.$group, function () use ($subGroup, $ns) {
            // Version group
            if ($subGroup!== null) {
                $this->group('/'.$subGroup, function () use ($ns) {
                    \mbarquin\SlimDR\Factory::setMap($this, $ns);
                });// End api group.
            } else {
                \mbarquin\SlimDR\Factory::setMap($this, $ns);
            }
        });
    }

    /**
     * Sets main dynamic routing params into Slim app
     *
     * @param \Slim\App $app
     *
     * @return void
     */
    static public function setMap($app, $namespace)
    {
        $app->any('/{controller}[/{params:.*}]', function ($request, $response, $args) use($app, $namespace) {
            $contDI    = $app->getContainer();
            // Get dependencies.

            // Get called method
            $method = strtolower($request->getMethod());

            // Get config namespace if necessary
            $calledController = $namespace.'\\'.$args['controller'];
            try {
                $controller       = new $calledController($contDI);
                if (is_a($controller, "\\mbarquin\\SlimDR\\ControllerInterface") === false) {
                    throw new \Exception('Controller must implement \\mbarquin\\SlimDR\\ControllerInterface');
                }

                $funcArgs = array(
                    'request' => $request,
                    'reponse' => $response,
                    'args'    => split('/', $args['params'])
                );

                call_user_func_array(array($controller, $method.'Method'), $funcArgs);
            } catch(\Exception $excp) {
                if(in_array("\\mbarquin\\SlimDR\\ControllerInterface", class_implements($calledController)) === false) {
                    throw new \Exception('Controller must implement \\mbarquin\\SlimDR\\ControllerInterface');
                }
            }
        });
    }

}


