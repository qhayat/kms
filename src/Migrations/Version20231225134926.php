<?php

declare(strict_types=1);

namespace Kms\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231225134926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE menu_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_token (id UUID NOT NULL, name VARCHAR(255) NOT NULL, token TEXT NOT NULL, enabled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN api_token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE api_token_permission (api_token_id UUID NOT NULL, permission_id UUID NOT NULL, PRIMARY KEY(api_token_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_4A1B264092E52D36 ON api_token_permission (api_token_id)');
        $this->addSql('CREATE INDEX IDX_4A1B2640FED90CCA ON api_token_permission (permission_id)');
        $this->addSql('COMMENT ON COLUMN api_token_permission.api_token_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN api_token_permission.permission_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE category_post (category_id UUID NOT NULL, post_id UUID NOT NULL, PRIMARY KEY(category_id, post_id))');
        $this->addSql('CREATE INDEX IDX_D11116CA12469DE2 ON category_post (category_id)');
        $this->addSql('CREATE INDEX IDX_D11116CA4B89032C ON category_post (post_id)');
        $this->addSql('COMMENT ON COLUMN category_post.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN category_post.post_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE content (id UUID NOT NULL, featured_image_id UUID DEFAULT NULL, author_id UUID NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content TEXT NOT NULL, excerpt TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEC530A9989D9B62 ON content (slug)');
        $this->addSql('CREATE INDEX IDX_FEC530A93569D950 ON content (featured_image_id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9F675F31B ON content (author_id)');
        $this->addSql('COMMENT ON COLUMN content.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN content.featured_image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN content.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE content_content (content_source UUID NOT NULL, content_target UUID NOT NULL, PRIMARY KEY(content_source, content_target))');
        $this->addSql('CREATE INDEX IDX_21CDE91A37BC0FDF ON content_content (content_source)');
        $this->addSql('CREATE INDEX IDX_21CDE91A2E595F50 ON content_content (content_target)');
        $this->addSql('COMMENT ON COLUMN content_content.content_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN content_content.content_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE media (id UUID NOT NULL, author_id UUID NOT NULL, name VARCHAR(255) NOT NULL, path TEXT NOT NULL, extension VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10CF675F31B ON media (author_id)');
        $this->addSql('COMMENT ON COLUMN media.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN media.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE menu_item (id INT NOT NULL, menu_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D754D550CCD7E912 ON menu_item (menu_id)');
        $this->addSql('CREATE TABLE page (id UUID NOT NULL, home_page BOOLEAN DEFAULT NULL, blog_page BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN page.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE permission (id UUID NOT NULL, key VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN permission.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE post (id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN post.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE setting (id UUID NOT NULL, author_id UUID NOT NULL, key VARCHAR(255) NOT NULL, value TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F74B898F675F31B ON setting (author_id)');
        $this->addSql('COMMENT ON COLUMN setting.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN setting.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE api_token_permission ADD CONSTRAINT FK_4A1B264092E52D36 FOREIGN KEY (api_token_id) REFERENCES api_token (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE api_token_permission ADD CONSTRAINT FK_4A1B2640FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_post ADD CONSTRAINT FK_D11116CA4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A93569D950 FOREIGN KEY (featured_image_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content_content ADD CONSTRAINT FK_21CDE91A37BC0FDF FOREIGN KEY (content_source) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content_content ADD CONSTRAINT FK_21CDE91A2E595F50 FOREIGN KEY (content_target) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620BF396750 FOREIGN KEY (id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DBF396750 FOREIGN KEY (id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898F675F31B FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE menu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE menu_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE api_token_permission DROP CONSTRAINT FK_4A1B264092E52D36');
        $this->addSql('ALTER TABLE api_token_permission DROP CONSTRAINT FK_4A1B2640FED90CCA');
        $this->addSql('ALTER TABLE category_post DROP CONSTRAINT FK_D11116CA12469DE2');
        $this->addSql('ALTER TABLE category_post DROP CONSTRAINT FK_D11116CA4B89032C');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT FK_FEC530A93569D950');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT FK_FEC530A9F675F31B');
        $this->addSql('ALTER TABLE content_content DROP CONSTRAINT FK_21CDE91A37BC0FDF');
        $this->addSql('ALTER TABLE content_content DROP CONSTRAINT FK_21CDE91A2E595F50');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CF675F31B');
        $this->addSql('ALTER TABLE menu_item DROP CONSTRAINT FK_D754D550CCD7E912');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620BF396750');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DBF396750');
        $this->addSql('ALTER TABLE setting DROP CONSTRAINT FK_9F74B898F675F31B');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE api_token_permission');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_post');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE content_content');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_item');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE users');
    }
}
