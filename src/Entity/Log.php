<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 * @ORM\Table(name="`log`")
 * @ApiResource(
 *   collectionOperations={
 *      "get"={"security"="is_granted('ROLE_ADMIN')"},
 *   },
 *   itemOperations={
 *      "get"={"security"="is_granted('ROLE_ADMIN')"},
 *   }
 * )
 * @ApiFilter(SearchFilter::class, properties={"targetElement": "exact", "method": "exact"})
 * @ApiFilter(NumericFilter::class, properties={"author","targetId", "level"})
 * @ApiFilter(OrderFilter::class, properties={"id","createdAt","updatedAt", "level"}, arguments={"orderParameterName"="order"})
 */
class Log
{
    use ResourceId;
    use Timestamps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $method;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $targetElement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $targetId;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $request = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $response = [];

    /**
     * @ORM\Column(type="integer")
     */
    private int $level;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getAuthor(): ?int
    {
        return $this->author;
    }

    public function setAuthor(?int $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getTargetElement(): ?string
    {
        return $this->targetElement;
    }

    public function setTargetElement(?string $targetElement): self
    {
        $this->targetElement = $targetElement;

        return $this;
    }

    public function getTargetId(): ?int
    {
        return $this->targetId;
    }

    public function setTargetId(?int $targetId): self
    {
        $this->targetId = $targetId;

        return $this;
    }

    public function getRequest(): ?array
    {
        return $this->request;
    }

    public function setRequest(?array $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function setResponse(?array $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
