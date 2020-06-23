<?php

namespace App\Controller;

use App\Entity\OptionGroupe;
use App\Form\OptionGroupeType;
use App\Repository\OptionGroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class OptionGroupeController extends AbstractController
{
    /**
     * @Route("/optiongroupe", name="option_groupe_index", methods={"GET"})
     */
    public function index(OptionGroupeRepository $optionGroupeRepository): Response
    {
        return $this->render('admin/option_groupe/index.html.twig', [
            'option_groupes' => $optionGroupeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/optiongroupe/new", name="option_groupe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $optionGroupe = new OptionGroupe();
        $form = $this->createForm(OptionGroupeType::class, $optionGroupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($optionGroupe);
            $entityManager->flush();

            return $this->redirectToRoute('option_groupe_index');
        }

        return $this->render('admin/option_groupe/new.html.twig', [
            'option_groupe' => $optionGroupe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/optiongroupe/{id}", name="option_groupe_show", methods={"GET"})
     */
    public function show(OptionGroupe $optionGroupe): Response
    {
        return $this->render('admin/option_groupe/show.html.twig', [
            'option_groupe' => $optionGroupe,
        ]);
    }

    /**
     * @Route("/optiongroupe/{id}/edit", name="option_groupe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OptionGroupe $optionGroupe): Response
    {
        $form = $this->createForm(OptionGroupeType::class, $optionGroupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('option_groupe_index');
        }

        return $this->render('admin/option_groupe/edit.html.twig', [
            'option_groupe' => $optionGroupe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/optiongroupe/delete/{id}", name="option_groupe_delete")
     */
    public function delete(OptionGroupe $optionGroupe): Response
    {
        if($optionGroupe){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($optionGroupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('option_groupe_index');
    }
}
