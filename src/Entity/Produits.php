<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitsRepository")
 */
class Produits
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $prix;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $images = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Options", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $options;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PanierProduit", mappedBy="produits")
     */
    private $panierProduits;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $colors = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $tailles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_choisi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OptionGroupe", inversedBy="produits")
     */
    private $optionGroupe;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getOptions(): ?Options
    {
        return $this->options;
    }

    public function setOptions(?Options $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return Collection|PanierProduit[]
     */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): self
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits[] = $panierProduit;
            $panierProduit->setProduits($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): self
    {
        if ($this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->removeElement($panierProduit);
            // set the owning side to null (unless already changed)
            if ($panierProduit->getProduits() === $this) {
                $panierProduit->setProduits(null);
            }
        }

        return $this;
    }

    public function getColors(): ?array
    {
        return $this->colors;
    }

    public function setColors(?array $colors): self
    {
        $this->colors = $colors;

        return $this;
    }

    public function getTailles(): ?array
    {
        return $this->tailles;
    }

    public function setTailles(?array $tailles): self
    {
        $this->tailles = $tailles;

        return $this;
    }

    public function getImageChoisi(): ?string
    {
        return $this->image_choisi;
    }

    public function setImageChoisi(?string $image_choisi): self
    {
        $this->image_choisi = $image_choisi;

        return $this;
    }

    public function getOptionGroupe(): ?OptionGroupe
    {
        return $this->optionGroupe;
    }

    public function setOptionGroupe(?OptionGroupe $optionGroupe): self
    {
        $this->optionGroupe = $optionGroupe;

        return $this;
    }

}
