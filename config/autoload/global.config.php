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

$blogName = 'igncode';

$config = array(
    'di' => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'blog' => array(
                            'options' => array(
                                'defaults' => array(
                                    'blog_name' => $blogName,
                                ),
                            ),
                        ),
                        'blog-post' => array(
                            'options' => array(
                                'defaults' => array(
                                    'blog_name' => $blogName,
                                ),
                            ),
                        ),
                        'blog-author' => array(
                            'options' => array(
                                'route' => '/blog/author/:authorName',
                                'constraints' => array(
                                    'authorName' => '[a-zA-Z0-9_-]+',
                                ),
                                'defaults' => array(
                                    'controller' => 'index',
                                    'action'	 => 'blog-author',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);

unset($blogName);
return $config;
