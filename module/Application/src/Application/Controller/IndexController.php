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

    public function barAction()
    {
        return new ViewModel();
    }
}
