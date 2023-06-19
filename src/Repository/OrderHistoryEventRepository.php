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

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class OrderHistoryEventRepository extends EntityRepository implements OrderHistoryEventRepositoryInterface
{
    public function getByOrderId(int|string $orderId): array
    {
        /** phpstan-ignore-next-line */
        return $this->createQueryBuilder('ohe')
            ->andWhere('ohe.orderId = :orderId')
            ->setParameter('orderId', (int) $orderId)
            ->orderBy('ohe.createdAt', 'ASC')
            // When order state change in the same transaction, so at the same time as a checkout,
            // payment or shipping state, we want to display order state last.
            ->addOrderBy("FIELD(ohe.type, 'checkout', 'payment', 'shipment', 'order')")
            ->getQuery()
            ->getResult()
        ;
    }
}
