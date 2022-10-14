<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[GQL\Type(name: "Book")]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[GQL\Field(type: "ID")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[GQL\Field]
    #[Groups('author-list')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[GQL\Field]
    private ?string $resume = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    private Author|null $author = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): Book
    {
        $this->author = $author;

        return $this;
    }
}
