<?php
return array(
    'modules' => array(
        'Application',
        'IgnArticle',
        'IgnGravatar',
        'IgnLess',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => true,
        'cache_dir'            => 'data/cache',
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
