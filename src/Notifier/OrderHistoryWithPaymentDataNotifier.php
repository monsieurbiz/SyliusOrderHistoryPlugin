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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Notifier;

use Sylius\Component\Core\Model\OrderInterface;

final class OrderHistoryWithPaymentDataNotifier extends AbstractOrderHistoryNotifier implements OrderHistoryNotifierInterface
{
    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void
    {
        $payment = $order->getPayments()->last() ?: null;
        if (null !== $payment) {
            $details = [
                'state' => $payment->getState(),
                'method_code' => $payment->getMethod()?->getCode(),
                'method_name' => $payment->getMethod()?->getName(),
                'details' => $payment->getDetails(),
                'amount' => $payment->getAmount(),
            ];
        }

        parent::notifyEvent($order, $type, $label, $details);
    }
}
