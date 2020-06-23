<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OptionGroupe", mappedBy="categorie")
     */
    private $optionGroupes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produits", mappedBy="categorie")
     */
    private $produits;


    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->optionGroupes = new ArrayCollection();
        $this->produits = new ArrayCollection();
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


    /**
     * @return Collection|OptionGroupe[]
     */
    public function getOptionGroupes(): Collection
    {
        return $this->optionGroupes;
    }

    public function addOptionGroupe(OptionGroupe $optionGroupe): self
    {
        if (!$this->optionGroupes->contains($optionGroupe)) {
            $this->optionGroupes[] = $optionGroupe;
            $optionGroupe->addCategorie($this);
        }

        return $this;
    }

    public function removeOptionGroupe(OptionGroupe $optionGroupe): self
    {
        if ($this->optionGroupes->contains($optionGroupe)) {
            $this->optionGroupes->removeElement($optionGroupe);
            $optionGroupe->removeCategorie($this);
        }

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

}
