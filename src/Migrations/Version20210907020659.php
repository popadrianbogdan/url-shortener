<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907020659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE url (
                id INT AUTO_INCREMENT NOT NULL, 
                user_id INT DEFAULT NULL, 
                long_url VARCHAR(255) NOT NULL, 
                short_url VARCHAR(7) NOT NULL, 
                views INTEGER DEFAULT 0,
                created_at DATETIME NOT NULL, 
                INDEX IDX_F47645AEA76ED395 (user_id), 
                UNIQUE INDEX short_url_unique (short_url), 
                PRIMARY KEY(id)
            ) 
        ');
        $this->addSql('
            ALTER TABLE url 
                ADD CONSTRAINT FK_F47645AEA76ED395 
                FOREIGN KEY (user_id) 
                REFERENCES user (id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            DROP INDEX short_url_unique ON url
        ');
        $this->addSql('
            ALTER TABLE url DROP FOREIGN KEY (user_id)
        ');
        $this->addSql('DROP TABLE url');
    }
}
