<?php

/*
 * This file is part of Monsieur Biz' Order History plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final class OrderEventListener
{
    public function __construct(private OrderHistoryNotifierInterface $notifier)
    {
    }

    public function onOrderItemAdded(ResourceControllerEvent $event): void
    {
        /** @var ?OrderItemInterface $item */
        $item = $event->getSubject();
        /** @var ?OrderInterface $order */
        $order = $item?->getOrder();
        if (null === $order) {
            return;
        }

        $this->notifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'item_added', [
            'product_sku' => $item?->getVariant()?->getProduct()?->getCode() ?? 'unknow',
            'qty' => $item?->getQuantity() ?? 'unknow',
        ]);
    }
}
