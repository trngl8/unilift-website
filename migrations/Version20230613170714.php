<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613170714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fix page table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE page ADD slug VARCHAR(65) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE page DROP slug');
    }
}
