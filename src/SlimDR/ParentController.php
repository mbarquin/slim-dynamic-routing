<?php
/**
 * Controllers parent file,
 * implements SlimDR interface
 *
 * PHP version 5.6
 *
 *
 * @category   SlimDR
 * @package    Controller
 * @subpackage Parent
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
    /**
     * Dependencies index, all actions dependencies must be declared here.
     * @var array
     */
    protected $dependencies;

    /**
     * Methods as constants.
     */
    const POST    = 'post';
    const GET     = 'get';
    const DELETE  = 'delete';
    const OPTIONS = 'options';
    const PATCH   = 'patch';
    const PUT     = 'put';

    /**
     * Dependencies getter, it returns a dependencies array for each declared
     * method, if a method is not declarad, an empty array will be returned.
     *
     * @param string $method Name of the method
     * 
     * @return array
     */
    public function getDependencies($method)
    {
        if (isset($this->dependencies[$method]) === true
                && is_array($this->dependencies[$method]) === true) {
            return $this->dependencies[$method];
        } else {
            return array();
        }
    }

}

