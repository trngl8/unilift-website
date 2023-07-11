<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230617132242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename pages';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE page RENAME TO app_pages');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_pages RENAME TO page');
    }
}
