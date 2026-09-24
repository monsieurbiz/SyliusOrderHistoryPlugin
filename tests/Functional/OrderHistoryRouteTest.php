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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Tests\Functional;

use MonsieurBiz\SyliusOrderHistoryPlugin\Entity\OrderHistoryEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class OrderHistoryRouteTest extends KernelTestCase
{
    public function testLegacyAdminRouteRendersTheMigratedTimeline(): void
    {
        self::bootKernel();

        $route = self::getContainer()->get(RouterInterface::class)->getRouteCollection()->get('monsieurbiz_order_history_admin_event_index');
        self::assertNotNull($route);
        self::assertSame('/admin/order-history/events/{orderId}', $route->getPath());
        self::assertSame(['GET'], $route->getMethods());

        $event = new OrderHistoryEvent();
        $event->setType('payment');
        $event->setLabel('completed');
        $event->setDetails(['state' => 'completed']);
        $event->setCreatedAt(new \DateTimeImmutable());

        $html = self::getContainer()->get(Environment::class)->render($route->getDefault('_sylius')['template'], [
            'order_history_events' => [$event],
        ]);

        self::assertStringContainsString('Order Timeline', $html);
        self::assertStringContainsString('Payment', $html);
        self::assertStringContainsString('completed', $html);
    }
}
