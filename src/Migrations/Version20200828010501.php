<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828010501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, status ENUM(\'PUBLISHED\', \'UNPUBLISHED\') DEFAULT \'UNPUBLISHED\' NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:published_status_enum)\', publish_date DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE blog_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, annotation TEXT NOT NULL COLLATE utf8mb4_unicode_ci, text MEDIUMTEXT NOT NULL COLLATE utf8mb4_unicode_ci, locale VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, seo_title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, seo_description VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX blog_translation_unique_translation (translatable_id, locale), INDEX IDX_6D59D9912C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role (id SMALLINT AUTO_INCREMENT NOT NULL, title VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, INDEX idx_deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, status ENUM(\'PUBLISHED\', \'UNPUBLISHED\') DEFAULT \'UNPUBLISHED\' NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:published_status_enum)\', image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, INDEX tag_id (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE story_block_image (id INT AUTO_INCREMENT NOT NULL, story_translation_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, description TEXT NOT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, INDEX story_translation_id (story_translation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE story_block_text (id INT AUTO_INCREMENT NOT NULL, story_translation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description TEXT NOT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, INDEX story_translation_id (story_translation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE story_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, annotation TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, seo_title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, seo_description VARCHAR(1024) DEFAULT NULL COLLATE utf8mb4_unicode_ci, locale VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_9500088F2C2AC5D3 (translatable_id), UNIQUE INDEX story_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE table_log (id BIGINT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, last_mod_user_id INT DEFAULT NULL, object_id INT NOT NULL, table_name ENUM(\'Blog\', \'Role\', \'Tag\', \'Story\', \'User\') NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:table_log_enum)\', created_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, deleted TINYINT(1) NOT NULL, INDEX idx_created_user_id (created_user_id), INDEX idx_deleted (deleted), INDEX idx_last_mod_user_id (last_mod_user_id), INDEX idx_object_id (object_id), INDEX idx_table_name (table_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, status ENUM(\'PUBLISHED\', \'UNPUBLISHED\') DEFAULT \'UNPUBLISHED\' NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:published_status_enum)\', entity_id INT NOT NULL, type ENUM(\'DEFAULT\', \'Story\', \'Blog\') NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:tag_type_enum)\', deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag_in_entity (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, entity_id INT NOT NULL, entity_type VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, deleted TINYINT(1) NOT NULL, INDEX tag_id (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, locale VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_A8A03F8F2C2AC5D3 (translatable_id), UNIQUE INDEX tag_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', deleted TINYINT(1) NOT NULL, INDEX idx_deleted (deleted), UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE blog');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE blog_translation');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE role');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE story');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE story_block_image');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE story_block_text');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE story_translation');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE table_log');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tag');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tag_in_entity');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tag_translation');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
    }
}
