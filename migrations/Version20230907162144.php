<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230907162144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create cat picture table and kittie table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cat_picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, size NUMERIC(10, 0) NOT NULL)');
        $this->addSql('CREATE TABLE kittie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cat_picture_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, breed VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_7991F97833A7964E FOREIGN KEY (cat_picture_id) REFERENCES cat_picture (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7991F97833A7964E ON kittie (cat_picture_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cat_picture');
        $this->addSql('DROP TABLE kittie');
    }
}
