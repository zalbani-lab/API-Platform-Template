<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\User\UserCurrent;
use App\Controller\User\UserForgottenPasswords;
use App\Controller\User\UserRoleUpdater;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ApiResource(
 *   collectionOperations={
 *      "get"={
 *        "normalization_context"={"groups"={"userRead", "alwaysDisplay"}},
 *      },
 *      "post"={
 *        "denormalization_context"={"groups"={"userWrite"}}
 *      },
 *     "currentUser"={
 *       "method"="get",
 *       "path"="/users/current",
 *       "controller"=UserCurrent::class,
 *       "normalization_context"={"groups"={"userDetailRead", "alwaysDisplay"}}
 *     },
 *    "resetPassword"={
 *         "method"="get",
 *         "path"="/users/forgottenPasswords",
 *         "controller"=UserForgottenPasswords::class
 *     }
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={"groups"={"userDetailRead", "alwaysDisplay"}}
 *      },
 *     "put"={
 *        "denormalization_context"={"groups"={"userWrite"}},
 *        "security"="is_granted('ROLE_ADMIN') or object == user",
 *        "security_message"="ERROR_CODE % 401 % Désoler, mais vous n'avez pas les autorisations nécessaires pour effectuer cette action."
 *      },
 *     "patch"={
 *        "denormalization_context"={"groups"={"userWrite"}},
 *        "security"="is_granted('ROLE_ADMIN') or object == user",
 *        "security_message"="ERROR_CODE % 401 % Désoler, mais vous n'avez pas les autorisations nécessaires pour effectuer cette action."
 *      },
 *     "delete"={
 *        "security"="is_granted('ROLE_ADMIN') or object == user",
 *        "security_message"="ERROR_CODE % 401 % Désoler, mais vous n'avez pas les autorisations nécessaires pour effectuer cette action.",
 *     },
 *     "userRoleUpdater"={
 *       "denormalization_context"={"groups"={"roleUpdate"}},
 *       "method"="patch",
 *       "path"="/users/{id}/updateRole",
 *       "controller"=UserRoleUpdater::class,
 *       "security"="is_granted('ROLE_ADMIN')",
 *       "security_message"="ERROR_CODE % 401 % Désoler, mais vous n'avez pas les autorisations nécessaires pour effectuer cette action."
 *    },
 *   }
 * )
 * @UniqueEntity("email", message="This email is already used")
 * @ApiFilter(SearchFilter::class, properties={"email": "partial", "roles": "partial"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ApiFilter(OrderFilter::class, properties={"id","email","createdAt","updatedAt"}, arguments={"orderParameterName"="order"})
 */
class User implements UserInterface
{
    use ResourceId;
    use Timestamps;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"userRead","userDetailRead", "userWrite", "forgottenPasswordWrite"})
     * @Assert\NotBlank(message="email required")
     * @Assert\Email(message="format invalid")
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"userRead","userDetailRead"})
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"userWrite"})
     * @Assert\NotBlank(message="password required")
     */
    private string $password;

    /**
     * @ORM\ManyToMany(targetEntity=Animation::class, mappedBy="contributors", cascade={"persist"})
     * @Groups({"userRead", "userDetailRead"})
     */
    private Collection $contributions;

    /**
     * @ORM\OneToMany(targetEntity=Animation::class, mappedBy="author")
     * @Groups({"userDetailRead"})
     */
    private Collection $animations;

    public function __construct()
    {
        $this->contributions = new ArrayCollection();
        $this->setRoles(['ROLE_USER']);

        $this->createdAt = new \DateTimeImmutable();
        $this->animations = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Animation[]
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContributions(Animation $animation): self
    {
        if (!$this->contributions->contains($animation)) {
            $this->contributions[] = $animation;
            $animation->addContributor($this);
        }

        return $this;
    }

    public function removeContributions(Animation $animation): self
    {
        if ($this->contributions->removeElement($animation)) {
            $animation->removeContributor($this);
        }

        return $this;
    }

    /**
     * @return Collection|Animation[]
     */
    public function getAnimations(): Collection
    {
        return $this->animations;
    }

    public function addAnimation(Animation $animation): self
    {
        if (!$this->animations->contains($animation)) {
            $this->animations[] = $animation;
            $animation->setAuthor($this);
        }

        return $this;
    }

    public function removeAnimation(Animation $animation): self
    {
        if ($this->animations->removeElement($animation)) {
            // set the owning side to null (unless already changed)
            if ($animation->getAuthor() === $this) {
                $animation->setAuthor(null);
            }
        }

        return $this;
    }
}
