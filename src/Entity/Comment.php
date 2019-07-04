<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9.,;\-:éèçà?\s']+$/", message="Le contenu n'est pas au bon format")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * Permet de mettre en place la date de création
     *
     * @ORM\PrePersist
     * @throws \Exception
     * @return void
     */
    public function prePersist() {

        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt(): ? \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

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
}
