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

use MonsieurBiz\SyliusOrderHistoryPlugin\Message\OrderHistoryEventMessage;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;

final class OrderHistoryNotifier implements OrderHistoryNotifierInterface
{
    public const SHOP_FIREWALL = 'shop';

    public const ADMIN_FIREWALL = 'admin';

    public function __construct(
        private MessageBusInterface $messageBus,
        private RequestStack $requestStack,
        private FirewallMapInterface $firewallMap,
        private UserContextInterface $userContext,
    ) {
    }

    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void
    {
        if (null === $order->getId()) {
            return;
        }

        $user = $this->userContext->getUser();

        $firewall = $this->getFirewall();
        $message = new OrderHistoryEventMessage(
            $order->getId(),
            $type,
            $label,
            $order->getCheckoutState(),
            $order->getState(),
            $order->getPaymentState(),
            $order->getShippingState(),
            $details,
            $this->getIp(),
            $firewall,
            self::SHOP_FIREWALL === $firewall ? $user : null,
            self::ADMIN_FIREWALL === $firewall ? $user : null,
        );

        $this->messageBus->dispatch($message);
    }

    private function getIp(): ?string
    {
        $request = $this->requestStack->getMainRequest();

        return $request?->getClientIp();
    }

    private function getFirewall(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return null;
        }

        /** @phpstan-ignore-next-line */
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);

        return $firewallConfig?->getName();
    }
}
