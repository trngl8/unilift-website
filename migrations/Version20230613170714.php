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
        return 'fix page table and older migrations';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE app_invites_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_invites DROP CONSTRAINT fk_ee23ea21ccfa12b8');
        $this->addSql('DROP TABLE app_invites');
        $this->addSql('ALTER TABLE page ADD slug VARCHAR(65) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_invites_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_invites (id INT NOT NULL, profile_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, lifetime INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ee23ea21ccfa12b8 ON app_invites (profile_id)');
        $this->addSql('COMMENT ON COLUMN app_invites.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE app_invites ADD CONSTRAINT fk_ee23ea21ccfa12b8 FOREIGN KEY (profile_id) REFERENCES app_profiles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page DROP slug');
    }
}
