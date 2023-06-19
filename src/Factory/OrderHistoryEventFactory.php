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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Factory;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class OrderHistoryEventFactory implements OrderHistoryEventFactoryInterface
{
    public function __construct(protected FactoryInterface $factory)
    {
    }

    public function createNew(): OrderHistoryEventInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->factory->createNew();
    }

    public function createForOrder(OrderInterface $order): OrderHistoryEventInterface
    {
        $entity = $this->createNew();
        $entity->setOrder($order);
        $entity->setPayment($order->getPayments()->last() ?: null);
        $entity->setShipment($order->getShipments()->last() ?: null);
        $entity->setOrderState($order->getState());
        $entity->setPaymentState($order->getPaymentState());
        $entity->setShippingState($order->getShippingState());

        return $entity;
    }
}
