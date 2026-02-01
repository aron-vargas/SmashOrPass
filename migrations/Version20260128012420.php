<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260128012420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, birthdate DATE DEFAULT NULL, height VARCHAR(100) DEFAULT NULL, weight VARCHAR(100) NOT NULL, home_town VARCHAR(255) DEFAULT NULL, married TINYINT NOT NULL, income VARCHAR(255) DEFAULT NULL, political_affiliation VARCHAR(255) DEFAULT NULL, interests LONGTEXT DEFAULT NULL, lifestyle LONGTEXT DEFAULT NULL, additional_information LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE candidate_category (candidate_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A7125B1291BD8781 (candidate_id), INDEX IDX_A7125B1212469DE2 (category_id), PRIMARY KEY (candidate_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE gender (id INT AUTO_INCREMENT NOT NULL, sex VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(512) NOT NULL, nick_name VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_vote (id INT AUTO_INCREMENT NOT NULL, created_on DATETIME NOT NULL, modified_on DATETIME NOT NULL, smash TINYINT NOT NULL, user_id_id INT NOT NULL, candidate_id_id INT NOT NULL, INDEX IDX_2091C9AD9D86650F (user_id_id), INDEX IDX_2091C9AD47A475AB (candidate_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE candidate_category ADD CONSTRAINT FK_A7125B1291BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate_category ADD CONSTRAINT FK_A7125B1212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9AD9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9AD47A475AB FOREIGN KEY (candidate_id_id) REFERENCES candidate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidate_category DROP FOREIGN KEY FK_A7125B1291BD8781');
        $this->addSql('ALTER TABLE candidate_category DROP FOREIGN KEY FK_A7125B1212469DE2');
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY FK_2091C9AD9D86650F');
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY FK_2091C9AD47A475AB');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE candidate_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_vote');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
