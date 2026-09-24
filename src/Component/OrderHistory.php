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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Component;

use MonsieurBiz\SyliusOrderHistoryPlugin\Repository\OrderHistoryEventRepositoryInterface;
use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

final class OrderHistory
{
    use DefaultActionTrait;
    use HookableComponentTrait;

    public ?int $orderId = null;

    public ?string $header = null;

    public function __construct(private readonly OrderHistoryEventRepositoryInterface $orderHistoryEventRepository)
    {
    }

    #[ExposeInTemplate(name: 'order_history_events')]
    public function getOrderHistoryEvents(): array
    {
        if (null === $this->orderId) {
            return [];
        }

        return $this->orderHistoryEventRepository->getByOrderId($this->orderId);
    }
}
