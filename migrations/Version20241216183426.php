<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216183426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added Comment, Log, Tag, Ticket and work time entities';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', author_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ticket_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', content VARCHAR(1000) NOT NULL, created_at DATE NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ticket_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', action VARCHAR(255) NOT NULL, created_at DATE NOT NULL, INDEX IDX_8F3F68C5700047D2 (ticket_id), INDEX IDX_8F3F68C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, created_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets_tags (tag_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ticket_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_6AC742FBBAD26311 (tag_id), INDEX IDX_6AC742FB700047D2 (ticket_id), PRIMARY KEY(tag_id, ticket_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', author_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', worker_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, content VARCHAR(2000) NOT NULL, status VARCHAR(20) NOT NULL, updated_at DATE NOT NULL, closed_at DATE DEFAULT NULL, created_at DATE NOT NULL, INDEX IDX_97A0ADA3F675F31B (author_id), INDEX IDX_97A0ADA36B20BA36 (worker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_tag (ticket_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', tag_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_F06CAF700047D2 (ticket_id), INDEX IDX_F06CAFBAD26311 (tag_id), PRIMARY KEY(ticket_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_time (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', ticket_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', employee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', time INT NOT NULL, created_at DATE NOT NULL, INDEX IDX_9657297D700047D2 (ticket_id), INDEX IDX_9657297D8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tickets_tags ADD CONSTRAINT FK_6AC742FBBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tickets_tags ADD CONSTRAINT FK_6AC742FB700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36B20BA36 FOREIGN KEY (worker_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket_tag ADD CONSTRAINT FK_F06CAF700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_tag ADD CONSTRAINT FK_F06CAFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_time ADD CONSTRAINT FK_9657297D700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE work_time ADD CONSTRAINT FK_9657297D8C03F15C FOREIGN KEY (employee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE token CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_id user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE deleted_at deleted_at DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C700047D2');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5700047D2');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A76ED395');
        $this->addSql('ALTER TABLE tickets_tags DROP FOREIGN KEY FK_6AC742FBBAD26311');
        $this->addSql('ALTER TABLE tickets_tags DROP FOREIGN KEY FK_6AC742FB700047D2');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F675F31B');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA36B20BA36');
        $this->addSql('ALTER TABLE ticket_tag DROP FOREIGN KEY FK_F06CAF700047D2');
        $this->addSql('ALTER TABLE ticket_tag DROP FOREIGN KEY FK_F06CAFBAD26311');
        $this->addSql('ALTER TABLE work_time DROP FOREIGN KEY FK_9657297D700047D2');
        $this->addSql('ALTER TABLE work_time DROP FOREIGN KEY FK_9657297D8C03F15C');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tickets_tags');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_tag');
        $this->addSql('DROP TABLE work_time');
        $this->addSql('ALTER TABLE token CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }
}
