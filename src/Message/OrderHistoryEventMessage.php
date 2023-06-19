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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Message;

use Symfony\Component\Security\Core\User\UserInterface;

final class OrderHistoryEventMessage implements OrderHistoryEventMessageInterface
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private int $orderId,
        private string $type,
        private string $label,
        private ?string $checkoutState,
        private ?string $orderState,
        private ?string $paymentState,
        private ?string $shippingState,
        private array $details,
        private ?string $ip,
        private ?string $firewall,
        private ?UserInterface $shopUser = null,
        private ?UserInterface $adminUser = null,
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCheckoutState(): ?string
    {
        return $this->checkoutState;
    }

    public function getOrderState(): ?string
    {
        return $this->orderState;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function getShippingState(): ?string
    {
        return $this->shippingState;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getFirewall(): ?string
    {
        return $this->firewall;
    }

    public function getShopUser(): ?UserInterface
    {
        return $this->shopUser;
    }

    public function getAdminUser(): ?UserInterface
    {
        return $this->adminUser;
    }
}
