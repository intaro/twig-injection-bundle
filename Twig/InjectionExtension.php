<?php
namespace Intaro\TwigInjectionBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Intaro\TwigInjectionBundle\Event\TwigInjectEvent;
use Intaro\TwigInjectionBundle\Event\TwigInjectInclude;
use Intaro\TwigInjectionBundle\Event\TwigInjectRender;

class InjectionExtension extends Twig_Extension
{
    private $fragment;
    private $dispatcher;

    public function __construct(FragmentHandler $fragment, EventDispatcherInterface $dispatcher)
    {
        $this->fragment = $fragment;
        $this->dispatcher = $dispatcher;
    }

    public function getFunctions()
    {
        return array(
            'inject' => new Twig_SimpleFunction('inject', [$this, 'inject'], [
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => array('all'),
            ]),
        );
    }

    public function inject(\Twig_Environment $env, $context, $eventName, array $parameters = [])
    {
        $event = new TwigInjectEvent($parameters);

        $event = $this->dispatcher->dispatch($eventName, $event);

        return $this->render($env, $context, $event);
    }

    private function render(\Twig_Environment $env, $context, TwigInjectEvent $event)
    {
        $content = '';

        if (sizeof($injections = $event->getInjections())) {
            foreach ($injections as $item) {
                if ($item instanceof TwigInjectInclude) {
                    $content .= $env->resolveTemplate($item->getTemplate())->render(array_merge($context, $item->getParameters()));
                    continue;
                }

                if ($item instanceof TwigInjectRender) {
                    $content .= $this->fragment->render(
                        new ControllerReference($item->getController(), $item->getAttributes(), $item->getQuery()),
                        $item->getStrategy()
                    );
                    continue;
                }
            }
        }

        return $content;
    }

    public function getName()
    {
        return 'twig_injection_extension';
    }
}
