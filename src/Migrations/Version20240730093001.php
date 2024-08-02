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

namespace MonsieurBiz\SyliusOrderHistoryPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240730093001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event DROP FOREIGN KEY FK_9EA44E026352511C');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event DROP FOREIGN KEY FK_9EA44E02A45D93BF');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E026352511C FOREIGN KEY (admin_user_id) REFERENCES sylius_admin_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E02A45D93BF FOREIGN KEY (shop_user_id) REFERENCES sylius_shop_user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event DROP FOREIGN KEY FK_9EA44E02A45D93BF');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event DROP FOREIGN KEY FK_9EA44E026352511C');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E02A45D93BF FOREIGN KEY (shop_user_id) REFERENCES sylius_shop_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E026352511C FOREIGN KEY (admin_user_id) REFERENCES sylius_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
