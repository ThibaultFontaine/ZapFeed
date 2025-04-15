<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415124234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE feed (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, url TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE item (id SERIAL NOT NULL, feed_id INT NOT NULL, title VARCHAR(255) NOT NULL, url TEXT NOT NULL, description TEXT DEFAULT NULL, media_link TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1F1B251E51A5BC03 ON item (feed_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_feed (user_id INT NOT NULL, feed_id INT NOT NULL, PRIMARY KEY(user_id, feed_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_59C10862A76ED395 ON user_feed (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_59C1086251A5BC03 ON user_feed (feed_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_item (user_id INT NOT NULL, item_id INT NOT NULL, PRIMARY KEY(user_id, item_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659A69D7A76ED395 ON user_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_659A69D7126F525E ON user_item (item_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item ADD CONSTRAINT FK_1F1B251E51A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C10862A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C1086251A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT FK_659A69D7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item ADD CONSTRAINT FK_659A69D7126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE item DROP CONSTRAINT FK_1F1B251E51A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C10862A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C1086251A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT FK_659A69D7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_item DROP CONSTRAINT FK_659A69D7126F525E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feed
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_feed
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_item
        SQL);
    }
}
