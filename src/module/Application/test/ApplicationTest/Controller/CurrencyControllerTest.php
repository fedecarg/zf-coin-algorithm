<?php

namespace ApplicationTest\Controller;

use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\Parameters;

use PHPUnit_Framework_TestCase;

use ApplicationTest\Bootstrap;
use Application\Controller\CurrencyController;

class CurrencyControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new CurrencyController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function validDataProvider()
    {
        // input, pence (canonical)
        return array(
            array('4',         4),
            array('85',        85),
            array('197p',      197),
            array('2p',        2),
            array('1.87',      187),
            array('£1.23',     123),
            array('£2',        200),
            array('£10',       1000),
            array('1.87p',     187),
            array('£1p',       100),
            array('£1.p',      100),
            array('001.41p',   141),
            array('4.235p',    424),
            array('£1.25742p', 126)
        );
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testValidUserInput($userInput, $expectedOutput)
    {
        $this->routeMatch->setParam('action', 'index');

        $params = new Parameters(array('amount' => $userInput));
        $this->request->setMethod('POST')->setPost($params);

        $viewModel = $this->controller->dispatch($this->request);

        $this->assertEquals($expectedOutput, $viewModel->pence);
    }

    public function invalidDataProvider()
    {
        // input, pence (canonical)
        return array(
            array('1x',     0),
            array('£1x.0p', 0),
            array('£p',     0)
        );
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidUserInput($userInput, $expectedOutput)
    {
        $this->routeMatch->setParam('action', 'index');

        $params = new Parameters(array('amount' => $userInput));
        $this->request->setMethod('POST')->setPost($params);

        $viewModel = $this->controller->dispatch($this->request);

        $this->assertEquals($expectedOutput, $viewModel->pence);
    }
}
