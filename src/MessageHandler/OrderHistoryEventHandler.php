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
use MonsieurBiz\SyliusOrderHistoryPlugin\Factory\OrderHistoryEventFactoryInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Message\OrderHistoryEventMessageInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderHistoryEventHandler implements MessageHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderHistoryEventFactoryInterface $orderHistoryEventFactory,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(OrderHistoryEventMessageInterface $message): void
    {
        /** @var ?OrderInterface $order */
        $order = $this->orderRepository->find($message->getOrderId());

        if (null === $order) {
            return;
        }

        $orderHistoryEvent = $this->orderHistoryEventFactory->createForOrder($order);
        $orderHistoryEvent->setType($message->getType());
        $orderHistoryEvent->setLabel($message->getLabel());
        $orderHistoryEvent->setCheckoutState($message->getCheckoutState());
        $orderHistoryEvent->setDetails($message->getDetails());
        $orderHistoryEvent->setIp($message->getIp());
        $orderHistoryEvent->setFirewall($message->getFirewall());
        $orderHistoryEvent->setShopUser($message->getShopUser());
        $orderHistoryEvent->setAdminUser($message->getAdminUser());
        $this->entityManager->persist($orderHistoryEvent);
        $this->entityManager->flush();
    }
}
