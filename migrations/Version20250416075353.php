<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416075353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id SERIAL NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT fk_659a69d7a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT fk_659a69d7126f525e
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed DROP name
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_234044ABF47645AE ON feed (url)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C10862A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C1086251A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD title VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN user_feed.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C10862A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C1086251A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_item (user_id INT NOT NULL, item_id INT NOT NULL, PRIMARY KEY(user_id, item_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_659a69d7126f525e ON user_item (item_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_659a69d7a76ed395 ON user_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT fk_659a69d7a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT fk_659a69d7126f525e FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_234044ABF47645AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed ADD name VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT fk_59c10862a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT fk_59c1086251a5bc03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP title
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP updated_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT fk_59c10862a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT fk_59c1086251a5bc03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
