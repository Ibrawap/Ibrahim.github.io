<?php
return array(
    'layout'                => 'layouts/layout.phtml',
    'display_exceptions'    => true,
    'di'                    => array(
        'instance' => array(
            'alias' => array(
                'index' => 'Application\Controller\IndexController',
                'error' => 'Application\Controller\ErrorController',
				'jobs' => 'Application\Controller\JobsController',
                'view'  => 'Zend\View\PhpRenderer',
                'application-cache' => 'Zend\Cache\Storage\Adapter\Filesystem',
                'application-cache-options' => 'Zend\Cache\Storage\Adapter\FilesystemOptions',
            ),
            'Zend\Cache\Storage\Adapter\Filesystem' => array(
                'parameters' => array(
                    'options' => 'application-cache-options',
                ),
             ),
            'Zend\Cache\Storage\Adapter\FilesystemOptions' => array(
                 'parameters' => array(
                     'options' => array(
                        'cache_dir' => __DIR__ . '/../../../data/cache',
                        'namespace' => 'code-ign-com',
                        'ttl' => 3600,
                    ),
                 ),
             ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'resolver' => 'Zend\View\TemplatePathStack',
                    'options'  => array(
                        'script_paths' => array(
                            'application' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'default' => array(
                            'type'    => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route'    => '/[:controller[/:action]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                    'controller' => 'index',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                        'home' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/',
                                'defaults' => array(
                                    'controller' => 'index',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                        'api' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/api',
                                'defaults' => array(
                                    'controller' => 'api',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                        'jobs' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/jobs',
                                'defaults' => array(
                                    'controller' => 'jobs',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
