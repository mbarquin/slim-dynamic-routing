<?php
/**
 * Controllers interface file
 * SlimDR Factory checks its implementation
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
 * Controller interface
 * SlimDR Factory checks its implementation
 */
interface ControllerInterface
{
    /**
     * Receives container to inject it on controller object
     * 
     * @param \Interop\Container\ContainerInterface $container
     * 
     * @return void
     * @access public
     */
    public function __construct(\Interop\Container\ContainerInterface $container);
    
    /**
     * Returns referenced container object
     * 
     * @return \Interop\Container\ContainerInterface
     * @acess public
     */
    public function getContainer();
}

