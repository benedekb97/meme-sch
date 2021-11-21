<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211121161432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_518B7ACFA76ED395F8697D13 ON votes (user_id, comment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_518B7ACFA76ED3954B89032C ON votes (user_id, post_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_518B7ACFA76ED395F8697D13 ON votes');
        $this->addSql('DROP INDEX UNIQ_518B7ACFA76ED3954B89032C ON votes');
    }
}
