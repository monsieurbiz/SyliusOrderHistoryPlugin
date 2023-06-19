<p align="center">
    <a href="https://monsieurbiz.com" target="_blank">
        <img src="https://monsieurbiz.com/logo.png" width="250px" alt="Monsieur Biz logo" />
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="https://monsieurbiz.com/agence-web-experte-sylius" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" width="200px" alt="Sylius logo" />
    </a>
    <br/>
    <img src="https://monsieurbiz.com/assets/images/sylius_badge_extension-artisan.png" width="100" alt="Monsieur Biz is a Sylius Extension Artisan partner">
</p>

<h1 align="center">Order History for Sylius</h1>

[![Order History Plugin license](https://img.shields.io/github/license/monsieurbiz/SyliusOrderHistoryPlugin?public)](https://github.com/monsieurbiz/SyliusOrderHistoryPlugin/blob/master/LICENSE)
[![Tests Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusOrderHistoryPlugin/tests.yml?branch=master&logo=github)](https://github.com/monsieurbiz/SyliusOrderHistoryPlugin/actions?query=workflow%3ATests)
[![Security Status](https://img.shields.io/github/actions/workflow/status/monsieurbiz/SyliusOrderHistoryPlugin/security.yml?branch=master&label=security&logo=github)](https://github.com/monsieurbiz/SyliusOrderHistoryPlugin/actions?query=workflow%3ASecurity)

This plugin saves order events and allows you to display them in the order history as a timeline.

![Demo of the Order History](docs/images/demo.png)

## Installation


Install the plugin via composer:

```bash
composer require monsieurbiz/sylius-order-history-plugin
```

<!-- The section on the flex recipe will be displayed when the flex recipe will be available on contrib repo
<details><summary>For the installation without flex, follow these additional steps</summary>
-->

Change your `config/bundles.php` file to add this line for the plugin declaration:

```php
<?php

return [
    //..
    MonsieurBiz\SyliusOrderHistoryPlugin\MonsieurBizSyliusOrderHistoryPlugin::class => ['all' => true],
];
```

Copy the plugin configuration files in your `config` folder:

```bash
cp -Rv vendor/monsieurbiz/sylius-order-history-plugin/recipes/1.0/config/ config
```

## Add custom events in code

```php
<?php
declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener;

use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use App\Erp\ErpClient;

final class OrderEventListener
{
    public function __construct(
        private OrderHistoryNotifierInterface $notifier,
        private ErpClient $erpClient,
    ){
    }

    public function onOrderItemAdded(ResourceControllerEvent $event): void
    {
        $order = $event->getSubject();
        $erpOrderNumber = $this->erpClient->sendOrder($order);
        
        # Use the event notifier with your own information
        $this->notifier->notifyEvent($order, 'erp', 'send_order', [
            'erp_order_number' => $erpOrderNumber,
        ]);
    }
}
```

## Add custom event in state machine

There is two ways to add custom events in state machine.

### Use basic notifier with "on-the-fly" details

This is the easiest way to add custom events in state machine. You can use basic [expression language](https://symfony.com/doc/current/reference/formats/expression_language.html)
and have acces to two vars: `object` and `event`.

```yaml
winzou_state_machine:
  sylius_order_checkout:
    callbacks:
      after:
        app_order_history_notify_custom:
          on: 'address'
          do: [ '@MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifier', 'notifyEvent' ]
          args:
            - 'object'
            - '"my_type"'
            - '"my_custom_event"'
            - |
              {
                  email: object.getCustomer().getEmail(),
                  amount: object.getTotal(),
              }
```

### Using your own notifier

It would be useful to use your own notifier for more complex events and/or if you want to inject some services in your notifier.
Your notifier had to implements `OrderHistoryNotifierInterface`. Extends `AbstractOrderHistoryNotifier` is recommended to avoid boilerplate.

```php
<?php

declare(strict_types=1);

namespace App\Notifier;

use Sylius\Component\Core\Model\OrderInterface;

final class MyCustomNotifier extends AbstractOrderHistoryNotifier implements OrderHistoryNotifierInterface
{
    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void
    {
        $details['email'] = $order->getCustomer()?->getEmail();
        $details['amount'] = $order->getCustomer()?->getEmail();
        parent::notifyEvent($order, $type, $label, $details);
    }
}
```

You can now use your own notifier in state machine.

```yaml
winzou_state_machine:
    sylius_order_checkout:
        callbacks:
            after:
                app_order_history_notify_custom:
                    on: 'address'
                    do: [ '@App\Notifier\MyCustomNotifier', 'notifyEvent' ]
                    args: [ 'object', '"my_type"', '"my_custom_event"' ]
```

### Add custom type and label display in history timeline

You could add dedicated label and title display in history timeline for your custom event type and event label. There's native builded 
templates for common label `new`, `completed` or `cancelled` and default ones for unkwnon types and labels. But if you want to add your 
own, you can do it by adding a new templates in the following paths:

* `templates/bundles/MonsieurBizSyliusOrderHistoryPlugin/Event/Type/{type}.html.twig`
* `templates/bundles/MonsieurBizSyliusOrderHistoryPlugin/Event/Label/{label}.html.twig`

## Contributing

You can find a way to run the plugin without effort in the file [DEVELOPMENT.md](./DEVELOPMENT.md).

Then you can open an issue or a Pull Request if you want! 😘  
Thank you!

## License

This plugin is completely free and released under the [MIT License](https://github.com/monsieurbiz/SyliusOrderHistoryPlugin/blob/master/LICENSE).
