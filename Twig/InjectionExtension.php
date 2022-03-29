<?php
namespace Intaro\TwigInjectionBundle\Twig;

use Intaro\TwigInjectionBundle\Event\TwigInjectEvent;
use Intaro\TwigInjectionBundle\Event\TwigInjectInclude;
use Intaro\TwigInjectionBundle\Event\TwigInjectRender;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class InjectionExtension extends AbstractExtension
{
    private $fragment;
    private $dispatcher;

    public function __construct(FragmentHandler $fragment, EventDispatcherInterface $dispatcher)
    {
        $this->fragment = $fragment;
        $this->dispatcher = $dispatcher;
    }

    public function getFunctions(): array
    {
        return [
            'inject' => new TwigFunction('inject', [$this, 'inject'], [
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => ['all'],
            ]),
        ];
    }

    public function inject(\Twig\Environment $env, $context, $eventName, array $parameters = [])
    {
        $event = new TwigInjectEvent($parameters);

        $event = $this->dispatcher->dispatch($event, $eventName);

        return $this->render($env, $context, $event);
    }

    private function render(\Twig\Environment $env, $context, TwigInjectEvent $event)
    {
        $content = '';

        if (\count($injections = $event->getInjections()) > 0) {
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

    public function getName(): string
    {
        return 'twig_injection_extension';
    }
}
