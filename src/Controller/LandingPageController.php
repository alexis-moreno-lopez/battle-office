<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\DeliveryLocations;
use App\Form\CommandeType;
use App\Form\DeliveryLocationsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    // Ton code ici
    #[Route('/', name: 'landing_page')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {

        // Ton code ici
        $commande = new Commande();
        $formCommande = $this->createForm(CommandeType::class, $commande);
        $formCommande->handleRequest($request);

        // CHERCHER TOUS VOS PRODUITS 
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            //  dd($request->request->all()['order']['cart']['cart_products'][0]); RECUPERE l'id DE VOTRE PRODUIT

            $entityManagerInterface->persist($commande);
            $entityManagerInterface->flush();
            # code...
        }
        // set les dates si le formulaire est valide 
        return $this->render('landing_page/index_new.html.twig', [
            'form' => $formCommande,
            // ENVOYER VOS PRODUITS DANS LA VUE

        ]);
    }

    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(): Response
    {
        return $this->render('landing_page/confirmation.html.twig');
    }
}
