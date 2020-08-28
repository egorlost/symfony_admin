<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UNIQ_8D93D64992FC23A8", columns={"username_canonical"}),
 *     @ORM\UniqueConstraint(name="UNIQ_8D93D649A0D96FBF", columns={"email_canonical"}),
 *     @ORM\UniqueConstraint(name="UNIQ_8D93D649C05FB297", columns={"confirmation_token"})
 * }, indexes={
 *     @ORM\Index(name="idx_deleted", columns={"deleted"})
 * })
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements BaseFieldsI
{
    use BaseFieldsTrait {
        __construct as protected baseFields__construct;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();

        $this->baseFields__construct();

        $this->roles = ['ROLE_ADMIN'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
