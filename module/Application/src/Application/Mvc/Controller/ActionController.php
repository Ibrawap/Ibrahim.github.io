<?php

namespace Application\Mvc\Controller;

use Zend\Di\Definition\Annotation as Di,
    Zend\Mvc\MvcEvent,
    Zend\View\Renderer\PhpRenderer;

abstract class ActionController extends \Zend\Mvc\Controller\ActionController
{
    /**
     * @var Renderer
     */
    private $view;

    /**
     * @Di\Inject()
     */
    public function setView(PhpRenderer $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return PhpRenderer
     */
    public function getView()
    {
        if (!$this->view instanceof PhpRenderer) {
            throw new \RuntimeException(__CLASS__ . ' expects a Zend\View\Renderer\PhpRenderer to be injected by Di or otherwise');
        }
        return $this->view;
    }

    /**
     * Setting up pre / post dispatch events to be fired at 100 / -100
     *
     * @see Zend\Mvc\Controller.ActionController::attachDefaultListeners()
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();

        $events = $this->events();
        $events->attach('dispatch', array($this, 'preDispatch'), 100);
        $events->attach('dispatch', array($this, 'postDispatch'), -100);
    }

    /**
     * Can be overridden in concrete action controllers
     *
     * @param \Zend\Mvc\MvcEvent $e
     * @codeCoverageIgnore
     */
    public function preDispatch (MvcEvent $e)
    {
    }

    /**
     * Can be overridden in concrete action controllers
     *
     * @param \Zend\Mvc\MvcEvent $e
     * @codeCoverageIgnore
     */
    public function postDispatch (MvcEvent $e)
    {
    }
}
