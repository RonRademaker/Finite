<?php

namespace Finite;

use Finite\Event\FiniteEvents;
use Finite\Event\StateMachineEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * An Event-aware State machine.
 *
 * Uses the Symfony EventDispatcher Component
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class ListenableStateMachine extends \Finite\StateMachine
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @{inheritDoc}
     */
    public function initialize()
    {
        parent::initialize();
        $this->dispatcher->dispatch(FiniteEvents::INITIALIZE, new StateMachineEvent($this));
    }

    /**
     * @{inheritDoc}
     */
    public function apply($transitionName)
    {
        $this->dispatcher->dispatch(FiniteEvents::PRE_TRANSITION);
        $value = parent::apply($transitionName);
        $this->dispatcher->dispatch(FiniteEvents::POST_TRANSITION);

        return $value;
    }

}
