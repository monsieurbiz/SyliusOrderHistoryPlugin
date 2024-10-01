<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener\Workflow;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\CompletedEvent;

final class ShipmentWorkflowEventListener
{
    public function __construct(
        private OrderHistoryNotifierInterface $orderHistoryWithShipmentDataNotifier,
    ) {
    }

    #[AsEventListener(event: 'workflow.sylius_shipment.completed.create')]
    public function new(CompletedEvent $event): void
    {
        $shipment = $event->getSubject();
        if (!$shipment instanceof ShipmentInterface) {
            return;
        }
        $order = $shipment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithShipmentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_SHIPMENT, 'new');
    }

    #[AsEventListener(event: 'workflow.sylius_shipment.completed.cancel')]
    public function cancelled(CompletedEvent $event): void
    {
        $shipment = $event->getSubject();
        if (!$shipment instanceof ShipmentInterface) {
            return;
        }
        $order = $shipment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithShipmentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_SHIPMENT, 'cancelled');
    }

    #[AsEventListener(event: 'workflow.sylius_shipment.completed.ship')]
    public function shipped(CompletedEvent $event): void
    {
        $shipment = $event->getSubject();
        if (!$shipment instanceof ShipmentInterface) {
            return;
        }
        $order = $shipment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithShipmentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_SHIPMENT, 'shipped');
    }
}
