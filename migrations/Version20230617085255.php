<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617085255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'location in delivery';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders ADD delivery_location VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders DROP delivery_location');
    }
}
