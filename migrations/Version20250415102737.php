<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415102737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE feed_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE tag_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE feed_item_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT fk_389b783a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP CONSTRAINT fk_389b78351a5bc03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item DROP CONSTRAINT fk_9f6c43ada76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item DROP CONSTRAINT fk_9f6c43ada87d462b
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT fk_59c10862a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed DROP CONSTRAINT fk_59c1086251a5bc03
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item DROP CONSTRAINT fk_9f8cce4951a5bc03
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feed
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_feed_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_feed
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feed_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_identifier_username
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP bio
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN username TO email
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE feed_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE feed_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id SERIAL NOT NULL, user_id INT NOT NULL, feed_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_389b78351a5bc03 ON tag (feed_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_389b783a76ed395 ON tag (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feed (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_feed_item (user_id INT NOT NULL, feed_item_id INT NOT NULL, PRIMARY KEY(user_id, feed_item_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9f6c43ada87d462b ON user_feed_item (feed_item_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9f6c43ada76ed395 ON user_feed_item (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_feed (user_id INT NOT NULL, feed_id INT NOT NULL, PRIMARY KEY(user_id, feed_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_59c1086251a5bc03 ON user_feed (feed_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_59c10862a76ed395 ON user_feed (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feed_item (id SERIAL NOT NULL, feed_id INT NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, media_link TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9f8cce4951a5bc03 ON feed_item (feed_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT fk_389b783a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT fk_389b78351a5bc03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item ADD CONSTRAINT fk_9f6c43ada76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed_item ADD CONSTRAINT fk_9f6c43ada87d462b FOREIGN KEY (feed_item_id) REFERENCES feed_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT fk_59c10862a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_feed ADD CONSTRAINT fk_59c1086251a5bc03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feed_item ADD CONSTRAINT fk_9f8cce4951a5bc03 FOREIGN KEY (feed_id) REFERENCES feed (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_IDENTIFIER_EMAIL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD bio VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN email TO username
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_identifier_username ON "user" (username)
        SQL);
    }
}
