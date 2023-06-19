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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Entity;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Security\Core\User\UserInterface as AdminUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as ShopUserInterface;

interface OrderHistoryEventInterface extends ResourceInterface, TimestampableInterface
{
    public const TYPE_CHECKOUT = 'checkout';

    public const TYPE_ORDER = 'order';

    public const TYPE_SHIPMENT = 'shipment';

    public const TYPE_PAYMENT = 'payment';

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getLabel(): ?string;

    public function setLabel(?string $label): void;

    public function getShopUser(): ?ShopUserInterface;

    public function setShopUser(?ShopUserInterface $shopUser): void;

    public function getAdminUser(): ?AdminUserInterface;

    public function setAdminUser(?AdminUserInterface $adminUser): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    public function getShipment(): ?ShipmentInterface;

    public function setShipment(?ShipmentInterface $shipment): void;

    public function getPayment(): ?PaymentInterface;

    public function setPayment(?PaymentInterface $payment): void;

    public function getCheckoutState(): ?string;

    public function setCheckoutState(?string $checkoutState): void;

    public function getOrderState(): ?string;

    public function setOrderState(?string $orderState): void;

    public function getPaymentState(): ?string;

    public function setPaymentState(?string $paymentState): void;

    public function getShippingState(): ?string;

    public function setShippingState(?string $shippingState): void;

    public function getDetails(): array;

    public function setDetails(array $details): void;

    public function getIp(): ?string;

    public function setIp(?string $ip): void;

    public function getFirewall(): ?string;

    public function setFirewall(?string $firewall): void;
}
