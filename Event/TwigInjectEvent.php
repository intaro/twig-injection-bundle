<?php

namespace Intaro\TwigInjectionBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class TwigInjectEvent extends Event
{
    private $parameters;
    private $injections = [];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function addInjection(TwigInjectItemInterface $item)
    {
        $this->injections[] = $item;
    }

    public function getInjections()
    {
        usort($this->injections, function($a, $b) {
            if ($a->getPriority() == $b->getPriority()) {
                return 0;
            }

            return ($a->getPriority() < $b->getPriority()) ? -1 : 1;
        });

        return $this->injections;
    }

    public function setInjections(array $injections)
    {
        $this->injections = $injections;
    }
}