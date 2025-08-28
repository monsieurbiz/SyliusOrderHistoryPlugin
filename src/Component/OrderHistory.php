<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Component;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\TwigHooks\Twig\Component\HookableComponentTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

final class OrderHistory
{
    use HookableComponentTrait;
    use DefaultActionTrait;

    public ?int $orderId = null;

    public ?string $header = null;

    public function __construct(private readonly RepositoryInterface $orderHistoryEventRepository)
    {
    }


    #[ExposeInTemplate(name: 'order_history_events')]
    public function getOrderHistoryEvents(): array
    {
        return $this->orderHistoryEventRepository->findBy(['orderId' => $this->orderId]);
    }
}
