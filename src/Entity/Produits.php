<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitsRepository")
 * @ORM\Table(name="produits", indexes={@ORM\Index(columns={"nom", "description"}, flags={"fulltext"}) })
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
     * @ORM\JoinColumn(nullable=true)
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
     * @Assert\NotBlank
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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="favoris")
     */
    private $favoris;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $inCart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $count;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $total;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
        $this->favoris = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(User $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
        }

        return $this;
    }

    public function removeFavori(User $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
        }

        return $this;
    }

    public function getInCart(): ?bool
    {
        return $this->inCart;
    }

    public function setInCart(?bool $inCart): self
    {
        $this->inCart = $inCart;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

}
