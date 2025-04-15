<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415084232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item DROP CONSTRAINT fk_9f8cce498acbcdeb
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_9f8cce498acbcdeb
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item RENAME COLUMN feed_id_id TO feed_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item ADD CONSTRAINT FK_9F8CCE4951A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9F8CCE4951A5BC03 ON feed_item (feed_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item DROP CONSTRAINT FK_9F8CCE4951A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9F8CCE4951A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item RENAME COLUMN feed_id TO feed_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item ADD CONSTRAINT fk_9f8cce498acbcdeb FOREIGN KEY (feed_id_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9f8cce498acbcdeb ON feed_item (feed_id_id)
        SQL);
    }
}
