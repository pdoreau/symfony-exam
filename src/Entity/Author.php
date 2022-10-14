<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity]
#[GQL\Type(name: "Author")]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[GQL\Field(type: "ID")]
    #[Groups('author-post')]
    private ?int $id = null;

    #[ORM\Column]
    #[NotBlank]
    #[GQL\Field]
    private ?string $lastName;

    #[ORM\Column]
    #[NotBlank]
    #[Groups('author-list')]
    private ?string $firstName;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    #[GQL\Field(type: '[Book]', )]
    #[Groups('author-list')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    #[Groups('author-list')]
    public function getBooksCount() : int
    {
        return count($this->books);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function updateBooksAuthor()
    {
        foreach ($this->books as $book) {
            $book->setAuthor($this);
        }
    }
}
