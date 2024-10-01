<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\EventListener\Workflow;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEventInterface;
use MonsieurBiz\SyliusOrderHistoryPlugin\Notifier\OrderHistoryNotifierInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\CompletedEvent;

final class PaymentWorkflowEventListener
{
    public function __construct(
        private OrderHistoryNotifierInterface $orderHistoryWithPaymentDataNotifier,
    ) {
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.create')]
    public function new(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'new');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.process')]
    public function processing(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'processing');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.authorize')]
    public function authorized(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'authorized');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.complete')]
    public function completed(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'completed');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.fail')]
    public function failed(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'failed');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.cancel')]
    public function cancelled(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'cancelled');
    }

    #[AsEventListener(event: 'workflow.sylius_payment.completed.refund')]
    public function refunded(CompletedEvent $event): void
    {
        $payment = $event->getSubject();
        if (!$payment instanceof PaymentInterface) {
            return;
        }
        $order = $payment->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->orderHistoryWithPaymentDataNotifier->notifyEvent($order, OrderHistoryEventInterface::TYPE_PAYMENT, 'refunded');
    }
}
