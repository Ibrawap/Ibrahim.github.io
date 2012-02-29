<?php

namespace Application\Controller;

use Application\Mvc\Controller\ActionController,
    Application\Model\Resumator\Feed as ResumatorFeed,
    Zend\Http\Client,
    Zend\Mvc\MvcEvent;

class JobsController extends ActionController
{

    protected $resumatorFeed;

    public function preDispatch (MvcEvent $e)
    {
        $locator = $this->getLocator();
        $cache = $locator->get('application-cache');
        
        if (false == ($xml = $cache->getItem('resumator-feed'))) {
            $client = new Client('http://app.theresumator.com/feeds/export/jobs/ign');
            $response = $client
                ->setAdapter('Zend\Http\Client\Adapter\Curl')
                ->send();
            if ($response->isSuccess()) {
                $xml = $response->getBody();
            } else {
                \Zend\Debug::dump($response);
                die;
                //TODO: better error handling
            }
            $cache->setItem('resumator-feed', $xml);
        }
        
        $this->resumatorFeed = new ResumatorFeed(new \SimpleXMLElement($xml));
    }

    public function indexAction()
    {
        return array('jobs' => $this->resumatorFeed->getJobsByFilter('department', 'Engineering'));
    }
}
