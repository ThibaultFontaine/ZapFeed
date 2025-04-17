<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417084646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_item (user_id INT NOT NULL, item_id INT NOT NULL, has_been_read BOOLEAN NOT NULL, has_been_liked BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(user_id, item_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659A69D7A76ED395 ON user_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659A69D7126F525E ON user_item (item_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN user_item.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT FK_659A69D7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT FK_659A69D7126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT FK_659A69D7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT FK_659A69D7126F525E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_item
        SQL);
    }
}
