<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211123201858 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAFE54D947 FOREIGN KEY (group_id) REFERENCES `groups` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_885DBAFAFE54D947 ON posts (group_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAFE54D947');
        $this->addSql('DROP INDEX IDX_885DBAFAFE54D947 ON posts');
        $this->addSql('ALTER TABLE posts DROP group_id');
    }
}
