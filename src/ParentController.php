<?php
/**
 * Controllers parent file,
 * implements SlimDR interface
 *
 * PHP version 5.6
 *
 *
 * @category   SlimDR
 * @package    Interface
 * @subpackage Interface
 * @author     Moises Barquin Salgado <moises.barquin@gmail.com>
 * @copyright  Moises Barquin Salgado 2016
 * @version    GIT: $Id$
 */
namespace mbarquin\SlimDR;

/**
 * Controllers parent, 
 * implements SlimDR interface
 */
class ParentController implements ControllerInterface
{
    const POST = 'post';
    const GET  = 'GET';
    const OPTION  = 'OPTION';
    const DELETE  = 'DELETE';
    
    static public $dependencies = [];
    
    protected $container;

    static public function getDependencies($method)
    {
        if (isset(self::$dependencies[$method]) === true) {
            return self::$dependencies[$method];
        } else {
            return array();
        }
    }
    
    /**
     * Class constructor, DI container must be received.
     *
     * @param \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * DI container getter
     *
     * @return Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}

