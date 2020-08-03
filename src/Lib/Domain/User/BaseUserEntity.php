<?php
declare(strict_types=1);

namespace App\Lib\Domain\User;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;
use Gedmo\Mapping\Annotation as Gedmo;

abstract class BaseUserEntity extends User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var DateTime|null
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updateAt;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function getId(): int
    {
        return $this->id;
    }

    protected function removed(): void
    {
        $this->deletedAt = new DateTime();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdateAt(): ?DateTime
    {
        return $this->updateAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    protected function created(): void
    {
        $this->createdAt = new DateTime();
    }

    protected function updated(): void
    {
        $this->updateAt = new DateTime();
    }
}