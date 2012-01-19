<?php
require_once __DIR__ . '/../vendor/ZendFramework/library/Zend/Loader/StandardAutoloader.php';
$autoloader = new Zend\Loader\StandardAutoloader;
$autoloader->register();

use Zend\Cache\PatternFactory;

class MyObject
{
    public function doResourceIntensiveStuff($arg1, $arg2, $arg3)
    {
        return "$arg1:$arg2:$arg3";
    }
}

$object      = new MyObject();
$objectProxy = PatternFactory::factory('object', array(
    'object'  => $object,
    'cacheOutput' => false,
    'storage' => array(
    	'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'cache_dir' => __DIR__ . '/../data/cache',
            	'namespace' => 'object-cache-test',
            ),
        ),
    ),
));

// Calls and caches $object->doResourceIntensiveStuff with three arguments
// and returns result
$result = $objectProxy->doResourceIntensiveStuff('argument1', 'argument2', 'argumentN');
\Zend\Debug::dump($result);

$result = $objectProxy->doResourceIntensiveStuff('argument1', 'argument2', 'argumentN');
\Zend\Debug::dump($result);
