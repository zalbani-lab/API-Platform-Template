<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Media\CreateMediaAction;
use App\Controller\Media\UpdateMediaAction;
use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"mediaRead", "alwaysDisplay"}
 *     },
 *     collectionOperations={
 *         "post"={
 *             "security"="is_granted('ROLE_REDACTOR')",
 *             "controller"=CreateMediaAction::class,
 *             "deserialize"=false,
 *             "validation_groups"={"Default", "mediaWrite"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     },
 *                                     "title"={
 *                                         "type"="string"
 *                                     },
 *                                    "legend"={
 *                                         "type"="string"
 *                                     },
 *                                    "target"={
 *                                      "type"="string"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"={
 *              "security"="is_granted('ROLE_REDACTOR')"
 *          }
 *     },
 *   itemOperations={
 *     "get",
 *     "put"={
 *             "security"="is_granted('ROLE_ADMIN')",
 *             "controller"=UpdateMediaAction::class,
 *             "deserialize"=false,
 *             "validation_groups"={"Default", "mediaWrite"},
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     },
 *                                     "title"={
 *                                         "type"="string"
 *                                     },
 *                                    "legend"={
 *                                         "type"="string"
 *                                     },
 *                                    "target"={
 *                                      "type"="string"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *      },
 *     "patch"={
 *        "security"="is_granted('ROLE_REDACTOR')",
 *        "denormalization_context"={"groups"={"mediaDetailWrite"}}
 *      },
 *     "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *   }
 * )
 * @Vich\Uploadable
 * @ApiFilter(OrderFilter::class, properties={"id","title","legend","createdAt","updatedAt"}, arguments={"orderParameterName"="order"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ApiFilter(SearchFilter::class, properties={"target":"partial", "title":"partial", "legend":"partial"})
 */
class Media
{
    use ResourceId;
    use Timestamps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mediaRead", "animationRead", "alwaysDisplay"})
     */
    public ?string $contentUrl;

    /**
     * @Assert\NotNull(groups={"mediaWrite"})
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    private ?File $file = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $filePath;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mediaRead", "mediaDetailWrite"})
     */
    private ?string $target;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mediaRead", "mediaDetailWrite", "alwaysDisplay"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"mediaRead", "mediaDetailWrite", "alwaysDisplay"})
     */
    private ?string $legend;

    /**
     * @ORM\OneToMany(targetEntity=Animation::class, mappedBy="image")
     */
    private Collection $animations;



    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->animations = new ArrayCollection();
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(?string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(?string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }

    public function addAnimation(Animation $animation): self
    {
        if (!$this->animations->contains($animation)) {
            $this->animations[] = $animation;
            $animation->setImage($this);
        }

        return $this;
    }

    public function removeAnimation(Animation $animation): self
    {
        if ($this->animations->removeElement($animation)) {
            // set the owning side to null (unless already changed)
            if ($animation->getImage() === $this) {
                $animation->setImage(null);
            }
        }

        return $this;
    }
}
