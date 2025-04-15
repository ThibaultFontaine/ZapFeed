<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415083726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE feed_item (id SERIAL NOT NULL, feed_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, media_link TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9F8CCE498ACBCDEB ON feed_item (feed_id_id)
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
            CREATE TABLE user_feed_item (user_id INT NOT NULL, feed_item_id INT NOT NULL, PRIMARY KEY(user_id, feed_item_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9F6C43ADA76ED395 ON user_feed_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9F6C43ADA87D462B ON user_feed_item (feed_item_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item ADD CONSTRAINT FK_9F8CCE498ACBCDEB FOREIGN KEY (feed_id_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C10862A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT FK_59C1086251A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item ADD CONSTRAINT FK_9F6C43ADA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item ADD CONSTRAINT FK_9F6C43ADA87D462B FOREIGN KEY (feed_item_id) REFERENCES feed_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT fk_389b7839d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT fk_389b7838acbcdeb
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_389b7838acbcdeb
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_389b7839d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD feed_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP user_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP feed_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT FK_389B783A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT FK_389B78351A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_389B783A76ED395 ON tag (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_389B78351A5BC03 ON tag (feed_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item DROP CONSTRAINT FK_9F8CCE498ACBCDEB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C10862A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT FK_59C1086251A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item DROP CONSTRAINT FK_9F6C43ADA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item DROP CONSTRAINT FK_9F6C43ADA87D462B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feed_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_feed
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_feed_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT FK_389B783A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT FK_389B78351A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_389B783A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_389B78351A5BC03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD user_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD feed_id_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP feed_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT fk_389b7839d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT fk_389b7838acbcdeb FOREIGN KEY (feed_id_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_389b7838acbcdeb ON tag (feed_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_389b7839d86650f ON tag (user_id_id)
        SQL);
    }
}
