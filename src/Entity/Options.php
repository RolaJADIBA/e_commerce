<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionsRepository")
 */
class Options
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
     * @ORM\OneToMany(targetEntity="App\Entity\Produits", mappedBy="options")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OptionGroupe", inversedBy="options")
     */
    private $optionGroupe;


    public function __construct()
    {
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
            $produit->setOptions($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getOptions() === $this) {
                $produit->setOptions(null);
            }
        }

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

    public function __toString()
    {
      return  $this->nom;
    }


}
