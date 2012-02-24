<?php

namespace Application;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    protected $view;
    protected $viewListener;

    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeNavigation'));
        $events->attach('Zend\Mvc\Application', 'dispatch', array($this, 'setMatchedRouteNavigation'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $basePath     = $app->getRequest()->getBasePath();
        $locator      = $app->getLocator();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('url')->setRouter($app->getRouter());
        $renderer->doctype()->setDoctype('HTML5');
        $renderer->plugin('basePath')->setBasePath($basePath);
    }

    public function initializeNavigation($e)
    {
        $app = $e->getParam('application');
        $router = $app->getRouter();
        $container = new \Zend\Navigation\Navigation(array(
            array(
                'label' => 'Home',
                'id' => 'home',
            	'uri' => $router->assemble(array(), array('name'=> 'home')),
            ),
            array(
                'label' => 'Blog',
                'id' => 'blog',
                'uri' => $router->assemble(array(), array('name'=> 'blog')),
            ),
            array(
                'label' => 'Jobs',
                'id' => 'jobs',
                'uri' => $router->assemble(array(), array('name'=> 'jobs')),
            ),
            array(
                'label' => 'People',
                'id' => 'people',
                'uri' => $router->assemble(array(), array('name'=> 'people')),
            ),
        ));
        \Zend\Registry::set('Zend_Navigation', $container);
    }

    public function setMatchedRouteNavigation($e)
    {
        $container = \Zend\Registry::get('Zend_Navigation');
        if ($container instanceof \Zend\Navigation\Navigation) {
            $routeMatch = $e->getParam('route-match');
            $matchedRouteName = $routeMatch->getMatchedRouteName();
            $page = $container->findOneBy('id', $matchedRouteName);
            if ($page) {
                $page->setActive(true);
            }
        }
    }
}
