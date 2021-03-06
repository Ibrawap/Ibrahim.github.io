<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Zend\Mvc\Router\RouteStack' => array(
                    'instantiator' => array(
                        'Zend\Mvc\Router\Http\TreeRouteStack',
                        'factory'
                    ),
                ),
            ),
        ),
        'instance' => array(
            'alias' => array(
                'application-cache' => 'Zend\Cache\Storage\Adapter\Filesystem',
            ),

            // Inject the plugin broker for controller plugins into
            // the action controller for use by all controllers that
            // extend it.
            'Zend\Mvc\Controller\ActionController' => array(
                'parameters' => array(
                    'broker' => 'Zend\Mvc\Controller\PluginBroker',
                ),
            ),
            'Zend\Mvc\Controller\PluginBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\Mvc\Controller\PluginLoader',
                ),
            ),

            // Setup the View layer
            'Zend\View\Resolver\AggregateResolver' => array(
                'injections' => array(
                    'Zend\View\Resolver\TemplateMapResolver',
                    'Zend\View\Resolver\TemplatePathStack',
                ),
            ),
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths' => array(
                        'application' => __DIR__ . '/../view',
                    ),
                ),
            ),
            'Zend\View\Renderer\PhpRenderer' => array(
                'parameters' => array(
                    'resolver' => 'Zend\View\Resolver\AggregateResolver',
                ),
            ),
            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
                'parameters' => array(
                    'layoutTemplate' => 'layout/layout',
                ),
            ),
            'Zend\Mvc\View\ExceptionStrategy' => array(
                'parameters' => array(
                    'exceptionTemplate' => 'error/index',
                ),
            ),
            'Zend\Mvc\View\RouteNotFoundStrategy' => array(
                'parameters' => array(
                    'notFoundTemplate'      => 'error/404',
                ),
            ),

            // Setup the router and routes
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'default' => array(
                            'type' => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/[:controller[/:action]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                    'controller' => 'Application\Controller\IndexController',
                                    'action' => 'index',
                                ),
                            ),
                        ),
                        'home' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route' => '/',
                                'defaults' => array(
                                    'controller' => 'Application\Controller\IndexController',
                                    'action' => 'index',
                                    'headTitle' => 'IGN Engineering Blog, Jobs & Engineers',
                                    'headMeta-description' => 'This site contains the latest from IGN Engineering. Learn more about what we do, who we are, and how you can join our team!',
                                ),
                            ),
                        ),
                        'jobs' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/jobs',
                                'defaults' => array(
                                    'controller' => 'Application\Controller\JobsController',
                                    'action' => 'index',
                                    'headTitle' => 'IGN Engineering Jobs',
                                    'headMeta-description' => 'IGN is always hiring highly skilled software engineers in various disciplines. Check out our current openings or tell us why we need YOU on our team!',
                                ),
                            ),
                        ),
                        'foo' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/foo',
                                'defaults' => array(
                                    'controller' => 'Application\Controller\IndexController',
                                    'action' => 'foo',
                                    'headTitle' => 'IGN\'s Code-Foo',
                                    'headMeta-description' => 'Code-Foo is a six week program where you\'ll get to learn cutting-edge tech and have the opportunity to work on real IGN engineering projects.',
                                ),
                            ),
                        ),
                        'bar' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/bar',
                                'defaults' => array(
                                    'controller' => 'Application\Controller\IndexController',
                                    'action' => 'bar',
                                ),
                            ),
                        ),
                    ),
                ),
            ),

            // Setup the application cache
            'Zend\Cache\Storage\Adapter\Filesystem' => array(
                'parameters' => array(
                    'Zend\Cache\Storage\Adapter\Filesystem::__construct:options' => array(
                        'cache_dir' => __DIR__ . '/../../../data/cache',
                        'namespace' => 'code-ign-com',
                        'ttl' => 3600,
                    ),
                ),
            ),
        ),
    ),
);
