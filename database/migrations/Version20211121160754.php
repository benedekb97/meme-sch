<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211121160754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AFE0B3C67');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AFE0B3C67 FOREIGN KEY (reply_to_comment_id) REFERENCES comments (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AFE0B3C67');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AFE0B3C67 FOREIGN KEY (reply_to_comment_id) REFERENCES comments (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
