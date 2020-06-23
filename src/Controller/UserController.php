<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduits;
use App\Entity\User;
use App\Form\UserEditConnexionType;
use App\Form\UserEditPassType;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Dompdf\Dompdf;
use Dompdf\Options;

class UserController extends AbstractController
{

    /************************************COMPTE**************************************************** */

    /**
     * @Route("/compte", name="compte")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }


    /**
     * @Route("/compte/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('user/profil_view.html.twig', [
            
        ]);
    }



    /********************************EDIT MES PARAMETRES******************************************* */

    /**
     * @Route("/compte/profil/edit/info-connexion", name="account_edit_info_connexion")
     */
    public function user_edit_info_connexion(Request $request)
    {
        $form = $this->createForm(UserEditConnexionType::class, $this->getUser());
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()) 
        {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($this->getUser());
            $doctrine->flush();

            $this->addFlash('success', 'Vos informations ont été mise à jour !');
            return $this->redirectToRoute('app_logout');

        }
        return $this->render('user/edit_info_connexion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/profil/edit/info-personnel", name="account_edit_info_personnel")
     */
    public function user_edit_info_personnel(Request $request)
    {
        $form = $this->createForm(UserEditType::class, $this->getUser());
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()) 
        {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($this->getUser());
            $doctrine->flush();

            $this->addFlash('success', 'Vos informations ont été mise à jour !');
            return $this->redirectToRoute('app_logout');

        }
        return $this->render('user/edit_info_personnel.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/profil/edit/info-securite", name="account_edit_info_security")
     */

    public function EditPass (Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserEditPassType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {


            $valide = $encoder->isPasswordValid($this->getUser(), $form->get('password')->getData());

            if($valide){
                $user = $this->getUser();
                $password = $form->get('newpass')->getData();
                $hash_password = $encoder->encodePassword($user, $password);

                $user->setPassword($hash_password);

                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($user);
                $doctrine->flush();

                $this->addFlash('success', 'Le mot de passe est modifier');
                return $this->redirectToRoute('app_logout');

            }
            else{
                
                $this->addFlash('error', 'Le mot de passe actuel est incorrect');
            }
        }


        return $this->render('user/edit_info_pass.html.twig', array (
            'form' => $form->createView()
        ));
    }

    // /**
    //  * @Route("/compte/profil/supprimer/{id}", name="supprime_compte")
    //  */
    // public function supprimerCompte(User $user)
    // {
    //     $id = $this->getDoctrine()->getRepository(User::class)->find($user);

    //     if($id){

    //         // Supprime lèarticle en BDD
    //         $doctrine = $this->getDoctrine()->getManager();
    //         $doctrine->remove($id);
    //         $doctrine->flush();
            
    //         $this->addFlash('success','Le produit est correctement supprimée');
    //     }

    //     return $this->redirectToRoute('accueil');
    // }




    /********************************RESET PASSWORD******************************************* */

    /**
     * @Route("/reset/password", name="reset_password")
     */
    public function resetPassword(Request $request,  \Swift_Mailer $mailer)
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);

            if($user != null){
                $token = md5(uniqid());
                $user->setToken($token);

                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($user);
                $doctrine->flush();

                $url = 'http://e_commerce.test/reset/password/'.$token;
                $text = 'Bonjour, voici votre lien de réinitialisation de votre mot de passe ' . $url;

                $mail = (new \Swift_Message('test'))
                ->setFrom($email)
                ->setTo('rola.zaitoni@gmail.com')
                ->setBody($text);

                $mailer->send($mail);
                $this->addFlash('emailreset-success', 'Un e-mail à été envoyé, cliquer sur le lien pour réinitialiser votre mot de passe.');


            }else{
                $this->addFlash('emailreset-incorrect', "Votre adresse e-mail n'existe pas");
            }
        }
        return $this->render('reset/index.html.twig', [
            
        ]);
    }

    /**
     * @Route("/reset/password/{token}", name="reset_password_action")
     */
    public function resetPasswordAction($token, Request $request,  \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $resultat = $entityManager->getRepository(User::class)->findOneBy(array('token' => $token));
        // $password = $request->request->get('password');
        
        if($resultat != null){

            // $hash_password = $encoder->encodePassword($resultat, $password);

            if($request->getMethod() == "POST"){

                $resultat->setPassword($encoder->encodePassword($resultat, $request->request->get('password')));

                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($resultat);
                $doctrine->flush();

                $resultat->setToken('');
                $doctrine = $this->getDoctrine()->getManager();
                $doctrine->persist($resultat);
                $doctrine->flush();


                $text = 'Votre mot de passe a bien été réinitialisé, un email de confirmation vous a été envoyé ';

                $mail = (new \Swift_Message('test'))
                    ->setFrom('server@server.com')
                    ->setTo($resultat->getEmail())
                    ->setBody($text);

                $mailer->send($mail);
                $this->addFlash('reset-success', 'Mot de passe changer');  
            }
           
        }else{
            $this->addFlash('reset-error', "Erreur, le token est invalide");

        }
       
        return $this->render('reset/action.html.twig', [
            'resultats' => $resultat
        ]);
    }


}
