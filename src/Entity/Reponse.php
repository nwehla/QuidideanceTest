<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $repondant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $acceptationpartagedonnee;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datemiseajour;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datefermeture;

    /**
     * @ORM\ManyToOne(targetEntity=Sondage::class, inversedBy="reponse")
     */
    private $sondage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRepondant(): ?string
    {
        return $this->repondant;
    }

    public function setRepondant(?string $repondant): self
    {
        $this->repondant = $repondant;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAcceptationpartagedonnee(): ?bool
    {
        return $this->acceptationpartagedonnee;
    }

    public function setAcceptationpartagedonnee(?bool $acceptationpartagedonnee): self
    {
        $this->acceptationpartagedonnee = $acceptationpartagedonnee;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatemiseajour(): ?\DateTimeInterface
    {
        return $this->datemiseajour;
    }

    public function setDatemiseajour(?\DateTimeInterface $datemiseajour): self
    {
        $this->datemiseajour = $datemiseajour;

        return $this;
    }

    public function getDatefermeture(): ?\DateTimeInterface
    {
        return $this->datefermeture;
    }

    public function setDatefermeture(?\DateTimeInterface $datefermeture): self
    {
        $this->datefermeture = $datefermeture;

        return $this;
    }

    public function getSondage(): ?Sondage
    {
        return $this->sondage;
    }

    public function setSondage(?Sondage $sondage): self
    {
        $this->sondage = $sondage;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
