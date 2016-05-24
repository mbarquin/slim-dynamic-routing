<?php
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

namespace mbarquin\SlimDR\Test\Controller;

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
        self::GET => array ('db')
    );

    /**
     * Get Method as controller action,
     * Params which are not request, reponse and args must be declared on
     * dependencies array.
     *
     * @param Psr\Http\Message\ServerRequestInterface $request  Request object
     * @param Psr\Http\Message\ResponseInterface      $response Reponse object
     * @param array                                   $args     Request params
     * @param \stdClass                               $db       Database object
     * @param \Monolog\Logger                         $logger   Logger object
     */
    public function get($request, $response, $args, \stdClass $db)
    {
/*
        $body = $response->getBody();
        $body->write("Hello, ".$args[0]);
        var_dump( $body->getBody()->read($len));
        echo "ffffff".$response->getBody()->getContents();
                die();
 * */
        $data = array('name' => 'test', 'age' => 40);

        $newResponse->write(json_encode($data));

        return $newResponse;
    }
}