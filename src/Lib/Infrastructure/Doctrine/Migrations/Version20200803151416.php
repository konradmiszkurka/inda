<?php

declare(strict_types=1);

namespace App\Lib\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200803151416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_ (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', phone VARCHAR(11) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, hash VARCHAR(256) NOT NULL, avatar_type ENUM(\'gravatar\', \'initial\', \'file\', \'default\') DEFAULT \'default\' COMMENT \'(DC2Type:UserUserAvatarEnum)\', password_change_required TINYINT(1) DEFAULT \'0\', created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_265BC90A92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_265BC90AA0D96FBF (email_canonical), UNIQUE INDEX UNIQ_265BC90AC05FB297 (confirmation_token), INDEX IDX_265BC90A93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_file (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, description LONGTEXT DEFAULT NULL, path VARCHAR(256) NOT NULL, mime_type VARCHAR(256) NOT NULL, category ENUM(\'avatar\') NOT NULL COMMENT \'(DC2Type:AttachmentFileCategoryEnum)\', created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_photo (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(256) NOT NULL, description LONGTEXT DEFAULT NULL, path VARCHAR(256) NOT NULL, mime_type VARCHAR(256) NOT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_ ADD CONSTRAINT FK_265BC90A93CB796C FOREIGN KEY (file_id) REFERENCES attachment_photo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_ DROP FOREIGN KEY FK_265BC90A93CB796C');
        $this->addSql('DROP TABLE user_');
        $this->addSql('DROP TABLE attachment_file');
        $this->addSql('DROP TABLE attachment_photo');
    }
}
