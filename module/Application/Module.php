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
        $events->attach('Zend\Mvc\Application', 'dispatch', array($this, 'initializeNavigation'));
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
        $locator      = $app->getLocator();
        $config       = $e->getParam('config');
        $view         = $this->getView($app);
        $viewListener = $this->getViewListener($view, $config);
        $app->events()->attachAggregate($viewListener);
        $events       = StaticEventManager::getInstance();
        $viewListener->registerStaticListeners($events, $locator);
    }

    protected function getViewListener($view, $config)
    {
        if ($this->viewListener instanceof View\Listener) {
            return $this->viewListener;
        }

        $viewListener       = new View\Listener($view, $config->layout);
        $viewListener->setDisplayExceptionsFlag($config->display_exceptions);

        $this->viewListener = $viewListener;
        return $viewListener;
    }

    protected function getView($app)
    {
        if ($this->view) {
            return $this->view;
        }

        $locator = $app->getLocator();
        $view    = $locator->get('view');
        $url     = $view->plugin('url');
        $url->setRouter($app->getRouter());

        $view->plugin('doctype')->setDoctype(\Zend\View\Helper\Doctype::HTML5);

        $view->plugin('headTitle')->setSeparator(' - ')
                                  ->setAutoEscape(false)
                                  ->append('code.ign');

        $basePath = $app->getRequest()->getBaseUrl();

        $view->plugin('headLinkLess')->appendStylesheetLess($basePath . '/css/less/bootstrap.less');

        $html5js = '<script src="' . $basePath . '/js/html5.js"></script>';
        $view->plugin('placeHolder')->__invoke('html5js')->set($html5js);
        $favicon = '<link rel="shortcut icon" href="' . $basePath . '/images/favicon.ico">';
        $view->plugin('placeHolder')->__invoke('favicon')->set($favicon);

        $this->view = $view;
        return $view;
    }

    public function initializeNavigation($e)
    {
        $router = $e->getParam('router');
        $routeMatch = $e->getParam('route-match');
        $matchedRouteName = $routeMatch->getMatchedRouteName();
        $container = new \Zend\Navigation\Navigation(array(
            array(
                'label' => 'Home',
            	'uri' => $router->assemble(array(), array('name'=> 'home')),
                'active' => 'home' === $matchedRouteName,
            ),
            array(
                'label' => 'Blog',
                'uri' => $router->assemble(array(), array('name'=> 'blog')),
            	'active' => 'blog' === $matchedRouteName,
            ),
            array(
                'label' => 'API',
                'uri' => $router->assemble(array(), array('name'=> 'api')),
            	'active' => 'api' === $matchedRouteName,
            ),
            array(
                'label' => 'Jobs',
                'uri' => $router->assemble(array(), array('name'=> 'jobs')),
            	'active' => 'jobs' === $matchedRouteName,
            ),
            array(
                'label' => 'People',
                'uri' => $router->assemble(array(), array('name'=> 'people')),
                'active' => 'people' === $matchedRouteName,
            ),
        ));
        \Zend\Registry::set('Zend_Navigation', $container);
    }
}
