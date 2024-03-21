<?php
namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Product;
use App\Form\CommandeType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, Product $product): Response
    {
        // Créer une nouvelle instance de Commande et créer le formulaire
        $commande = new Commande();
        $formCommande = $this->createForm(CommandeType::class, $commande);
        $formCommande->handleRequest($request);

        // Récupérer tous les produits
        $products = $productRepository->findAll();

        // Traiter le formulaire s'il est soumis et valide
        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            // récupérer le produit et lui donner
            $commande->setProduct($product);
            $entityManager->persist($commande);
            $entityManager->flush();
            // Rediriger ou afficher un message de confirmation, etc.
            $this->sendApiRequest($commande);
            return $this->redirectToRoute('confirmation');
        }

        // Passer les données du formulaire et des produits à la vue Twig
        return $this->render('landing_page/index_new.html.twig', [
            'form' => $formCommande->createView(),
            'products' => $products,
        ]);
    }

    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation(): Response
    {
        return $this->render('landing_page/confirmation.html.twig');
    }

    public function sendApiRequest(Commande $commande) {
        $client = new \GuzzleHttp\Client();
        $client->request('POST', 'https://api-commerce.simplon-roanne.com/order', [
            'order' => [
                'id' => $commande->getId(),
                'product' => $commande->getProduct(),
                'payment_method' => $commande->getPayment(),
                'status' => $commande->getPayment(),
                'client' => [
                'firstname' => $commande->getFirstName(),
                'lastname' => $commande->getlastName(),
                'email' => $commande->getEmail(),
                 ],
                'addresses' => [
                'billing' => [

                
                'address_line1' => $commande->getAdress(),
                'address_line2' => $commande->getAdressSup(),
                'city' => $commande->getCity(),
                'zipcode' => $commande->getCp(),
                'country' => $commande->getCountry(),
                'phone' => $commande->getPhone(),
                ],
                'shipping' => [
                'address_line1' => $commande->getAdress(),
                'address_line2' => $commande->getAdressSup(),
                'city' => $commande->getCity(),
                'zipcode' => $commande->getCp(),
                'country' => $commande->getCountry(),
                'phone' => $commande->getPhone(),  
                ]

 
             ]    
            ]
        ]);
    }
}
