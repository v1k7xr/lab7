<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $NameBook;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AuthorBook;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $imageLocation;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $bookLocation;

    /**
     * @ORM\Column(type="date")
     */
    private $ReadingDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameBook(): ?string
    {
        return $this->NameBook;
    }

    public function setNameBook(string $NameBook): self
    {
        $this->NameBook = $NameBook;

        return $this;
    }

    public function getAuthorBook(): ?string
    {
        return $this->AuthorBook;
    }

    public function setAuthorBook(string $AuthorBook): self
    {
        $this->AuthorBook = $AuthorBook;

        return $this;
    }

    public function getImageLocation() 
    {
        return $this->imageLocation;
    }

    public function setImageLocation(string $imageLocation): self
    {
        $this->imageLocation = $imageLocation;

        return $this;
    }

    public function getBookLocation() 
    {
        return $this->bookLocation;
    }

    public function setBookLocation(string $bookLocation): self
    {
        $this->bookLocation = $bookLocation;

        return $this;
    }

    public function getReadingDate(): ?\DateTimeInterface
    {
        return $this->ReadingDate;
    }

    public function setReadingDate(\DateTimeInterface $ReadingDate): self
    {
        $this->ReadingDate = $ReadingDate;

        return $this;
    }
}
