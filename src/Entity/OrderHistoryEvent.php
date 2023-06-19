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
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Security\Core\User\UserInterface as AdminUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as ShopUserInterface;

class OrderHistoryEvent implements OrderHistoryEventInterface
{
    use TimestampableTrait;

    private ?int $id = null;

    private ?string $type = null;

    private ?string $label = null;

    private ?ShopUserInterface $shopUser = null;

    private ?AdminUserInterface $adminUser = null;

    private ?OrderInterface $order = null;

    private ?ShipmentInterface $shipment = null;

    private ?PaymentInterface $payment = null;

    private ?string $checkoutState = null;

    private ?string $orderState = null;

    private ?string $paymentState = null;

    private ?string $shippingState = null;

    private array $details = [];

    private ?string $ip = null;

    private ?string $firewall = null;

    /**
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getShopUser(): ?ShopUserInterface
    {
        return $this->shopUser;
    }

    public function setShopUser(?ShopUserInterface $shopUser): void
    {
        $this->shopUser = $shopUser;
    }

    public function getAdminUser(): ?AdminUserInterface
    {
        return $this->adminUser;
    }

    public function setAdminUser(?AdminUserInterface $adminUser): void
    {
        $this->adminUser = $adminUser;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getShipment(): ?ShipmentInterface
    {
        return $this->shipment;
    }

    public function setShipment(?ShipmentInterface $shipment): void
    {
        $this->shipment = $shipment;
    }

    public function getPayment(): ?PaymentInterface
    {
        return $this->payment;
    }

    public function setPayment(?PaymentInterface $payment): void
    {
        $this->payment = $payment;
    }

    public function getCheckoutState(): ?string
    {
        return $this->checkoutState;
    }

    public function setCheckoutState(?string $checkoutState): void
    {
        $this->checkoutState = $checkoutState;
    }

    public function getOrderState(): ?string
    {
        return $this->orderState;
    }

    public function setOrderState(?string $orderState): void
    {
        $this->orderState = $orderState;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function setPaymentState(?string $paymentState): void
    {
        $this->paymentState = $paymentState;
    }

    public function getShippingState(): ?string
    {
        return $this->shippingState;
    }

    public function setShippingState(?string $shippingState): void
    {
        $this->shippingState = $shippingState;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): void
    {
        $this->details = $details;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getFirewall(): ?string
    {
        return $this->firewall;
    }

    public function setFirewall(?string $firewall): void
    {
        $this->firewall = $firewall;
    }
}
