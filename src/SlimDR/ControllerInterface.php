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
     * @param string HTTP Method used in call
     *
     * @return array()
     * @access public
     */
    public function getDependencies($method);
}

