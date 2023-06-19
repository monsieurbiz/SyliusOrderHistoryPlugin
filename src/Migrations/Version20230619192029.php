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
final class Version20230619192029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_order_history_event (id INT AUTO_INCREMENT NOT NULL, admin_user_id INT DEFAULT NULL, shop_user_id INT DEFAULT NULL, order_id INT NOT NULL, shipment_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, checkout_state VARCHAR(255) NOT NULL, order_state VARCHAR(255) NOT NULL, payment_state VARCHAR(255) NOT NULL, shipping_state VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ip VARCHAR(40) DEFAULT NULL, firewall VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_9EA44E026352511C (admin_user_id), INDEX IDX_9EA44E02A45D93BF (shop_user_id), INDEX IDX_9EA44E028D9F6D38 (order_id), INDEX IDX_9EA44E027BE036FC (shipment_id), INDEX IDX_9EA44E024C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E026352511C FOREIGN KEY (admin_user_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E02A45D93BF FOREIGN KEY (shop_user_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E028D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E027BE036FC FOREIGN KEY (shipment_id) REFERENCES sylius_shipment (id)');
        $this->addSql('ALTER TABLE monsieurbiz_order_history_event ADD CONSTRAINT FK_9EA44E024C3A3BB FOREIGN KEY (payment_id) REFERENCES sylius_payment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE monsieurbiz_order_history_event');
    }
}
