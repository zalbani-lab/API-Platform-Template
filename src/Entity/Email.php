<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\Email\PostEmail;
use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 * @ORM\Table(name="`email`")
 * @ApiResource(
 *   collectionOperations={
 *      "get"={"security"="is_granted('ROLE_ADMIN')"},
 *      "post"={
 *          "denormalization_context"={"groups"={"emailWrite"}},
 *          "controller"=PostEmail::class
 *      }
 *   },
 *   itemOperations={
 *      "get"={"security"="is_granted('ROLE_ADMIN')"},
 *   }
 * )
 * @ApiFilter(SearchFilter::class, properties={"object": "partial", "template": "exact", "recipient": "partial", "context": "exact", "content": "partial"})
 * @ApiFilter(NumericFilter::class, properties={"author", "id"})
 * @ApiFilter(OrderFilter::class, properties={"id","createdAt","updatedAt"}, arguments={"orderParameterName"="order"})
 */
class Email
{
    use Timestamps;
    use ResourceId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emailWrite"})
     */
    private string $recipient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"emailWrite"})
     */
    private string $subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $template;

    /**
     * @ORM\Column(type="text")
     * @Groups({"emailWrite"})
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $context;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"emailWrite"})
     */
    private ?string $replyAddress;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->replyAddress = null;
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

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getReplyAddress(): ?string
    {
        return $this->replyAddress;
    }

    public function setReplyAddress(?string $replyAddress): self
    {
        $this->replyAddress = $replyAddress;

        return $this;
    }
}
