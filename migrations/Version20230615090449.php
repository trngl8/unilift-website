<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615090449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE app_options_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_checks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_subscribes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_cards_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_answers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_results_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_cards DROP CONSTRAINT fk_d211d5304584665a');
        $this->addSql('ALTER TABLE app_results DROP CONSTRAINT fk_72fe645954731cde');
        $this->addSql('ALTER TABLE app_results DROP CONSTRAINT fk_72fe6459d23afaf');
        $this->addSql('ALTER TABLE app_options DROP CONSTRAINT fk_3d687aca727aca70');
        $this->addSql('ALTER TABLE app_answers DROP CONSTRAINT fk_bd8d464b1f55203d');
        $this->addSql('DROP TABLE app_subscribes');
        $this->addSql('DROP TABLE app_cards');
        $this->addSql('DROP TABLE app_results');
        $this->addSql('DROP TABLE app_options');
        $this->addSql('DROP TABLE app_checks');
        $this->addSql('DROP TABLE app_answers');
        $this->addSql('ALTER TABLE page ADD filename VARCHAR(255) DEFAULT NULL');
        //$this->addSql('ALTER TABLE page ALTER slug SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE app_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_checks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_subscribes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_cards_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_results_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_subscribes (id INT NOT NULL, type VARCHAR(255) NOT NULL, target INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_cards (id INT NOT NULL, product_id INT DEFAULT NULL, uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_sale DOUBLE PRECISION DEFAULT NULL, quantity INT DEFAULT NULL, available BOOLEAN DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, brand VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, weight INT DEFAULT NULL, country_code VARCHAR(2) DEFAULT NULL, period VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_d211d5304584665a ON app_cards (product_id)');
        $this->addSql('COMMENT ON COLUMN app_cards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_results (id INT NOT NULL, check_item_id INT NOT NULL, check_option_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value VARCHAR(255) DEFAULT NULL, is_valid BOOLEAN DEFAULT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_72fe6459d23afaf ON app_results (check_option_id)');
        $this->addSql('CREATE INDEX idx_72fe645954731cde ON app_results (check_item_id)');
        $this->addSql('COMMENT ON COLUMN app_results.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_options (id INT NOT NULL, parent_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, "position" INT NOT NULL, open BOOLEAN DEFAULT false NOT NULL, correct BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3d687aca727aca70 ON app_options (parent_id)');
        $this->addSql('CREATE TABLE app_checks (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_answers (id INT NOT NULL, topic_id INT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_bd8d464b1f55203d ON app_answers (topic_id)');
        $this->addSql('ALTER TABLE app_cards ADD CONSTRAINT fk_d211d5304584665a FOREIGN KEY (product_id) REFERENCES app_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_results ADD CONSTRAINT fk_72fe645954731cde FOREIGN KEY (check_item_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_results ADD CONSTRAINT fk_72fe6459d23afaf FOREIGN KEY (check_option_id) REFERENCES app_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_options ADD CONSTRAINT fk_3d687aca727aca70 FOREIGN KEY (parent_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_answers ADD CONSTRAINT fk_bd8d464b1f55203d FOREIGN KEY (topic_id) REFERENCES app_topics (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page DROP filename');
        //$this->addSql('ALTER TABLE page ALTER slug DROP NOT NULL');
    }
}
