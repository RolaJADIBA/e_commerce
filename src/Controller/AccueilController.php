<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\SearchProduitType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController extends AbstractController
{

    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /*************ACCUEIL ADMIN**************** */

    /**
     * @Route("/accueil/admin", name="accueil_admin")
     */
    public function indexAdmin()
    {
        return $this->render('accueilAdmin/index.html.twig');
    }

    /*****************A PROPOS************************* */

    /**
     * @Route("/propos", name="propos")
     */
    public function propos()
    {
        return $this->render('accueil/propos.html.twig');
    }
    /*****************DETAILS************************************ */
    /**
     * @Route("/produits/cards", name="cards")
     */
    public function cards()
    {
        $produits = $this->getDoctrine()->getRepository(Produits::class)->findAll();
        return $this->render('accueil/produit.html.twig',[
            'produits' => $produits
        ]);
    }


    /***********************************VETEMENT FEMME******************************************* */

    /**
     * @Route("/robe", name="robe")
     */
    public function robe()
    {
    
        $robes = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 1
             ]);     
     
        return $this->render('accueil/produit.html.twig', [
            'produits' => $robes
        ]);
    }

    /**
     * @Route("/monteauxVesteFemme", name="monteauxVesteFemme")
     */
    public function monteauxVesteFemme(){

        $monteauxVesteFemmes = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 4
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $monteauxVesteFemmes
        ]);
    }


    /**
     * @Route("/tShirtFemme", name="tShirtFemme")
     */
    public function tShirtFemme(){
    
        $tShirtFemme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 2
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $tShirtFemme
        ]);
    }

    /**
     * @Route("/blouses", name="blouses")
     */
    public function blouses(){
   
        $blouses = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 3
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $blouses
        ]);
    }

    /**
     * @Route("/pantalonFemme", name="pantalonFemme")
     */
    public function pantalonFemme(){

    
        $pantalonFemme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 5
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $pantalonFemme
        ]);
    }

    /**
     * @Route("/jupes", name="jupes")
     */
    public function jupes(){

        $jupes = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 6
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $jupes
        ]);
    }

    /**
     * @Route("/shortsFemme", name="shortsFemme")
     */
    public function shortsFemme(){

        $shortsFemme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 7
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $shortsFemme
        ]);
    }

    /**
     * @Route("/vetementsPlage", name="vetementsPlage")
     */
    public function vetementsPlage(){

        $vetementPlage = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 8
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $vetementPlage
        ]);
    }

    /*****************************CHAUSSEURS & SACS FEMME************************************ */
    /**
     * @Route("/chausseursFemme", name="chausseursFemme")
     */
    public function chausseursFemme(){

        $chausseurs = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 20
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $chausseurs
        ]);
    }

    /**
     * @Route("/sacs", name="sacs")
     */
    public function sacs(){

        $chausseurs = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 21
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $chausseurs
        ]);
    }

    /*******************************ACCESSOIRES FEMME***************************** */

    /**
     * @Route("/bijoux", name="bijoux")
     */
    public function bijoux(){

        $bijoux = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 22
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $bijoux
        ]);
    }

    /**
     * @Route("/lunettesSoliel", name="lunettesSoliel")
     */
    public function LunettesSoliel(){

        $lunettesSoliel = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 1,
            'options' => 23
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $lunettesSoliel
        ]);
    }

    /************************************HOMME******************************************** */

    /******************VETEMENT************************ */

    /**
     * @Route("/pantalonHomme", name="pantalonHomme")
     */
    public function pantalonHomme(){

        $pantalonHomme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 5
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $pantalonHomme
        ]);
    }

    /**
     * @Route("/chemises", name="chemises")
     */
    public function chemises(){

        $chemises = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 9
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $chemises
        ]);
    }

    /**
     * @Route("/tShirtHomme", name="tShirtHomme")
     */
    public function tShirtHomme(){
    
        $tShirtHomme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 2
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $tShirtHomme
        ]);
    }


    /**
     * @Route("/shortHomme", name="shortHomme")
     */
    public function shortHomme(){
    
        $shortHomme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 7
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $shortHomme
        ]);
    }

    /**
     * @Route("/polos", name="polos")
     */
    public function plolos(){
    
        $polos = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 10
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $polos
        ]);
    }

    /**
     * @Route("/monteauxVesteHomme", name="monteauxVesteHomme")
     */
    public function monteauxVesteHomme(){

        $monteauxVesteHomme = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 4
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $monteauxVesteHomme
        ]);
    }

    /**
     * @Route("/maillots", name="maillots")
     */
    public function maillots(){

        $maillots = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 11
             ]);


        return $this->render('accueil/produit.html.twig', [
            'produits' => $maillots
        ]);
    }

    /***************************CHAUSSEURS&SACS ****************************** */

    /**
     * @Route("/chausseursHomme", name="chausseursHomme")
     */
    public function chausseursHomme(){

        $chausseurs = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 12
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $chausseurs
        ]);
    }

    /****************************ACCESSOIRES************************************** */

    /**
     * @Route("/accessoires", name="accessoires")
     */
    public function accessoires(){

        $accessoires = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 2,
            'options' => 13
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $accessoires
        ]);
    }

    /***************************************************************************************************************************************************ENFANT***************************************************************************************************************************************** */

    /**
     * @Route("/adosFille", name="adosFille")
     */
    public function adosFille(){

        $adosFille = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 14
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $adosFille
        ]);
    }

    /**
     * @Route("/adosGarcon", name="adosGarcon")
     */
    public function adosGarcon(){

        $adosGarcon = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 15
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $adosGarcon
        ]);
    }

    /**
     * @Route("/petitFille", name="petitFille")
     */
    public function petitFille(){

        $petitFille = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 16
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $petitFille
        ]);
    }

    /**
     * @Route("/petitGarcon", name="petitGarcon")
     */
    public function petitGarcon(){

        $petitGarcon = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 17
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $petitGarcon
        ]);
    }

    /**
     * @Route("/bebeFille", name="bebeFille")
     */
    public function bebeFille(){

        $bebeFille = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 18
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $bebeFille
        ]);
    }



    /**
     * @Route("/bebeGarcon", name="bebeGarcon")
     */
    public function bebeGarcon(){

        $bebeGarcon = $this->getDoctrine()->getRepository(Produits::class)->findBy([
            'categorie' => 3,
            'options' => 19
             ]);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $bebeGarcon
        ]);
    }

/*************************************DETAILS*********************************************** */
    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Produits $produit){

        $produitDetails = $this->getDoctrine()->getRepository(Produits::class)->find($produit);

        return $this->render('accueil/details.html.twig', [
            'produit' => $produitDetails
        ]);
    }

/**************************************SEARCH*********************************************** */
    /**
     * @Route("/search", name="search")
     */
    public function search(ProduitsRepository $produitsRepos, Request $request)
    {
        $search = $request->query->get('query');

            $produits = $produitsRepos->search($search);

        return $this->render('accueil/produit.html.twig', [
            'produits' => $produits,
        ]);
    }

}
