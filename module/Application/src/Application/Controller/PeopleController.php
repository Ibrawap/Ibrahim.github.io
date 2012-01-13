<?php

namespace Application\Controller;

use Zend\Mvc\MvcEvent,
    Application\Mvc\Controller\ActionController,
    IgnGravatar\Service\Profile as GravatarProfileService;

class PeopleController extends ActionController
{

    public function preDispatch(MvcEvent $e)
    {
        foreach ($this->people as $person) {
            //TODO: cache each persons gravatar info
        }
    }

    public function indexAction()
    {
        $people = array();
        foreach ($this->people as $email) {
            $gravatarProfileService = new GravatarProfileService();
            $people[$email] = $gravatarProfileService->get($email);
        }
        echo "<pre>";
        var_dump($people);
        echo "</pre>";
        die;
    }
}
