<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200531091650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE issue_tags (issue_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , tag_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(issue_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_375B5CEA5E7AA58C ON issue_tags (issue_id)');
        $this->addSql('CREATE INDEX IDX_375B5CEABAD26311 ON issue_tags (tag_id)');
        $this->addSql('DROP INDEX issue_user');
        $this->addSql('DROP INDEX IDX_C301139C5E7AA58C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__issue_votes AS SELECT id, issue_id, user_id, amount FROM issue_votes');
        $this->addSql('DROP TABLE issue_votes');
        $this->addSql('CREATE TABLE issue_votes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, issue_id CHAR(36) DEFAULT NULL COLLATE BINARY --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , amount INTEGER NOT NULL, CONSTRAINT FK_C301139C5E7AA58C FOREIGN KEY (issue_id) REFERENCES issues (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO issue_votes (id, issue_id, user_id, amount) SELECT id, issue_id, user_id, amount FROM __temp__issue_votes');
        $this->addSql('DROP TABLE __temp__issue_votes');
        $this->addSql('CREATE UNIQUE INDEX issue_user ON issue_votes (user_id, issue_id)');
        $this->addSql('CREATE INDEX IDX_C301139C5E7AA58C ON issue_votes (issue_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE issue_tags');
        $this->addSql('DROP INDEX IDX_C301139C5E7AA58C');
        $this->addSql('DROP INDEX issue_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__issue_votes AS SELECT id, issue_id, user_id, amount FROM issue_votes');
        $this->addSql('DROP TABLE issue_votes');
        $this->addSql('CREATE TABLE issue_votes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, issue_id CHAR(36) DEFAULT NULL --(DC2Type:uuid)
        , user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , amount INTEGER NOT NULL)');
        $this->addSql('INSERT INTO issue_votes (id, issue_id, user_id, amount) SELECT id, issue_id, user_id, amount FROM __temp__issue_votes');
        $this->addSql('DROP TABLE __temp__issue_votes');
        $this->addSql('CREATE INDEX IDX_C301139C5E7AA58C ON issue_votes (issue_id)');
        $this->addSql('CREATE UNIQUE INDEX issue_user ON issue_votes (user_id, issue_id)');
    }
}
