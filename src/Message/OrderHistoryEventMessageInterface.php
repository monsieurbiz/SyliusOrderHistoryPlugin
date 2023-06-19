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

interface OrderHistoryEventMessageInterface
{
    public function getOrderId(): int;

    public function getType(): string;

    public function getLabel(): string;

    public function getCheckoutState(): ?string;

    public function getOrderState(): ?string;

    public function getPaymentState(): ?string;

    public function getShippingState(): ?string;

    public function getDetails(): array;

    public function getIp(): ?string;

    public function getFirewall(): ?string;

    public function getShopUser(): ?UserInterface;

    public function getAdminUser(): ?UserInterface;
}
