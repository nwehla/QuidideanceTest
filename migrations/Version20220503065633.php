<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220503065633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE interroger (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, repondant VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, acceptationpartagedonnee TINYINT(1) DEFAULT NULL, datecreation DATETIME DEFAULT NULL, datemiseajour DATETIME DEFAULT NULL, datefermeture DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sondage (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, question VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, multiple TINYINT(1) DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, messagefermeture LONGTEXT DEFAULT NULL, datecreation DATETIME DEFAULT NULL, datemiseajour DATETIME DEFAULT NULL, datedefermeture DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON DEFAULT NULL, datecreation DATETIME DEFAULT NULL, datemiseajour DATETIME DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE interroger');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE sondage');
        $this->addSql('DROP TABLE utilisateur');
    }
}
