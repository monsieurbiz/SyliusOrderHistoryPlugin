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

final class OrderHistoryWithAddressesDataNotifier extends AbstractOrderHistoryNotifier implements OrderHistoryNotifierInterface
{
    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void
    {
        $details['email'] = $order->getCustomer()?->getEmail();

        $shippingAddress = $order->getShippingAddress();
        if (null !== $shippingAddress) {
            $details['shipping_address'] = [
                'firstname' => $shippingAddress->getFirstName(),
                'lastname' => $shippingAddress->getLastName(),
                'company' => $shippingAddress->getCompany(),
                'street' => $shippingAddress->getStreet(),
                'city' => $shippingAddress->getCity(),
                'postcode' => $shippingAddress->getPostcode(),
                'country_code' => $shippingAddress->getCountryCode(),
                'phone_number' => $shippingAddress->getPhoneNumber(),
            ];
        }

        $billingAddress = $order->getBillingAddress();
        if (null !== $billingAddress) {
            $details['billing_address'] = [
                'firstname' => $billingAddress->getFirstName(),
                'lastname' => $billingAddress->getLastName(),
                'company' => $billingAddress->getCompany(),
                'street' => $billingAddress->getStreet(),
                'city' => $billingAddress->getCity(),
                'postcode' => $billingAddress->getPostcode(),
                'country_code' => $billingAddress->getCountryCode(),
                'phone_number' => $billingAddress->getPhoneNumber(),
            ];
        }

        parent::notifyEvent($order, $type, $label, $details);
    }
}
