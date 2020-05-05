<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200504185924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__issue_votes AS SELECT user_id, amount FROM issue_votes');
        $this->addSql('DROP TABLE issue_votes');
        $this->addSql('CREATE TABLE issue_votes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:uuid)
        , amount INTEGER NOT NULL, issue_id CHAR(36) NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('INSERT INTO issue_votes (user_id, amount) SELECT user_id, amount FROM __temp__issue_votes');
        $this->addSql('DROP TABLE __temp__issue_votes');
        $this->addSql('CREATE UNIQUE INDEX issue_user ON issue_votes (user_id, issue_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX issue_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__issue_votes AS SELECT user_id, amount FROM issue_votes');
        $this->addSql('DROP TABLE issue_votes');
        $this->addSql('CREATE TABLE issue_votes (user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , amount INTEGER NOT NULL, PRIMARY KEY(user_id))');
        $this->addSql('INSERT INTO issue_votes (user_id, amount) SELECT user_id, amount FROM __temp__issue_votes');
        $this->addSql('DROP TABLE __temp__issue_votes');
    }
}
