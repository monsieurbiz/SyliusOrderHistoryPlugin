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

use MonsieurBiz\SyliusOrderHistoryPlugin\Component\OrderHistory;
use MonsieurBiz\SyliusOrderHistoryPlugin\Repository\OrderHistoryEventRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

final class AddressHistoryTemplateTest extends KernelTestCase
{
    public function testOptionalAddressFieldsOmitBlankValuesButEscapePopulatedValues(): void
    {
        self::bootKernel();

        $twig = self::getContainer()->get(Environment::class);
        $template = '@MonsieurBizSyliusOrderHistoryPlugin/admin/order/history/address/optional_field.html.twig';
        $context = [
            'hookable_metadata' => [
                'context' => ['log' => ['data' => ['phoneNumber' => '']]],
                'configuration' => ['field' => 'phoneNumber', 'label' => 'sylius.ui.phone_number'],
            ],
        ];

        self::assertSame('', trim($twig->render($template, $context)));

        $context['hookable_metadata']['context']['log']['data']['phoneNumber'] = '<script>alert(1)</script>';
        $html = $twig->render($template, $context);
        self::assertStringContainsString('Phone number', $html);
        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        self::assertStringNotContainsString('<script>', $html);
    }

    public function testNativeAddressHooksAreScopedWithoutReplacingTheTimeline(): void
    {
        $hooks = Yaml::parseFile(\dirname(__DIR__, 2) . '/src/Resources/config/sylius/twig_hooks.yaml')['sylius_twig_hooks']['hooks'];

        self::assertArrayHasKey('sylius_admin.order.history.content.sections.timelines', $hooks);

        foreach (['billing_address', 'shipping_address'] as $address) {
            $prefix = 'sylius_admin.order.history.content.sections.addresses.' . $address . '.details';
            self::assertSame(
                '@MonsieurBizSyliusOrderHistoryPlugin/admin/order/history/address/data.html.twig',
                $hooks[$prefix]['data']['template'],
            );
            self::assertCount(4, $hooks[$prefix . '.data']);
        }
    }

    public function testTimelineIdentityEscapesUserName(): void
    {
        self::bootKernel();

        $html = self::getContainer()->get(Environment::class)->render(
            '@MonsieurBizSyliusOrderHistoryPlugin/admin/order/history/content/sections/timelines/order_history/main/identity.html.twig',
            [
                'hookable_metadata' => [
                    'context' => [
                        'event' => [
                            'shopUser' => null,
                            'adminUser' => ['id' => 1, 'username' => '<script>alert(1)</script>'],
                            'createdAt' => new \DateTimeImmutable(),
                            'ip' => null,
                            'firewall' => null,
                        ],
                    ],
                ],
            ],
        );

        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        self::assertStringNotContainsString('<script>alert(1)</script>', $html);
        self::assertStringContainsString('data-bs-title="Date"', $html);
        self::assertStringContainsString('data-bs-title="Time"', $html);
    }

    public function testTimelineUsesTheOrderSpecificRepositoryQuery(): void
    {
        $events = [new \stdClass(), new \stdClass()];
        $repository = $this->createMock(OrderHistoryEventRepositoryInterface::class);
        $repository->expects(self::once())->method('getByOrderId')->with(11)->willReturn($events);

        $component = new OrderHistory($repository);
        $component->orderId = 11;

        self::assertSame($events, $component->getOrderHistoryEvents());
    }

    public function testNestedDetailsRemainVisibleAndEscaped(): void
    {
        self::bootKernel();

        $html = self::getContainer()->get(Environment::class)->render(
            '@MonsieurBizSyliusOrderHistoryPlugin/admin/order/history/content/sections/timelines/order_history/main/content.html.twig',
            [
                'hookable_metadata' => [
                    'context' => [
                        'event' => [
                            'details' => [
                                'payment' => ['gateway' => ['transaction_id' => 'PAY-123', 'note' => '<script>alert(1)</script>']],
                            ],
                        ],
                    ],
                ],
            ],
        );

        self::assertStringContainsString('PAY-123', $html);
        self::assertStringContainsString('Transaction id', $html);
        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        self::assertStringNotContainsString('<script>alert(1)</script>', $html);
    }
}
