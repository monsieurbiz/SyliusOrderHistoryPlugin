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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface OrderHistoryEventRepositoryInterface extends RepositoryInterface
{
    public function getByOrderId(int|string $orderId): array;
}
