<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.  
 * You would place values in here that are agnostic to the environment and not 
 * sensitive to security. 
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source 
 * control, so do not include passwords or other sensitive information in this 
 * file.
 */

return array(
    'di'                    => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'blog' => array(
                            'options' => array(
                                'defaults' => array(
                                    'blog_name' => 'ign-tech',
                                ),
                            ),
                        ),
                        'blog-post' => array(
                            'options' => array(
                                'defaults' => array(
                                    'blog_name' => 'ign-tech',
                                ),
                            ),
                        ),
                        'blog-author' => array(
                            'options' => array(
                                'route' => '/people/:authorName',
                                'constraints' => array(
                                    'authorName' => '[a-zA-Z0-9_-]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'people',
                                    'action'	 => 'person',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
