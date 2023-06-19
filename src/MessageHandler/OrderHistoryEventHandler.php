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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\MessageHandler;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Message\OrderHistoryEventMessageInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderHistoryEventHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FactoryInterface $orderHistoryEventFactory,
    ) {
    }

    public function __invoke(OrderHistoryEventMessageInterface $message): void
    {
        /** @var OrderHistoryEventInterface $orderHistoryEvent */
        $orderHistoryEvent = $this->orderHistoryEventFactory->createNew();
        $orderHistoryEvent->setOrderId($message->getOrderId());
        $orderHistoryEvent->setType($message->getType());
        $orderHistoryEvent->setLabel($message->getLabel());
        $orderHistoryEvent->setCheckoutState($message->getCheckoutState());
        $orderHistoryEvent->setOrderState($message->getOrderState());
        $orderHistoryEvent->setShippingState($message->getShippingState());
        $orderHistoryEvent->setPaymentState($message->getPaymentState());
        $orderHistoryEvent->setDetails($message->getDetails());
        $orderHistoryEvent->setIp($message->getIp());
        $orderHistoryEvent->setFirewall($message->getFirewall());
        $orderHistoryEvent->setShopUser($message->getShopUser());
        $orderHistoryEvent->setAdminUser($message->getAdminUser());

        $this->entityManager->persist($orderHistoryEvent);
        $this->entityManager->flush();
    }
}
