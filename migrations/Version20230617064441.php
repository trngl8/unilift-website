<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230617064441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fix relations and product';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT fk_74f52bb9166d1f9c');
        $this->addSql('ALTER TABLE app_orders DROP CONSTRAINT fk_2c906e53c674ee');
        $this->addSql('DROP SEQUENCE app_offers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_project_id_seq CASCADE');
        $this->addSql('DROP TABLE app_project');
        $this->addSql('DROP TABLE app_offers');
        $this->addSql('DROP INDEX idx_2c906e53c674ee');
        $this->addSql('ALTER TABLE app_orders DROP offer_id');
        $this->addSql('ALTER TABLE app_products ADD slug VARCHAR(64) DEFAULT NULL');
        $this->addSql('DROP INDEX idx_74f52bb9166d1f9c');
        $this->addSql('ALTER TABLE app_topics DROP project_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_offers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_project (id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(128) NOT NULL, description VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, close_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, icon VARCHAR(255) DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_project.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN app_project.start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN app_project.close_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_offers (id INT NOT NULL, title VARCHAR(255) NOT NULL, amount INT NOT NULL, currency VARCHAR(32) NOT NULL, active BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_offers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE app_orders ADD offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_orders ADD CONSTRAINT fk_2c906e53c674ee FOREIGN KEY (offer_id) REFERENCES app_offers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2c906e53c674ee ON app_orders (offer_id)');
        $this->addSql('ALTER TABLE app_products DROP slug');
        $this->addSql('ALTER TABLE app_topics ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT fk_74f52bb9166d1f9c FOREIGN KEY (project_id) REFERENCES app_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_74f52bb9166d1f9c ON app_topics (project_id)');
    }
}
