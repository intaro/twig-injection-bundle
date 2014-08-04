<?php

namespace Intaro\TwigInjectionBundle\Event;

/**
 * Injects the twig include calling
 */
class TwigInjectInclude extends TwigInjectItem
{
    private $template;
    private $parameters;

    public function __construct($template, array $parameters = [], $priority = 0)
    {
        $this->template = $template;
        $this->parameters = $parameters;

        parent::__construct($priority);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
}
