<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $paniers = $entityManager->getRepository(Panier::class)->findBy(['user' => $this->getUSer()]);

        return $this->render('commande/index.html.twig', [
            'paniers' => $paniers
        ]);
    }

    /**
     * @Route("/commande/details/{id}", name="commande_details")
     */
    public function commandeDetails($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        $panierProduits = $entityManager->getRepository(PanierProduit::class)->findBy(['panier' => $id]);

        return $this->render('commande/details.html.twig', [
            'panier' => $panier,
            'panierProduits' => $panierProduits
        ]);
    }

    /**
     * @Route("/generer/facture/{id}", name="genererFactureClients")
     */
    public function genererFacture($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $paniers = $entityManager->getRepository(Panier::class)->find($id);
        $produitPaniers = $entityManager->getRepository(PanierProduit::class)->findBy(['panier' => $id]);


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'paniers' => $paniers,
            'produitPaniers' => $produitPaniers
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("doc.pdf", [
            "Attachment" => false
        ]);

    }


}
