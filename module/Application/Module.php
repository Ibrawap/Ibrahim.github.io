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
        $view->plugin('headLink')->appendStylesheet($basePath . '/css/styles.css');

        $html5js = '<script src="' . $basePath . '/js/html5.js"></script>';
        $view->plugin('placeHolder')->__invoke('html5js')->set($html5js);
        $favicon = '<link rel="shortcut icon" href="' . $basePath . '/images/favicon.ico">';
        $view->plugin('placeHolder')->__invoke('favicon')->set($favicon);

        $this->view = $view;
        return $view;
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
