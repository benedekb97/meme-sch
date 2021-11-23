<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20211123170156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_885DBAFA3DA5256D ON posts (image_id)');
        $this->addSql('ALTER TABLE users ADD profile_picture_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E98EB33F19 FOREIGN KEY (profile_picture_image_id) REFERENCES images (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_1483A5E98EB33F19 ON users (profile_picture_image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA3DA5256D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E98EB33F19');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP INDEX IDX_885DBAFA3DA5256D ON posts');
        $this->addSql('ALTER TABLE posts DROP image_id');
        $this->addSql('DROP INDEX IDX_1483A5E98EB33F19 ON users');
        $this->addSql('ALTER TABLE users DROP profile_picture_image_id');
    }
}
