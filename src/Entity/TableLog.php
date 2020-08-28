<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableLog
 *
 * @ORM\Table(name="table_log", indexes={
 *     @ORM\Index(name="idx_object_id", columns={"object_id"}),
 *     @ORM\Index(name="idx_created_user_id", columns={"created_user_id"}),
 *     @ORM\Index(name="idx_last_mod_user_id", columns={"last_mod_user_id"}),
 *     @ORM\Index(name="idx_deleted", columns={"deleted"}),
 *     @ORM\Index(name="idx_table_name", columns={"table_name"})
 * })
 * @ORM\Entity(repositoryClass="App\Entity\Repository\TableLogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TableLog implements BaseFieldsI
{
    use BaseFieldsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="object_id", type="integer", nullable=false)
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="table_log_enum", nullable=false)
     */
    private $tableName;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_user_id", referencedColumnName="id")
     * })
     */
    private $createdUserId;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="last_mod_user_id", referencedColumnName="id")
     * })
     */
    private $lastModUserId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    public function __construct()
    {
        $this->createdDate = new \DateTime();
        $this->updatedDate = new \DateTime();
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function getCreatedUserId(): ?User
    {
        return $this->createdUserId;
    }

    public function setCreatedUserId(?User $createdUserId): self
    {
        $this->createdUserId = $createdUserId;

        return $this;
    }

    public function getLastModUserId(): ?User
    {
        return $this->lastModUserId;
    }

    public function setLastModUserId(?User $lastModUserId): self
    {
        $this->lastModUserId = $lastModUserId;

        return $this;
    }


}
