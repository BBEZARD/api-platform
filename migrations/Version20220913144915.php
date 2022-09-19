<?php

declare( strict_types=1 );

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220913144915 extends AbstractMigration {
	public function getDescription(): string {
		return '';
	}

	public function up( Schema $schema ): void {
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql( 'CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, birth_date DATETIME DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, address VARCHAR(127) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, paid_at DATETIME DEFAULT NULL, payment INT NOT NULL, receipt INT NOT NULL, workshop INT NOT NULL, my_workshop VARCHAR(255) NOT NULL, INDEX IDX_90D3F06075E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE receipt (id INT AUTO_INCREMENT NOT NULL, adherent_id INT NOT NULL, donation INT NOT NULL, number INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, donation_letter VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5399B64596901F54 (number), INDEX IDX_5399B64525F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB' );
		$this->addSql( 'ALTER TABLE adherent ADD CONSTRAINT FK_90D3F06075E23604 FOREIGN KEY (town_id) REFERENCES town (id)' );
		$this->addSql( 'ALTER TABLE receipt ADD CONSTRAINT FK_5399B64525F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)' );
	}

	public function down( Schema $schema ): void {
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql( 'ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F06075E23604' );
		$this->addSql( 'ALTER TABLE receipt DROP FOREIGN KEY FK_5399B64525F06C53' );
		$this->addSql( 'DROP TABLE adherent' );
		$this->addSql( 'DROP TABLE receipt' );
		$this->addSql( 'DROP TABLE town' );
	}
}
