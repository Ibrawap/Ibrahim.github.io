<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    IgnGravatar\Model\Profile as GravatarProfile,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{

    protected $myIgnToEmailAddress;

	/**
     * This setter should be populated by local di config
     */
    public function setMyIgnToEmailAddress(array $myIgnToEmailAddress)
    {
        $this->myIgnToEmailAddress = $myIgnToEmailAddress;
    }

    public function indexAction()
    {
        $routeMatch = $this->getEvent()->getRouteMatch();
        $renderer = $this->getLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headTitle($routeMatch->getParam('headTitle'));
        $renderer->headMeta($routeMatch->getParam('headMeta-description'), 'description');
        return new ViewModel();
    }

    public function blogAuthorAction()
    {
        $author = $this->getEvent()->getRouteMatch()->getParam('authorName');
        if (isset($this->myIgnToEmailAddress[$author])) {
            $locator = $this->getLocator();
            $gravatarProfileServiceProxy = $locator->get('ign-gravatar-profile-service');
            $gravatarProfile = $gravatarProfileServiceProxy->getByEmail($this->myIgnToEmailAddress[$author]);
            if ($gravatarProfile instanceof GravatarProfile) {
                $this->redirect()->toRoute('person', array('username' => $gravatarProfile->getPreferredUsername()));
                return;
            }
        }
        throw new \Exception('Profile not found');
    }
    
    public function fooAction()
    {
    	$routeMatch = $this->getEvent()->getRouteMatch();
        $renderer = $this->getLocator()->get('Zend\View\Renderer\PhpRenderer');
        $renderer->headTitle($routeMatch->getParam('headTitle'));
        $renderer->headMeta($routeMatch->getParam('headMeta-description'), 'description');
        return new ViewModel();
    }

    public function barAction()
    {
        return new ViewModel();
    }
}
