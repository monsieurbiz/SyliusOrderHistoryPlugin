<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener\Workflow;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\CompletedEvent;

final class OrderWorkflowEventListener
{
    public function __construct(
        private OrderHistoryNotifierInterface $orderHistoryNotifier,
    ) {
    }

    #[AsEventListener(event: 'workflow.sylius_order.completed.create')]
    public function new(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_ORDER, 'new');
    }

    #[AsEventListener(event: 'workflow.sylius_order.completed.cancel')]
    public function cancelled(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_ORDER, 'cancelled');
    }

    #[AsEventListener(event: 'workflow.sylius_order.completed.fulfill')]
    public function fulfilled(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_ORDER, 'fulfilled');
    }
}
