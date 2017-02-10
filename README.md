# TwigInjectionBundle

The TwigInjectionBundle allows to inject twig templates through the event behavior.

## Installation

TwigInjectionBundle requires Symfony 2.8 or higher.

Require the bundle in your `composer.json` file:

````json
{
    "require": {
        "intaro/twig-injection-bundle": "~1.0.0",
    }
}
```

Register the bundle in `AppKernel`:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        //...

        new Intaro\TwigInjectionBundle\IntaroTwigInjectionBundle(),
    );

    //...
}
```

Install the bundle:

```
$ composer update intaro/twig-injection-bundle
```

## Usage

1) Add `{{ inject() }}` calling in template:

```twig
{{ inject('twig.injection.event.name', { parameter1: 'some-value', parameter2: some_object }) }}
```

2) Prepare controller action which you want to render or template which you want to include.

3) Define Listener which will inject `include` or `render` calling:

```php
<?php

namespace Acme\DemoBundle\EventListener;

use Intaro\TwigInjectionBundle\Event\TwigInjectEvent;
use Intaro\TwigInjectionBundle\Event\TwigInjectRender;

class TwigInjectionListener
{
    public function onSomeEvent(TwigInjectEvent $event)
    {
        $parameters = $event->getParameters();

        if (!isset($parameters['parameter1']) || 'some-value' !== $parameters['parameters1']) {
            return;
        }

        $render = new TwigInjectRender(
            'AcmeDemoBundle:DefaultController:index',
            [ 'object' => $parameters['parameters2'] ]
        );
        $event->addInjection($render);

        $include = new TwigInjectInclude('AcmeDemoBundle:Default:someTemplate.html.twig');
        $event->addInjection($include);
    }
}
```

4) Register the listener:

```yaml
services:
    acme_demo.twig_injection.listener:
        class: Acme\DemoBundle\EventListener\TwigInjectionListener
        tags:
            - { name: kernel.event_listener, event: twig.injection.event.name, method: onSomeEvent }

```
