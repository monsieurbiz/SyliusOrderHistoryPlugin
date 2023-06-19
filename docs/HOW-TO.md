## Add more details on existing events

If you want to add more details on existing events, you can decorate native notifiers.

```yaml
# config/services.yaml
services:
  App\Notifier\OrderHistoryWithAddressesDataNotifierDecorator:
    decorates: MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryWithAddressesDataNotifier
    arguments: [ '@.inner' ]
```

```php
<?php
declare(strict_types=1);

namespace App\Notifier;

use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryWithAddressesDataNotifier;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderHistoryWithAddressesDataNotifierDecorator
{
    public function __construct(private OrderHistoryWithAddressesDataNotifier $decorated)
    {
    }

    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void
    {
        $details['my_custom_label'] = 'my_custom_value';
        $this->decorated->notifyEvent($order, $type, $label, $details);
    }
}
```

## Add custom event in code

It could be useful to fire your own order timeline event. For example, you could notify when an order is sent to your
ERP or when emails are sent to your customers.

```php
<?php
declare(strict_types=1);

namespace App\EventListener;

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

There are two ways to add custom events in the state machine.

### Use basic notifier with "on-the-fly" details

This is the easiest way to add custom events in the state machine. You can use
basic [expression language](https://symfony.com/doc/current/reference/formats/expression_language.html)
and have access to two vars: `object` and `event`.

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

It would be useful to use your own notifier for more complex events and/or if you want to inject some services into your
notifier. Your notifier should implement the `OrderHistoryNotifierInterface`. Extending `AbstractOrderHistoryNotifier`
is recommended to avoid boilerplate.

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

You can now use your own notifier in the state machine.

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

## Add custom type and label display in history timeline

You could add a dedicated label and title display in the history timeline for your custom event type and event label.
There are native built-in templates for common labels like `new`, `completed` or `cancelled` as well as default ones
for unknown types and labels. However, if you want to add your own, you can do so by adding new templates in the
following paths:

* `templates/bundles/MonsieurBizSyliusOrderHistoryPlugin/Event/Type/{type}.html.twig`
* `templates/bundles/MonsieurBizSyliusOrderHistoryPlugin/Event/Label/{label}.html.twig`
