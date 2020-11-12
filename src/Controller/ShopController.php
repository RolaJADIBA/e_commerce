<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use DateTime;


class ShopController extends AbstractController
{

  private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        // $this->session->remove('panier');

        if(!$this->session->get('panier')) {
            $this->session->set('panier', []);        
        }
    }

    /************************************AJOUTER PPANIER******************************************************** */

    /**
     * @Route("shop/ajout/produit/", name="ajoutPanier")
     */
    public function ajouterPanier(Request $request)
    {
        // Récupère le panier
        $panier = $this->session->get('panier');

        $produit = $request->request->get('produit');

        $taille = $request->request->get('taille');
    
        $color = $request->request->get('color');

        $quantite = $request->request->get('quantite');

        if($panier == null){
            // On initilialise un tableau, dans lequel on y passe l'id du menu et de la boisson
            $monPanier = [];
            $monPanier[] = ['produit_id' => $produit,'taille' =>$taille ,'color' => $color, 'quantite' => $quantite];

            // dd($monPanier);
           
            // Insère les nouvelles données dans le panier
            $this->session->set('panier', $monPanier);
            
        }
        else{
            // Si le panier existe déjà alors on y ajoute les nouvelles valeur
            $panier[] = ['produit_id' => $produit,'taille' =>$taille ,'color' => $color,'quantite' => $quantite];
            // Et on met à jour le panier avec les nouvelles informations
            $this->session->set('panier', $panier);
        }

        // dd($panier);

        // Insère les nouvelles données dans le panier
        // $this->session->set('panier', $panier);

        return $this->redirectToRoute('shop');
    }

    /**********************************************INDEX************************************* */

    /**
     * @Route("/shop", name="shop")
     */
    public function index()
    {

        $paniers = $this->session->get('panier');

        $produits = $this->getDoctrine()->getRepository(Produits::class)->findAll();

        if($paniers != null){

            $nouveau_Panier = [];

                foreach ($paniers as $panier) {

                    $produits_panier = $this->getDoctrine()->getRepository(Produits::class)->findBy(['id' => $panier['produit_id']]);

                    foreach ($produits_panier as $produit_panier) {

                        $nouveau_Panier[] = [

                        'id' => $produit_panier->getId(),
                         'titre' => $produit_panier->getNom(), 
                         'color' => $panier['color'],
                         'taille' => $panier['taille'],
                         'quantite' => $panier['quantite'], 
                         'prix' => $produit_panier->getPrix() * $panier['quantite']

                    ];

                } 
            }           
            }    
            else{
                $nouveau_Panier = [];
                $panier = [];
                $produit_panier = [];
            }  

        return $this->render('shop/index.html.twig', [
            'produits' => $produits,
            'produit_panier' => $nouveau_Panier ,
            'panier' => $panier
        ]);
    }

    /*******************************DELETE PRODUIT************************************************** */

    /**
     * @Route("/shop/panier/produit/delete/{id}", name="deleteProduitPanier")
     */
    public function deleteProduitPanier($id)
    {

        $paniers = $this->session->get('panier', []);

        foreach($paniers as $key => $panier){

            foreach($panier as $cle => $pan){

               if($pan === $id){
                   unset($paniers[$key]);
               }
            }
         }

        $this->session->set('panier', $paniers);

        return $this->redirectToRoute('shop');

    }

/***********************************VIDER PANIER*************************************************** */

    /**
     * @Route("/vider/panier", name="vider_panier")
     */
    public function viderPanier()
    {
        $this->session->clear();        
        // //redirection vers ma page tableau
        return $this->redirectToRoute('accueil');
    }


/***********************************STRIPE*************************************************/

    /**
     * @Route("/charge", name="stripe")
     */
    public function Stripe(Request $request)
    {
        // On récupère le total depuis le formulaire
        $total = $request->request->get('total');

        // Paramétrage de la clès API de STRIPE
        \Stripe\Stripe::setApiKey("sk_test_9C3nIrEYCAzA2Pa02bfAybpj00kF4Qpsin");

        // On créer une charge de paiement
        $charge = \Stripe\Charge::create([
          "amount" => $total * 100,
          "currency" => "eur",
          "source" => "tok_mastercard", // obtained with Stripe.js
          "description" => "paiement de ". $this->getUser()->getEmail(),
        ], [
        //   "idempotency_key" => "fp0unaRbAVdbpVf0",
        ]);

        return $this->redirectToRoute('ajoutPanierBdd', [
            'total' => $total
        ]);
        
    }

    /***********************************AJOUTER BDD****************************************** */

        /**
     * @Route("/shop/ajout/panier/{total}", name="ajoutPanierBdd")
     */
    public function ajoutPanier($total, Request $request)
    {
        // On récupere la session panier
        $paniers = $this->session->get('panier');

        // dd($panier);

        // On créer un numéro de commande aléatoire
        $numero = rand ( 1, 50000 );

        $date = new DateTime();

        //On récupère les produits dont l'id correspond aux clès du tableau session
        // $produits = $this->getDoctrine()->getRepository(Produits::class)->findByArray(array_keys($panier));
        $entityManager = $this->getDoctrine()->getManager();

        //On créer l'objet panier, on set l'utilisateur, le montant total, son statut et son numéro de commande pour envoyé dans la base de données
        $panier1 = new Panier();
        $panier1->setUser($this->getUser());
        $panier1->setMontantTotal($total);
        $panier1->setStatut(0);
        $panier1->setDate($date);
        $panier1->setNumeroCommande($numero);
        $entityManager->persist($panier1);

        
        foreach($paniers as $panier){

            $produits = $this->getDoctrine()->getRepository(Produits::class)->findBy(['id' => $panier['produit_id']]);

            // On boucle les produits récupérer, on créer un objet PanierProduit, on set le prix, la quantité, l'id du produit, l'id du panier et on envoi en base de données et on efface le panier en session.

            foreach($produits as $produit){

                $panier_produit = new PanierProduit();
                $panier_produit->setPrix($produit->getPrix());
                $panier_produit->setQuantite($panier['quantite']);
                $panier_produit->setColor($panier['color']);
                $panier_produit->setTaille($panier['taille']);
                $panier_produit->setProduits($produit);
                $panier_produit->setPanier($panier1);
                
                $entityManager->persist($panier_produit);
    
            }
        }

        $entityManager->flush();

        $this->session->remove('panier');

        $this->addFlash('paiement_success', 'Votre commande a bien été enregistré.');

        return $this->redirectToRoute('accueil');
    }

}
