<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="`category`")
 * @ApiResource(
 *   collectionOperations={
 *      "get"={
 *        "normalization_context"={"groups"={"categoryRead", "alwaysDisplay"}}
 *      },
 *      "post"={
 *        "security"="is_granted('ROLE_ADMIN')"
 *      }
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={"groups"={"categoryRead","categoryDetailRead", "alwaysDisplay"}}
 *      },
 *     "put"={
 *        "security"="is_granted('ROLE_ADMIN')",
 *        "denormalization_context"={"groups"={"categoryWrite"}}
 *      },
 *     "patch"={
 *        "security"="is_granted('ROLE_ADMIN')",
 *        "denormalization_context"={"groups"={"categoryWrite"}}
 *      },
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *   }
 * )
 * @ApiFilter(OrderFilter::class, properties={"id","createdAt","updatedAt"}, arguments={"orderParameterName"="order"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ApiFilter(SearchFilter::class, properties={"name":"partial"})
 */

class Category
{
    use ResourceId;
    use Timestamps;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"animationRead", "categoryRead", "categoryWrite"})
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Animation::class, mappedBy="category")
     * @Groups({"categoryDetailRead"})
     */
    private Collection $animations;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->animations = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $animation->setCategory($this);
        }

        return $this;
    }

    public function removeAnimation(Animation $animation): self
    {
        if ($this->animations->removeElement($animation)) {
            // set the owning side to null (unless already changed)
            if ($animation->getCategory() === $this) {
                $animation->setCategory(null);
            }
        }

        return $this;
    }
}
