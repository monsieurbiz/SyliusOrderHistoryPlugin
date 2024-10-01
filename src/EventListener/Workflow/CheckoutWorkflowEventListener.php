<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener\Workflow;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\CompletedEvent;

final class CheckoutWorkflowEventListener
{
    public function __construct(
        private OrderHistoryNotifierInterface $orderHistoryWithAddressesDataNotifier,
        private OrderHistoryNotifierInterface $orderHistoryNotifier,
        private OrderHistoryNotifierInterface $orderHistoryWithShipmentDataNotifier,
        private OrderHistoryNotifierInterface $orderHistoryWithPaymentDataNotifier,
    ) {
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.addressed')]
    public function addressed(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithAddressesDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'addressed');
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.skip_shipping')]
    public function shippingSkipped(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'shipping_skipped');
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.select_shipping')]
    public function shippingSelected(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithShipmentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'shipping_selected');
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.skip_payment')]
    public function paymentSkipped(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'payment_skipped');
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.select_payment')]
    public function paymentSelected(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'payment_selected');
    }

    #[AsEventListener(event: 'workflow.sylius_order_checkout.completed.complete')]
    public function completed(CompletedEvent $event): void
    {
        $order = $event->getSubject();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_CHECKOUT, 'completed');
    }
}
