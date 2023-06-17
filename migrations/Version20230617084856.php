<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230617084856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'delivery_name in order';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders ADD delivery_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders DROP delivery_name');
    }
}
