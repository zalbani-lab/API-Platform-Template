<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211109141551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animation ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animation ADD CONSTRAINT FK_8D5284DCF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8D5284DCF675F31B ON animation (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `animation` DROP FOREIGN KEY FK_8D5284DCF675F31B');
        $this->addSql('DROP INDEX IDX_8D5284DCF675F31B ON `animation`');
        $this->addSql('ALTER TABLE `animation` DROP author_id');
    }
}
