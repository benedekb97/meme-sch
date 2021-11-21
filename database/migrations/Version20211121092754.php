<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211121092754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts ADD approved_by_user_id INT DEFAULT NULL, ADD anonymous TINYINT(1) NOT NULL, ADD approved_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA7BBF3A5D FOREIGN KEY (approved_by_user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_885DBAFA7BBF3A5D ON posts (approved_by_user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA7BBF3A5D');
        $this->addSql('DROP INDEX IDX_885DBAFA7BBF3A5D ON posts');
        $this->addSql('ALTER TABLE posts DROP approved_by_user_id, DROP anonymous, DROP approved_at');
    }
}
