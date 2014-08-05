<?php

namespace Intaro\TwigInjectionBundle\Event;

/**
 * Injects the twig render() calling
 */
class TwigInjectRender extends TwigInjectItem
{
    private $controller;
    private $attributes;
    private $query;
    private $stategy;

    public function __construct(
        $controller,
        array $attributes = array(),
        $priority = 0,
        array $query = array(),
        $strategy = 'inline'
    ) {
        $this->controller = $controller;
        $this->attributes = $attributes;
        $this->query = $query;
        $this->strategy = $strategy;

        parent::__construct($priority);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery(array $query = [])
    {
        $this->query = $query;
    }

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setStrategy(array $strategy = [])
    {
        $this->strategy = $strategy;
    }
}
