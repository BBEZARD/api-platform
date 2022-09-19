<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914115336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE payment payment INT DEFAULT 0 NOT NULL, CHANGE receipt receipt INT DEFAULT 0 NOT NULL, CHANGE workshop workshop INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B64525F06C53');
        $this->addSql('DROP INDEX IDX_5399B64525F06C53 ON receipt');
        $this->addSql('ALTER TABLE receipt DROP adherent_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent CHANGE payment payment INT NOT NULL, CHANGE receipt receipt INT NOT NULL, CHANGE workshop workshop INT NOT NULL');
        $this->addSql('ALTER TABLE receipt ADD adherent_id INT NOT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B64525F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5399B64525F06C53 ON receipt (adherent_id)');
    }
}
