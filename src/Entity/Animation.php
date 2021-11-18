<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\AnimationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnimationRepository::class)
 * @ORM\Table(name="`animation`")
 * @ApiResource(
 *   collectionOperations={
 *      "get"={
 *        "normalization_context"={"groups"={"animationRead", "alwaysDisplay"}}
 *      },
 *      "post"={
 *        "security"="is_granted('ROLE_USER')",
 *        "normalization_context"={"groups"={"animationRead", "alwaysDisplay"}},
 *        "denormalization_context"={"groups"={"animationWrite"}}
 *      }
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={"groups"={"animationRead","animationDetailRead", "alwaysDisplay"}}
 *      },
 *     "put"={
 *        "security"="is_granted('ROLE_USER')",
 *        "denormalization_context"={"groups"={"animationWrite"}}
 *      },
 *     "patch"={
 *        "security"="is_granted('ROLE_USER')",
 *        "denormalization_context"={"groups"={"animationWrite"}}
 *      },
 *     "delete"={"security"="is_granted('ROLE_USER')"}
 *   }
 * )
 * @ApiFilter(OrderFilter::class, properties={"id","createdAt","updatedAt"}, arguments={"orderParameterName"="order"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ApiFilter(SearchFilter::class, properties={"title":"partial", "users":"exact"})
 */
class Animation
{
    use ResourceId;
    use Timestamps;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "animationWrite", "animationRead", "userDetailRead", "programDetailRead", "dateRead" })
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"animationWrite", "animationRead", "userDetailRead", "programDetailRead",  "dateRead"})
     */
    private string $shortDescription;

    /**
     * @ORM\Column(type="text")
     * @Groups({"animationWrite", "animationRead", "userDetailRead", "programDetailRead", "dateRead"})
     */
    private string $longDescription;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="contributions",  cascade={"persist"})
     * @Groups({"animationDetailRead"})
     */
    private Collection $contributors;


    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="animations")
     * @Groups({"animationWrite", "animationRead", "alwaysDisplay"})
     */
    private ?Media $image;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"animationRead"})
     */
    private ?\DateTimeInterface $dateStart;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"animationRead"})
     */
    private ?\DateTimeInterface $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animations", cascade={"persist"})
     * @Groups({"animationRead"})
     */
    private ?User $author;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="animations")
     * @Groups({"animationRead"})
     */
    private ?Category $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $display;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $displayDate;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->contributors = new ArrayCollection();
        $this->dateStart = null;
        $this->displayDate = null;
        $this->display = false;
        $this->dateEnd = null;
        $this->author = null;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getContributors(): Collection
    {
        return $this->contributors;
    }

    public function addContributor(User $user): self
    {
        if (!$this->contributors->contains($user)) {
            $this->contributors[] = $user;
        }

        return $this;
    }

    public function removeContributor(User $user): self
    {
        $this->contributors->removeElement($user);

        return $this;
    }


    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDisplay(): ?bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getDisplayDate(): ?\DateTimeInterface
    {
        return $this->displayDate;
    }

    public function setDisplayDate(?\DateTimeInterface $displayDate): self
    {
        $this->displayDate = $displayDate;

        return $this;
    }
}
