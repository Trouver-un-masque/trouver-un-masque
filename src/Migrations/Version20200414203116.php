<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200414203116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Création des entités de base de la plateforme';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles JSON NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            gsm VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', 
            adresse VARCHAR(180) DEFAULT NULL, 
            code_postal VARCHAR(10) DEFAULT NULL, 
            localite VARCHAR(180) DEFAULT NULL, 
            latitude DOUBLE PRECISION NOT NULL, 
            longitude DOUBLE PRECISION NOT NULL, 
            public_name VARCHAR(180) NOT NULL, 
            is_email_validated TINYINT(1) NOT NULL, 
            is_banned TINYINT(1) NOT NULL, 
            date_last_login DATETIME DEFAULT NULL, 
            can_move_to_producer TINYINT(1) NOT NULL, 
            is_personne_risque TINYINT(1) NOT NULL, 
            is_collectivite TINYINT(1) NOT NULL, 
            is_takeaway TINYINT(1) NOT NULL, 
            rayon_distribution INT DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME NOT NULL, 
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), 
            PRIMARY KEY(id)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE masque (id INT AUTO_INCREMENT NOT NULL, 
            demandeur_id INT DEFAULT NULL, 
            lot_id INT DEFAULT NULL, 
            is_demande_spontanee TINYINT(1) NOT NULL, 
            is_delivered TINYINT(1) NOT NULL, 
            INDEX IDX_E2D3F6AB95A6EE59 (demandeur_id), 
            INDEX IDX_E2D3F6ABA8CBA5F7 (lot_id), 
            PRIMARY KEY(id)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lot (id INT AUTO_INCREMENT NOT NULL, 
            producteur_id INT NOT NULL, status INT NOT NULL, 
            is_delivered TINYINT(1) NOT NULL, 
            date_production_prevue DATE NOT NULL, 
            date_production_prete DATE DEFAULT NULL, 
            prix DOUBLE PRECISION DEFAULT NULL, 
            instructions_paiement LONGTEXT DEFAULT NULL, 
            is_banned TINYINT(1) NOT NULL, 
            quantite_prevue INT NOT NULL, 
            quantite_prete INT DEFAULT NULL, 
            is_materiel_requis TINYINT(1) NOT NULL, 
            materiel_requis LONGTEXT DEFAULT NULL, 
            type INT NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME NOT NULL, 
            INDEX IDX_B81291BAB9BB300 (producteur_id), 
            PRIMARY KEY(id)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE masque ADD CONSTRAINT FK_E2D3F6AB95A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE masque ADD CONSTRAINT FK_E2D3F6ABA8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id)');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291BAB9BB300 FOREIGN KEY (producteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE masque DROP FOREIGN KEY FK_E2D3F6AB95A6EE59');
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291BAB9BB300');
        $this->addSql('ALTER TABLE masque DROP FOREIGN KEY FK_E2D3F6ABA8CBA5F7');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE masque');
        $this->addSql('DROP TABLE lot');
    }
}
