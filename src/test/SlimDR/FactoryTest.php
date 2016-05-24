<?php

namespace mbarquin\SlimDR;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-05-23 at 15:18:58.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Factory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Factory::slim();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers mbarquin\SlimDR\Factory::slim
     * @todo   Implement testSlim().
     */
    public function testSlimReturnFactory()
    {
        $this->assertInstanceOf('\\mbarquin\\SlimDR\\Factory', $this->object);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::__construct
     * @todo   Implement testSlim().
     */
    public function testSlimConstruct()
    {
        $this->assertInstanceOf('\\mbarquin\\SlimDR\\Factory', $this->object);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::slim
     * @todo   Implement testSlim().
     */
    public function testSlimAcceptsSlim()
    {
        $slim = new \Slim\App();
        $slim->group('/users', function () {
            $this->get('/reset-password', function ($request, $response, $args) {
                // Code here.
            })->setName('user-password-reset');
        });

        $oFact = Factory::slim($slim);
        $this->assertAttributeInstanceOf('\\Slim\\App', 'slimApp', $oFact);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::slim
     * @todo   Implement testSlim().
     */
    public function testSlimAcceptsSlimAlreadySetted()
    {
        $slim = new \Slim\App();
        $slim->group('/users', function () {
            $this->get('/reset-password', function ($request, $response, $args) {
                // Code here.
            })->setName('user-password-reset');
        });

        $oFact         = Factory::slim($slim);
        $slimProcessed = $oFact->withGroup('admin')->getApp();
        $container     = $slimProcessed->getContainer();
        $routes        = $container->get('router')->getRoutes();
        $rout          = array_values($routes);
        $group         = $rout[0]->getGroups();
        $group2        = $rout[1]->getGroups();

        $this->assertAttributeContains('/users', 'pattern', $group[0]);
        $this->assertAttributeContains('/admin', 'pattern', $group2[0]);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::withGroup
     * @todo   Implement testWithGroup().
     */
    public function testWithGroup()
    {
        // Remove the following lines when you implement this test.
        $slimProcessed = $this->object->withGroup('testgroup')->getApp();
        $container     = $slimProcessed->getContainer();

        $routes        = $container->get('router')->getRoutes();
        $rout          = array_values($routes);
        $group         = $rout[0]->getGroups();

        $this->assertAttributeContains('/testgroup', 'pattern', $group[0]);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::withVersionGroup
     */
    public function testWithVersionGroupOnly()
    {
        // Remove the following lines when you implement this test.
        $oFact = Factory::slim()->withVersionGroup('testsubgroup');

        $this->assertAttributeContains('testsubgroup', 'versionGroup', $oFact);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::withVersionGroup
     * @todo   Implement testWithVersionGroup().
     */
    public function testWithNoGroups()
    {
        // Remove the following lines when you implement this test.
        $slimProcessed = Factory::slim()->getApp();
        $container     = $slimProcessed->getContainer();

        $routes        = $container->get('router')->getRoutes();
        $rout          = array_values($routes);

        $this->assertCount(0, $rout[0]->getGroups());
    }


    /**
     * @covers mbarquin\SlimDR\Factory::withContainer
     * @todo   Implement testWithContainer().
     */
    public function testWithContainer()
    {
        // Remove the following lines when you implement this test.
        $settings = [
            'settings' => [
                'displayErrorDetails' => true, // set to false in production
                'app.basename' => '\\Testbasename'
            ],

        ];

        $slimProcessed     = $this->object->withContainer($settings)->getApp();
        $container         = $slimProcessed->getContainer();
        $settingsProcessed = $container->get('settings');

        $this->assertEquals(
            $settingsProcessed['app.basename'], $settings['settings']['app.basename']
        );

    }

    /**
     * @covers mbarquin\SlimDR\Factory::withContainer
     * @expectedException InvalidArgumentException
     */
    public function testWithContainerAlreadySlimCreatedException()
    {
        // Remove the following lines when you implement this test.
        $settings = [
            'settings' => [
                'displayErrorDetails' => true, // set to false in production
                'app.basename' => '\\Testbasename'
            ],
        ];

        $slim  = new \Slim\App();
        $oFact = Factory::slim($slim);

        $oFact->withContainer($settings)->getApp();

    }

    /**
     * @covers mbarquin\SlimDR\Factory::withNamespace
     * @todo   Implement testWithNamespace().
     */
    public function testWithNamespace()
    {
        $oApp = $this->object->withNamespace('\\test\\namespace');

        $this->assertAttributeEquals('\\test\\namespace', 'namespace', $oApp);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::getApp
     * @todo   Implement testGetApp().
     */
    public function testGetApp()
    {
        $confSlim = $this->object
                ->withGroup('api')
                ->withVersionGroup('v1')
                ->withNamespace('\\mbarquin\\Controllers')
                ->getApp();

        $this->assertInstanceOf('\\Slim\\App', $confSlim);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::getApp
     * @todo   Implement testGetApp().
     */
    public function testGetAppVersionGroupOnly()
    {
        $slimProcessed = $this->object
                ->withVersionGroup('testsubgroup')
                ->withNamespace('\\mbarquin\\Controllers')
                ->getApp();

        $container = $slimProcessed->getContainer();

        $routes    = $container->get('router')->getRoutes();
        $rout      = array_values($routes);
        $group     = $rout[0]->getGroups();

        $this->assertAttributeContains('/testsubgroup', 'pattern', $group[0]);
    }

    /**
     * @covers mbarquin\SlimDR\Factory::setMap
     * @todo   Implement testSetMap().
     */
    public function testSetMap()
    {
        $settings = [
            'settings' => [
                'displayErrorDetails' => true, // set to false in production
                'app.basename' => '\\Testbasename'
            ],
        ];

        $confSlim = $this->object
                ->withGroup('api')
                ->withVersionGroup('v1')
                ->withContainer($settings)
                ->withNamespace('\\mbarquin\\SlimDR\\Test\\Controller')
                ->getApp();

        $env = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/api/v1/test/param1/param2'
        ]);

        $request = \Slim\Http\Request::createFromEnvironment($env);

        $response  = new \Slim\Http\Response();
        $container = $confSlim->getContainer();
        $container['db'] = function ($c) {
            $db = new \stdClass();

            return $db;
        };
        //$routes    = $container->get('router')->getRoutes();
        //$rout      = array_values($routes);
        //$request   = $request->withAttribute('router', $rout[0]);
        $resp      = $confSlim->process($request, $response);

        //$this->assertTrue(isset($resp));

    }

    /**
     * @covers mbarquin\SlimDR\Factory::getArgs
     * @todo   Implement testGetArgs().
     */
    public function testGetArgs()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
