<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            $message = (new \Swift_Message($form->get('objet')->getData()))
            ->setFrom($form->get('email')->getData())
            ->setTo('rola.zaitoni@gmail.com')
            ->setBody($form->get('message')->getData());


            $mailer->send($message);

            $this->addFlash('success1','Your email has been sent correctly');

            $form = $this->createForm(ContactType::class);
            
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
