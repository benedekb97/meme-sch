<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211123162825 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA6E02DA83');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA6E02DA83 FOREIGN KEY (refusal_id) REFERENCES refusals (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA6E02DA83');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA6E02DA83 FOREIGN KEY (refusal_id) REFERENCES refusals (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
