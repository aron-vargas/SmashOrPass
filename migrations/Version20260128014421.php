<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260128014421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY `FK_2091C9AD47A475AB`');
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY `FK_2091C9AD9D86650F`');
        $this->addSql('DROP INDEX IDX_2091C9AD9D86650F ON user_vote');
        $this->addSql('DROP INDEX IDX_2091C9AD47A475AB ON user_vote');
        $this->addSql('ALTER TABLE user_vote ADD user_id INT NOT NULL, ADD candidate_id INT NOT NULL, DROP user_id_id, DROP candidate_id_id');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT FK_2091C9AD91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('CREATE INDEX IDX_2091C9ADA76ED395 ON user_vote (user_id)');
        $this->addSql('CREATE INDEX IDX_2091C9AD91BD8781 ON user_vote (candidate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY FK_2091C9ADA76ED395');
        $this->addSql('ALTER TABLE user_vote DROP FOREIGN KEY FK_2091C9AD91BD8781');
        $this->addSql('DROP INDEX IDX_2091C9ADA76ED395 ON user_vote');
        $this->addSql('DROP INDEX IDX_2091C9AD91BD8781 ON user_vote');
        $this->addSql('ALTER TABLE user_vote ADD user_id_id INT NOT NULL, ADD candidate_id_id INT NOT NULL, DROP user_id, DROP candidate_id');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT `FK_2091C9AD47A475AB` FOREIGN KEY (candidate_id_id) REFERENCES candidate (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_vote ADD CONSTRAINT `FK_2091C9AD9D86650F` FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2091C9AD9D86650F ON user_vote (user_id_id)');
        $this->addSql('CREATE INDEX IDX_2091C9AD47A475AB ON user_vote (candidate_id_id)');
    }
}
