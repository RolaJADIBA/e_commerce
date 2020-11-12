<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;
use Symfony\Component\Filesystem\Filesystem;


/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="produits_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('admin/produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    /****************************************NEW********************************************** */

    /**
     * @Route("produits/new", name="produits_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produits();

        $lists = [];

        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload un Image

            $images = $form->get('images')->getData();
            $image_choisi = $form->get('image_choisi')->getData();

            foreach($images as $image){

                $nouveau_nom = md5(uniqid()).'.'.$image->guessExtension();

                //Récupère les informations du fichier
                $image->move(
                    $this->getParameter('image_produits'),
                    $nouveau_nom
                );
                $lists[] = $nouveau_nom;
            }
            // if($produit->getImageChoisi()){

                $nouveau_nom_choisi = md5(uniqid()).'.'.$image_choisi->guessExtension();

                $image_choisi->move(
                    $this->getParameter('image_choisi'),
                    $nouveau_nom_choisi
                );

                $produit->setImageChoisi($nouveau_nom_choisi);
            // }

            $produit->setImages($lists);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produits_index');
        }

        return $this->render('admin/produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /*************************************SHOW*************************************************** */

    /**
     * @Route("produits/{id}", name="produits_show", methods={"GET"})
     */
    public function show(Produits $produit): Response
    {
        return $this->render('admin/produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /***************************************EDITER*************************************************** */

    /**
     * @Route("produits/{id}/edit", name="produits_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produits $id): Response
    {
        $ancienne_photos = [];
        $ancienne_photos = $id->getImages();
        $ancienne_photo_choisi = $id->getImageChoisi();

        $form = $this->createForm(ProduitsType::class, $id);
        $form->handleRequest($request);
        $lists = [];

        if ($form->isSubmitted() && $form->isValid()) {

            // Si getImage() est non null, on modifie la photo
            if($id->getImages()) {

                if($ancienne_photos != null){
                // Supprime l'ancienne photo
                $filesystem= new Filesystem();

                foreach($ancienne_photos as $ancienne_photo){

                    $filesystem->remove('img/'. $ancienne_photo);
                }
                }

                $images = $form->get('images')->getData();

                foreach($images as $image){

                $nouveau_nom = md5(uniqid()) .'.'. $image->guessExtension();

                $image->move(
                    $this->getParameter('image_produits'),
                    $nouveau_nom
                );

                $lists[] = $nouveau_nom;
                
                $id->setImages($lists);
            }

        }else{
            $id->setImages($ancienne_photos);
        }
    
        if($id->getImageChoisi()){

            if($ancienne_photo_choisi != null){

                $filesystem1= new Filesystem();

                $filesystem1->remove('img_choisi/' . $ancienne_photo_choisi);
            }
                $image_choisi = $form->get('image_choisi')->getData();

                $nouveau_nom_choisi = md5(uniqid()) .'.'. $image_choisi->guessExtension();

                $image_choisi->move(
                    $this->getParameter('image_choisi'),
                    $nouveau_nom_choisi
                );
    
                $id->setImageChoisi($nouveau_nom_choisi);

            }else{

                $id->setImageChoisi($ancienne_photo_choisi);

            }
        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($id);
            $entityManager->flush();

            return $this->redirectToRoute('produits_index');
            
        }

        return $this->render('admin/produits/edit.html.twig', [
            'produit' => $id,
            'form' => $form->createView(),
        ]);
}

    /***********************************DELETE****************************************** */

    /**
     * @Route("produits/delete/{id}", name="produits_delete")
     */
    public function delete(Produits $id, Produits $produits): Response
    {

        $ancienne_photos = [];
        $ancienne_photos = $produits->getImages();
        $ancienne_photo_choisi = $produits->getImageChoisi();
        // dd($ancienne_photo_choisi);

        // Supprime la photo du serveur si getImage() n'est pas NULL
        if($id->getImages()){

            $filesystem = new Filesystem();

            foreach($ancienne_photos as $ancienne_photo){

            $filesystem->remove('img/'. $ancienne_photo);

                }                    
            }

            if($id->getImageChoisi()){

                $filesystem1 = new Filesystem();
                $filesystem1->remove('img_choisi/' . $ancienne_photo_choisi);

            }

        //Si $id n'est pas vide, on supprime la catégorie
        if($id){

            // Supprime lèarticle en BDD
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->remove($id);
            $doctrine->flush();
            
            $this->addFlash('success','Le produit est correctement supprimée');
        }

        else{
            $this->addFlash('error', "Le produit n'existe pas");
        }       

        return $this->redirectToRoute('produits_index');
    }

    /**
     * @Route("/favoris/ajout/{id}", name="produits_ajout_favoris")
     */
    public function ajoutFavoris(Produits $produit)
    {
        if(!$produit){
            throw new EnvNotFoundException('Pas d\'annonce trouvée');
        }

        $option = $produit->getOptions()->getId();
        
        $produits = $this->getDoctrine()->getRepository(Produits::class)->findBy(['options' => $option]);

        $produit->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        return $this->render('accueil/produit.html.twig',[
            'produits' => $produits
        ]);       
    }

    /**
     * @Route("/favoris/retrait/{id}", name="produits_retrait_favoris")
     */
    public function retraitFavoris(Produits $produit)
    {
        if(!$produit){
            throw new EnvNotFoundException('Pas d\'annonce trouvée');
        }

        $option = $produit->getOptions()->getId();
        
        $produits = $this->getDoctrine()->getRepository(Produits::class)->findBy(['options' => $option]);
        
        $produit->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        return $this->render('accueil/produit.html.twig',[
            'produits' => $produits
        ]);
    }

}
