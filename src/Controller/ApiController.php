<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class ApiController extends AbstractController
{
    /**
     * @Route("/api/produits/liste", name="liste", methods={"GET"})
     */
    public function liste(SerializerInterface $serializer,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if($request->isMethod('GET')){

            $produits = $entityManager->getRepository(Produits::class)->findAll();

            $users = $entityManager->getRepository(User::class)->findAll();


            $res = [];

            foreach($users as $user){
                $res[] = ['email' => $user->getEmail(), 'password' => $user->getPassword(), 'token' => $user->getToken() ];
            }

            $resultat = [];

            foreach($produits as $produit){
                    $resultat[] = ['id' => $produit->getId(),
                    'nom' => $produit->getNom(), 
                    'prix' => $produit->getPrix(),
                    'image_choisi'=>$produit->getImageChoisi(), 
                    'description' => $produit->getDescription(),
                    'inCart' => $produit->getInCart(),
                    'count' => $produit->getCount(),
                    'total' => $produit->getTotal(),
                    'options' => $produit->getOptions()->getNom(),
                    'categorie'=> $produit->getCategorie()->getNom() 
                ];
            }


            $result = ['success' => true, 'produits' => $resultat , 'users' => $res];

            $response = new Response($serializer->serialize($result, 'json'));

            // On ajoute l'entete HTTP
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Request-Method', '*');
            $response->headers->set('Access-Control-Request-Headers', '*');
            $response->headers->set('Access-Control-Allow-Methods', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With', 'Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age:', '0');
            $response->headers->set('Cache-Control:', 'max-age=0');
            $response->headers->set('Cache-Control:', 's-maxage=0');
            $response->headers->set('Cache-Control:', 'no-store');
            $response->headers->set('Cache-Control:', 'no-cache');
               
            //On envoie la reponse
            return $response;  
        }
        else {
            $message = ['succes' => false, 'erreur_code' => '001', 'erreur_message' => 'Requete invalide'];

            $response = new Response($serializer->serialize($message, 'json'));

            // On ajoute l'entete HTTP
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Request-Method', '*');
            $response->headers->set('Access-Control-Request-Headers', '*');
            $response->headers->set('Access-Control-Allow-Methods', '*');
            $response->headers->set('Access-Control-Allow-Headers', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            $response->headers->set('Access-Control-Max-Age:', '0');
            $response->headers->set('Cache-Control:', 'max-age=0');
            $response->headers->set('Cache-Control:', 's-maxage=0');
            $response->headers->set('Cache-Control:', 'no-store');
            $response->headers->set('Cache-Control:', 'no-cache');
    
            //On envoie la reponse
            return $response;  
        }

    }

    /**
     * @Route("/api/user/{token}", name="user", methods={"GET"})
     */
    public function user(SerializerInterface $serializer,Request $request,$token, UserRepository $userRepo)
    {

        if($request->isMethod('GET')){


            $users = $userRepo->findBy(['token' => $token]);

            $res = [];

            foreach($users as $user){
                $res[] = ['email' => $user->getEmail(), 'password' => $user->getPassword(), 
                            'prenom' => $user->getPrenom(), 'nom' => $user->getNom() ];
            }


            $result = ['success' => true, 'user' => $res];

            $response = new Response($serializer->serialize($result, 'json'));

            // On ajoute l'entete HTTP
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Request-Method', '*');
            $response->headers->set('Access-Control-Request-Headers', '*');
            $response->headers->set('Access-Control-Allow-Methods', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With', 'Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age:', '0');
            $response->headers->set('Cache-Control:', 'max-age=0');
            $response->headers->set('Cache-Control:', 's-maxage=0');
            $response->headers->set('Cache-Control:', 'no-store');
            $response->headers->set('Cache-Control:', 'no-cache');
               
            //On envoie la reponse
            return $response;  
        }
        else {
            $message = ['succes' => false, 'erreur_code' => '001', 'erreur_message' => 'Requete invalide'];

            $response = new Response($serializer->serialize($message, 'json'));

            // On ajoute l'entete HTTP
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Request-Method', '*');
            $response->headers->set('Access-Control-Request-Headers', '*');
            $response->headers->set('Access-Control-Allow-Methods', '*');
            $response->headers->set('Access-Control-Allow-Headers', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            $response->headers->set('Access-Control-Max-Age:', '0');
            $response->headers->set('Cache-Control:', 'max-age=0');
            $response->headers->set('Cache-Control:', 's-maxage=0');
            $response->headers->set('Cache-Control:', 'no-store');
            $response->headers->set('Cache-Control:', 'no-cache');
    
            //On envoie la reponse
            return $response;  
        }

    }


    /** 
     * @Route("/api/login", name="login", methods={"POST"})
     */

    public function login(Request $request, UserRepository $userRepo)
    {
        if ($request->isMethod('POST')) {

            $donnees = json_decode($request->getContent());

            $username = $donnees->email;
            $password = $donnees->password;

            if ((isset($username) && !empty($username)) && (isset($password) && !empty($password))) {

                $user = $userRepo->findOneBy(['email' => $username]);

                if ($user) {
                    if (password_verify($password, $user->getPassword())) {
                        return  $this->json(['success' => true, 'token' => $user->getToken(), 'prenom' => $user->getPrenom()]);
                    }
                } else {
                    return  $this->json(['sucess' => false]);
                }
            }
             else {
                return    $this->json(['success' => false, 'erreur' => 'Information manquante. Code : 001']);
            }
        } else {
            return $this->json(['message' => 'Requête invalide']);
        }
    }

    // /**
    //  * @Route("/api/user/lire", name="lire", methods={"POST"})
    //  */
    // public function getUser(Request $request, UserRepository $userRepo)
    // {
    //     if ($request->isMethod('POST')) {

    //         $donnees = json_decode($request->getContent());

    //         $token = $donnees->token;

    //         if ((isset($token) && !empty($token))) {

    //             $user = $userRepo->findOneBy(['token' => $token]);

    //             return  $this->json(['success' => true, 'prenom' => $user->getPrenom()]);
    //         }
    //          else {
    //             return    $this->json(['success' => false, 'erreur' => 'Information manquante. Code : 001']);
    //         }
    //     } else {
    //         return $this->json(['message' => 'Requête invalide']);
    //     }

    // }

    /**
     * @Route("api/user/lire/{token}", name="lire", methods={"GET"})
     */
    public function getProduit($token, UserRepository $userRepo)
    {
            $user = $userRepo->findOneBy(['token' => $token]);

            return  $this->json(['success' => true, 'prenom' => $user->getPrenom()]);

    }
    

    /**
     * @Route("/api/ajout/user", name="ajout", methods={"POST"})
     */

    public function addUser(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $donnees = json_decode($request->getContent());
        $token = md5(uniqid());
       
        $user = new User();

        // $token = $this->jwtManager->create($user);

        $user->setEmail($donnees->email);
        $user->setRoles([]);
        $user->setPassword($passwordEncoder->encodePassword($user,$donnees->password));
        $user->setNom($donnees->nom);
        $user->setPrenom($donnees->prenom);
        $user->setToken($token);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        //On retourne la confirmation
        return  $this->json(['success' => true]);
    }

    /********************************STRIPE*********************************** */

    /**
     * @Route("/api/charge", name="stripeApi", methods={"POST"})
     */
    public function Stripe(Request $request)
    {
        // On récupère le total depuis le formulaire
        // $total = $request->request->get('total');
        $donnees = json_decode($request->getContent());
        $total = $donnees->total;

        // Paramétrage de la clès API de STRIPE
        \Stripe\Stripe::setApiKey("sk_test_9C3nIrEYCAzA2Pa02bfAybpj00kF4Qpsin");

        // On créer une charge de paiement
        $charge = \Stripe\Charge::create([
        "amount" => $total * 100,
        "currency" => "eur",
        "source" => "tok_mastercard", // obtained with Stripe.js
        // "description" => "paiement de ". $this->getUser()->getEmail(),
        ], [
        //   "idempotency_key" => "fp0unaRbAVdbpVf0",
        ]);

        return $this->redirectToRoute('ajoutPanierBdd', [
            'total' => $total
        ]);
        
    }


}
