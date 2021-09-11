<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906221438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL, 
                email VARCHAR(128) NOT NULL, 
                password VARCHAR(128) NOT NULL, 
                salt VARCHAR(32) NOT NULL, 
                created_at DATETIME NOT NULL,
                constraint email_unique
                unique (email),
                PRIMARY KEY(id)
            ) 
        ');
    }

    public function down(Schema $schema): void
    {

    }
}
