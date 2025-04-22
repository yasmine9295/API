<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422115859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, code_commune VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, tel VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pret (id INT AUTO_INCREMENT NOT NULL, livre_id INT NOT NULL, adherent_id INT NOT NULL, date_pret DATETIME NOT NULL, date_retour_prevue DATETIME NOT NULL, date_retour_relle DATETIME DEFAULT NULL, INDEX IDX_52ECE97937D925CB (livre_id), INDEX IDX_52ECE97925F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97925F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB1401B063272');
        $this->addSql('DROP INDEX IDX_55AB1401B063272 ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE nationalite_id relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB1403256915B FOREIGN KEY (relation_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_55AB1403256915B ON auteur (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97937D925CB');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97925F06C53');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE pret');
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB1403256915B');
        $this->addSql('DROP INDEX IDX_55AB1403256915B ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE relation_id nationalite_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB1401B063272 FOREIGN KEY (nationalite_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_55AB1401B063272 ON auteur (nationalite_id)');
    }
}
