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

interface OrderHistoryNotifierInterface
{
    public function notifyEvent(OrderInterface $order, string $type, string $label, array $details = []): void;
}
