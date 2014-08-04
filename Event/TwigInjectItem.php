<?php

namespace Intaro\TwigInjectionBundle\Event;

/**
 * Injects the twig method calling
 */
abstract class TwigInjectItem implements TwigInjectItemInterface
{
    protected $priority;

    public function __construct($priority = 0)
    {
        $this->priority = (int) $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
    }
}
