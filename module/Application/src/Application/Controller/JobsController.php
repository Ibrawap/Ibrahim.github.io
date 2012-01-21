<?php

namespace Application\Controller;

use Application\Mvc\Controller\ActionController,
    Zend\Http\Client,
    Zend\Mvc\MvcEvent;

class JobsController extends ActionController
{

    public function preDispatch (MvcEvent $e)
    {
        $locator = $this->getLocator();
        $cache = $locator->get('application-cache');
        
        if (false == ($xml = $cache->getItem('resumator-feed'))) {
            $client = new Client('http://app.theresumator.com/feeds/export/jobs/ign');
            $response = $client->send();
            if ($response->isSuccess()) {
                $xml = $response->getBody();
            } else {
                \Zend\Debug::dump($response);
                die;
                //TODO: better error handling
            }
            $cache->setItem('resumator-feed', $xml);
        }
        
        $simpleXml = new \SimpleXMLElement($xml);
        \Zend\Debug::dump($simpleXml);
    }

    public function indexAction()
    {
        return array();
    }
}
